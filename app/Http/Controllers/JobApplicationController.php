<?php

namespace App\Http\Controllers;
use App\JobApplication;
use App\JobApplicant;
use App\JobVacancie;
use Illuminate\Http\Request;

use App\Selection;
use App\SelectionApplicant;
use App\JobVacancyStage;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;
use App\RecruitmentBatch;
use App\Mail\BatchInfoMail;
use App\Mail\ApplicationStatusMail;
use App\Mail\JobOfferingMail;
use Illuminate\Support\Facades\Mail;
use App\RecruitmentBatchStage;

class JobApplicationController extends Controller
{

    /**
     * Display a listing of the resource (Grouped by Applicant).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Redirect to vacancy management or keep as is? 
        // Better to provide choice or default to vacancy management
        return $this->manageApplications(request());
    }

    /**
     * Manage applications by vacancy and Phase.
     */
    public function manageApplications(Request $request)
    {
        $user = auth()->user();
        $vacanciesQuery = JobVacancie::with(['batches.stages.selection']);

        // Filter Vacancies for HR
        if ($user && !$user->isSuperAdmin()) {
            $vacanciesQuery->whereHas('hrs', function ($q) use ($user) {
                $q->where('job_vacancy_hr.user_id', $user->user_id);
            });
        }

        $vacancies = $vacanciesQuery->get();

        // Validate or Default selected vacancy
        $selectedVacancyId = $request->vacancy_id;

        if (!$selectedVacancyId && $vacancies->count() > 0) {
            $selectedVacancyId = $vacancies->first()->vacancies_id;
        }

        // Ensure HR can't access non-assigned vacancy by ID
        if ($selectedVacancyId && !$user->isSuperAdmin() && !$vacancies->pluck('vacancies_id')->contains($selectedVacancyId)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke lowongan ini.'], 403);
            }
            return redirect()->route('jobapplication.manage', ['vacancy_id' => $vacancies->first()->vacancies_id ?? null])
                ->with('error', 'Anda tidak memiliki akses ke lowongan tersebut.');
        }

        $phase = $request->phase ?? 'review'; // review, selection, offering

        $query = JobApplication::with(['jobApplicant', 'selectionApplicant', 'batch.stages.selection.aspects', 'selectionApplicant.selection.aspects', 'jobVacancie'])
            ->where('vacancies_id', $selectedVacancyId);

        if ($phase == 'review') {
            $query->whereIn('status', ['pending', 'applied']);
        }
        elseif ($phase == 'selection') {
            $query->where('status', 'process');
        }
        elseif ($phase == 'offering') {
            $query->whereIn('status', ['offering', 'offering_sent', 'negotiation_requested']);
        }
        elseif ($phase == 'final') {
            $query->whereIn('status', ['hired', 'accepted', 'rejected']);
        }

        if ($request->search) {
            $query->whereHas('jobApplicant', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->paginate(50);
        $batches = RecruitmentBatch::where('vacancies_id', $selectedVacancyId)->get();

        return view('jobapplication.manage', compact('vacancies', 'selectedVacancyId', 'applications', 'phase', 'batches'));
    }

    /**
     * Phase 3: Review Berkas (Luluskan ke Tahap Seleksi)
     */
    public function batchProcess(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'action' => 'required|in:pass_review,fail_review'
        ]);

        $status = ($request->action == 'pass_review') ? 'process' : 'rejected';

        $applications = JobApplication::whereIn('application_id', $request->application_ids)->get();
        foreach ($applications as $app) {
            $app->update(['status' => $status]);

            // Send email
            $statusLabel = ($status == 'process') ? 'Lulus Review Berkas' : 'Tidak Lulus Review Berkas';
            $msg = ($status == 'process') ? 'Selamat! Berkas Anda telah kami review dan Anda dinyatakan LULUS untuk tahap selanjutnya.' : 'Terima kasih atas lamaran Anda. Mohon maaf, lamaran Anda belum dapat kami proses ke tahap selanjutnya.';

            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new ApplicationStatusMail($app, $statusLabel, $msg));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email review: ' . $e->getMessage());
            }

            // Hapus akun login jika keputusan final (rejected)
            if ($status == 'rejected') {
                $this->cleanupFinalizedApplicant($app);
            }
        }


        ActivityLog::log('Melakukan review berkas massal: ' . count($request->application_ids) . ' pelamar dikelola.', 'Proses Lamaran');

        return redirect()->back()->with('success', 'Review berkas berhasil diproses dan email notifikasi telah dikirim.');
    }

    /**
     * Phase 4: Assign to Batch
     */
    public function assignBatch(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'batch_id' => 'required|exists:recruitment_batches,id'
        ]);

        $applications = JobApplication::whereIn('application_id', $request->application_ids)->get();
        foreach ($applications as $app) {
            $app->update(['batch_id' => $request->batch_id]);

            // Send email
            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new BatchInfoMail($app->load('batch.stages.selection')));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email batch: ' . $e->getMessage());
            }
        }

        ActivityLog::log('Menugaskan ' . count($request->application_ids) . ' pelamar ke Batch ID: ' . $request->batch_id, 'Proses Lamaran');

        return redirect()->back()->with('success', 'Pelamar berhasil ditugaskan ke batch dan email jadwal telah dikirim.');
    }

    /**
     * Phase 4: Input Score & Luluskan ke Offering
     */
    public function inputScore(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:job_applications,application_id',
            'selection_id' => 'required|exists:selection,selection_id',
            'batch_stage_id' => 'required|exists:recruitment_batch_stages,id',
            'notes' => 'nullable|string',
            'action' => 'required|in:pass,fail,save_only',
            'aspects' => 'nullable|array',
            'aspects.*' => 'numeric|min:0|max:5',
            'score' => 'nullable|numeric'
        ]);

        $score = $request->score ?? 0;
        $status = 'process';

        if ($request->has('aspects') && count($request->aspects) > 0) {
            $totalAspects = count($request->aspects);
            $avgStars = array_sum($request->aspects) / $totalAspects;
            $score = ($avgStars / 5) * 100;
        }


        $notes = $request->notes;
        if (empty($notes) || trim($notes) === '-') {
            if ($status === 'passed') {
                $notes = "Memenuhi kriteria kelulusan dengan nilai yang baik/memuaskan.";
            }
            elseif ($status === 'failed') {
                $notes = "Gagal. Belum mencapai standar evaluasi yang telah ditetapkan pada tahap ini.";
            }
            else {
                $notes = "Menunggu penilaian lebih lanjut.";
            }
        }

        $selectionApp = SelectionApplicant::updateOrCreate(
        [
            'application_id' => $request->application_id,
            'selection_id' => $request->selection_id,
        ],
        [
            'batch_stage_id' => $request->batch_stage_id,
            'score' => $score,
            'notes' => $notes,
            'status' => $status,
        ]
        );

        if ($request->has('aspects') && count($request->aspects) > 0) {
            foreach ($request->aspects as $aspectId => $aspectScore) {
                \App\SelectionApplicantScore::updateOrCreate(
                [
                    'selection_applicant_id' => $selectionApp->selection_applicant_id,
                    'aspect_id' => $aspectId,
                ],
                [
                    'score' => $aspectScore,
                ]
                );
            }
        }

        if ($request->action == 'pass') {
            $app = JobApplication::with('jobApplicant.user', 'jobVacancie', 'batch.stages')->find($request->application_id);

            if (!$app->isSelectionCompleted()) {
                $msg = 'Tidak dapat meluluskan ke Offering. Masih ada tahapan seleksi yang belum dinilai untuk pelamar ini (Proses Seleksi belum 100%).';
                if ($request->ajax()) {
                    return response()->json(['message' => $msg], 422);
                }
                return redirect()->back()->with('error', $msg);
            }

            $app->update(['status' => 'offering']);

            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new ApplicationStatusMail($app, 'Lulus Seleksi', 'Selamat! Anda telah dinyatakan LULUS pada seluruh rangkaian proses seleksi. Kami akan segera mengirimkan surat penawaran (Offering Letter) kepada Anda.'));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email pass selection: ' . $e->getMessage());
            }
        }
        elseif ($request->action == 'fail') {
            $app = JobApplication::with('jobApplicant', 'jobVacancie', 'batch.stages')->find($request->application_id);

            if (!$app->isSelectionCompleted()) {
                return redirect()->back()->with('error', 'Tidak dapat menggagalkan pelamar. Seluruh tahapan seleksi harus dinilai terlebih dahulu agar data evaluasi lengkap (100%).');
            }

            $app->update(['status' => 'rejected']);

            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new ApplicationStatusMail($app, 'Tidak Lulus Seleksi', 'Mohon maaf, Anda dinyatakan tidak lulus.'));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email fail selection: ' . $e->getMessage());
            }

            // Hapus akun login pelamar setelah keputusan final
            $this->cleanupFinalizedApplicant($app);
        }
        else {
            // Cukup simpan nilai tanpa aggregasi otomatis Lulus/Gagal
            return redirect()->back()->with('success', 'Nilai "' . ($selectionApp->selection->name ?? 'Tahapan') . '" berhasil disimpan dengan skor ' . number_format($score, 1) . '%. Silakan tentukan keputusan akhir secara manual.');
        }

        return redirect()->back()->with('success', 'Nilai seleksi pelamar berhasil disimpan.');
    }

    public function promoteToOffering(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'offering_job_desc' => 'nullable|string',
            'offering_salary' => 'nullable|numeric',
            'offering_working_hours' => 'nullable|string',
            'offering_leave_quota' => 'nullable|string',
            'offering_start_date' => 'required|date',
        ]);

        $applications = JobApplication::with(['jobApplicant.user', 'jobVacancie', 'batch.stages'])->whereIn('application_id', $request->application_ids)->get();

        $successCount = 0;
        $skippedNames = [];

        foreach ($applications as $app) {
            // Check if selection is completed OR they are already in offering phase
            $isOfferingPhase = in_array($app->status, ['offering', 'offering_sent', 'negotiating', 'accepted', 'rejected', 'negotiation_requested']);

            if (!$app->isSelectionCompleted() && !$isOfferingPhase) {
                $skippedNames[] = $app->jobApplicant->name;
                continue;
            }

            // Generate letter number if not exists
            $letterNo = $app->offering_letter_no ?? $app->generateOfferingLetterNo();

            $app->update([
                'status' => 'offering', // Draft Status
                'offering_job_desc' => $request->offering_job_desc ?? $app->jobVacancie->description,
                'offering_salary' => $request->offering_salary ?? $app->jobVacancie->salary_nominal,
                'offering_working_hours' => $request->offering_working_hours ?? 'Senin - Jumat, 08:00 - 17:00',
                'offering_leave_quota' => $request->offering_leave_quota ?? '12 hari per tahun',
                'offering_start_date' => $request->offering_start_date,
                'offering_letter_no' => $letterNo,
                'updated_at' => now()
            ]);

            $successCount++;
        }

        $msg = $successCount . ' pelamar berhasil dipindahkan ke tahap Offering (Draft).';
        if (count($skippedNames) > 0) {
            $msg .= ' ' . count($skippedNames) . ' pelamar dilewati karena proses seleksi belum selesai.';
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $msg,
                'success_count' => $successCount,
                'skipped_count' => count($skippedNames)
            ], ($successCount > 0 ? 200 : 422));
        }

        return redirect()->back()->with($successCount > 0 ? 'success' : 'error', $msg);
    }

    /**
     * Preview Offering Letter PDF
     */
    public function previewOfferingLetter($id)
    {
        $application = JobApplication::with(['jobApplicant', 'jobVacancie.departement'])->findOrFail($id);

        $settings = [
            'hr_name' => 'HR Manager PT Vneu',
            'hr_position' => 'Human Resources Department'
        ];

        if (!class_exists('PDF')) {
            // Fallback if dompdf not installed
            return view('pdf.offering_letter', compact('application', 'settings'));
        }

        $pdf = \PDF::loadView('pdf.offering_letter', compact('application', 'settings'));
        return $pdf->stream('Offering_Letter_' . str_replace(' ', '_', $application->jobApplicant->name) . '.pdf');
    }

    /**
     * Approve and Send Offering Letter
     */
    public function approveOffering(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
        ]);

        $applications = JobApplication::with(['jobApplicant.user', 'jobVacancie.departement', 'jobVacancie.position'])->whereIn('application_id', $request->application_ids)->get();

        $successCount = 0;
        $errorEmails = [];

        $settings = [
            'hr_name' => 'HR Manager PT Vneu',
            'hr_position' => 'Human Resources Department'
        ];

        foreach ($applications as $app) {
            try {
                // Generate PDF and save to storage
                $pdfName = 'offering_letter_' . $app->application_id . '_' . time() . '.pdf';
                $pdfPath = 'offering_letters/' . $pdfName;

                if (class_exists('PDF')) {
                    $pdf = \PDF::loadView('pdf.offering_letter', ['application' => $app, 'settings' => $settings]);
                    \Storage::disk('public')->put($pdfPath, $pdf->output());
                }
                else {
                    // If dompdf fails, we can't send with attachment
                    // For now let's skip or throw
                    throw new \Exception('Sistem PDF (dompdf) belum terpasang. Mohon hubungi administrator.');
                }

                $app->update([
                    'status' => 'offering_sent',
                    'offering_letter_file' => $pdfPath,
                    'updated_at' => now()
                ]);

                // Send Email
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new JobOfferingMail($app));
                    $successCount++;
                }
                else {
                    $errorEmails[] = $app->jobApplicant->name . ' (Email tidak ditemukan)';
                }

                ActivityLog::log('HR menyetujui dan mengirim Offering Letter untuk ' . $app->jobApplicant->name, 'Offering');

            }
            catch (\Exception $e) {
                $errorEmails[] = $app->jobApplicant->name . ' (' . $e->getMessage() . ')';
                \Log::error('Gagal approve/kirim offering: ' . $e->getMessage());
            }
        }

        $msg = $successCount . ' Offering Letter berhasil dikirim.';
        if (count($errorEmails) > 0) {
            $msg .= ' Masalah: ' . implode(', ', $errorEmails);
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $msg,
                'success' => $successCount > 0
            ], $successCount > 0 ? 200 : 422);
        }
        return redirect()->back()->with($successCount > 0 ? 'success' : 'error', $msg);
    }

    public function failSelection(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
        ]);

        $applications = JobApplication::with('jobApplicant', 'jobVacancie')->whereIn('application_id', $request->application_ids)->get();

        foreach ($applications as $app) {
            $app->update(['status' => 'rejected', 'updated_at' => now()]);
            // Send email
            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    Mail::to($email)->send(new ApplicationStatusMail($app, 'Tidak Lulus Seleksi', 'Mohon maaf, Anda dinyatakan tidak lulus pada tahap seleksi lanjutan.'));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email fail selection bulk: ' . $e->getMessage());
            }

            // Hapus akun login pelamar setelah keputusan final
            $this->cleanupFinalizedApplicant($app);
        }


        ActivityLog::log('Menggagalkan ' . count($request->application_ids) . ' pelamar pada tahap seleksi.', 'Proses Lamaran');

        if ($request->ajax()) {
            return response()->json(['message' => count($request->application_ids) . ' pelamar dinyatakan tidak lulus seleksi dan email notifikasi telah dikirim.']);
        }
        return redirect()->back()->with('success', count($request->application_ids) . ' pelamar dinyatakan tidak lulus seleksi dan email notifikasi telah dikirim.');
    }

    public function finalizeOffering(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'action' => 'required|in:accept,reject'
        ]);

        $status = ($request->action == 'accept') ? 'hired' : 'rejected';

        $applications = JobApplication::with('jobApplicant', 'jobVacancie')->whereIn('application_id', $request->application_ids)->get();

        foreach ($applications as $app) {
            $app->update(['status' => $status]);
            try {
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    $statusLabel = ($status == 'hired') ? 'Selamat! Anda Diterima' : 'Mohon Maaf, Penawaran Ditolak';
                    $msg = ($status == 'hired') ? 'Kami senang mengabarkan bahwa Anda telah resmi bergabung dengan tim kami. Selamat!' : 'Terima kasih atas tanggapan Anda. Kami menghargai keputusan Anda.';
                    Mail::to($email)->send(new ApplicationStatusMail($app, $statusLabel, $msg));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email finalize offering: ' . $e->getMessage());
            }

            // Hapus akun login pelamar setelah keputusan final
            $this->cleanupFinalizedApplicant($app);
        }


        ActivityLog::log('Finalisasi Offering: ' . count($request->application_ids) . ' pelamar diputuskan.', 'Proses Lamaran');

        if ($request->ajax()) {
            return response()->json(['message' => 'Keputusan Offering berhasil disimpan.']);
        }
        return redirect()->back()->with('success', 'Keputusan Offering berhasil disimpan. Akun login pelamar akan otomatis dihapus.');
    }

    public function respondOffering(Request $request, $application_id, $response)
    {
        // Bypass signature check if user is logged in and owns the application
        $isOwner = auth()->check() && JobApplicant::where('user_id', auth()->id())->whereHas('jobApplications', function ($q) use ($application_id) {
            $q->where('application_id', $application_id);
        })->exists();

        if (!$isOwner && !$request->hasValidSignature()) {
            abort(401, 'Link sudah tidak berlaku atau tidak valid.');
        }

        $app = JobApplication::with('jobApplicant', 'jobVacancie')->findOrFail($application_id);

        // Cek status yang valid untuk merespon (offering, offering_sent, atau negotiation_requested jika HR counter)
        if (!in_array($app->status, ['offering', 'offering_sent'])) {
            // Jika status sudah accepted/hired atau rejected, beri pesan informatif
            if (in_array($app->status, ['accepted', 'hired', 'rejected'])) {
                return view('offering_response', [
                    'success' => true,
                    'type' => 'info',
                    'message' => 'Tanggapan Anda sudah kami catat sebelumnya. Status saat ini: ' . strtoupper($app->status),
                    'application' => $app
                ]);
            }
        }

        if ($response == 'accept') {
            $app->update([
                'status' => 'hired',
                'offering_accepted_at' => now()
            ]);
            $message = 'Selamat! Anda telah resmi menerima penawaran kami dan bergabung dengan tim kami. Kami akan segera menghubungi Anda untuk tahap onboarding.';
            ActivityLog::log('Pelamar ' . $app->jobApplicant->name . ' MENERIMA offering (Status: Hired).', 'Offering');

            // Hapus akun login pelamar setelah keputusan final
            $this->cleanupFinalizedApplicant($app);

            return view('offering_response', [
                'success' => true,
                'type' => 'success',
                'message' => $message,
                'application' => $app
            ]);
        }
        elseif ($response == 'reject') {
            $app->update([
                'status' => 'rejected',
                'offering_rejected_at' => now()
            ]);
            $message = 'Terima kasih atas tanggapan Anda. Kami menghargai keputusan Anda dan mendoakan yang terbaik untuk karir Anda ke depan.';
            ActivityLog::log('Pelamar ' . $app->jobApplicant->name . ' MENOLAK offering.', 'Offering');

            // Hapus akun login pelamar setelah keputusan final
            $this->cleanupFinalizedApplicant($app);

            return view('offering_response', [
                'success' => true,
                'type' => 'error',
                'message' => $message,
                'application' => $app
            ]);
        }
        elseif ($response == 'negotiate') {
            // Tampilkan halaman form negosiasi
            return view('offering_response', [
                'success' => true,
                'type' => 'negotiate',
                'message' => 'Silakan sampaikan ekspektasi gaji dan alasan Anda untuk kami pertimbangkan kembali.',
                'application' => $app
            ]);
        }

        abort(404);
    }

    public function submitNegotiation(Request $request, $application_id)
    {
        $request->validate([
            'expected_salary' => 'required|numeric|min:0',
            'negotiation_reason' => 'nullable|string|max:1000',
        ]);

        $app = JobApplication::findOrFail($application_id);

        $app->update([
            'status' => 'negotiation_requested',
            'expected_salary' => $request->expected_salary,
            'negotiation_reason' => $request->negotiation_reason,
            'updated_at' => now()
        ]);

        ActivityLog::log('Pelamar ' . $app->jobApplicant->name . ' mengajukan NEGOSIASI GAJI.', 'Offering');

        return view('offering_response', [
            'success' => true,
            'type' => 'success',
            'message' => 'Permintaan negosiasi Anda telah terkirim. Tim HR kami akan meninjau dan menghubungi Anda kembali. Pantau terus statusnya melalui link tracking Anda.',
            'application' => $app
        ]);
    }

    public function respondNegotiation(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:job_applications,application_id',
            'action' => 'required|in:accept,counter,reject',
            'counter_salary' => 'required_if:action,counter|nullable|numeric',
            'hr_note' => 'nullable|string',
        ]);

        $app = JobApplication::with('jobApplicant', 'jobVacancie')->findOrFail($request->application_id);
        $oldStatus = $app->status;

        if ($request->action == 'accept') {
            $app->update([
                'status' => 'hired',
                'offering_salary' => $app->expected_salary,
                'hr_negotiation_note' => $request->hr_note,
                'offering_accepted_at' => now()
            ]);
            $statusLabel = 'Negosiasi Diterima (Hired)';
            $msg = 'Selamat! Negosiasi gaji Anda telah disetujui. Anda resmi bergabung bersama kami.';
        }
        elseif ($request->action == 'counter') {
            $app->update([
                'status' => 'offering_sent',
                'offering_salary' => $request->counter_salary,
                'hr_negotiation_note' => $request->hr_note,
            ]);
            $statusLabel = 'Counter Offer (Penawaran Baru)';
            $msg = 'HR memberikan penawaran baru (Counter Offer) sesuai pertimbangan tim. Silakan cek detail gaji terbaru Anda.';
        }
        else {
            $app->update([
                'status' => 'rejected',
                'hr_negotiation_note' => $request->hr_note,
                'offering_rejected_at' => now()
            ]);
            $statusLabel = 'Negosiasi Ditolak';
            $msg = 'Mohon maaf, negosiasi gaji belum dapat kami setujui saat ini.';
        }

        // Kirim Email Notifikasi (harus sebelum cleanup agar email masih bisa diambil)
        try {
            $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
            if ($email) {
                Mail::to($email)->send(new ApplicationStatusMail($app, $statusLabel, $msg));
                if ($request->action == 'counter') {
                    // Kirim ulang Offering Mail jika counter
                    Mail::to($email)->send(new JobOfferingMail($app));
                }
            }
        }
        catch (\Exception $e) {
            \Log::error('Gagal kirim email respon negosiasi: ' . $e->getMessage());
        }

        // Hapus akun login pelamar jika keputusan final (hired/rejected)
        if (in_array($request->action, ['accept', 'reject'])) {
            $this->cleanupFinalizedApplicant($app);
        }


        ActivityLog::log('HR merespon negosiasi ' . $app->jobApplicant->name . ': ' . $request->action, 'Offering');


        return redirect()->back()->with('success', 'Keputusan negosiasi berhasil disimpan dan notifikasi telah dikirim.');
    }

    /**
     * Display all applications for a specific applicant.
     *
     * @param  int  $applicantId
     * @return \Illuminate\Http\Response
     */
    public function showApplicantDetails($applicantId)
    {
        $applicant = JobApplicant::with('applications.jobVacancie.departement', 'applications.jobVacancie.position')
            ->findOrFail($applicantId);

        return view('jobapplication.applicant_list', compact('applicant'));
    }

    /**
     * Display the specified resource (Application Detail & Selection Process).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobapplication = JobApplication::with([
            'jobApplicant',
            'jobVacancie.departement',
            'jobVacancie.position',
            'batch.stages.selection',
            'selectionApplicant' => function ($query) {
            $query->with(['selection', 'batchStage.batch'])->orderBy('created_at', 'asc');
        }
        ])->findOrFail($id);

        $selections = Selection::all();

        return view('jobapplication.show', compact('jobapplication', 'selections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the application status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:applied,process,rejected,accepted,pending,approved'
        ]);

        $jobapplication = JobApplication::with('jobApplicant', 'jobVacancie.position')->findOrFail($id);
        if ($jobapplication->status != $request->status) {
            $jobapplication->update([
                'status' => $request->status
            ]);


            $applicantName = $jobapplication->jobApplicant->name ?? 'Pelamar';
            $positionName = $jobapplication->jobVacancie->position->name ?? 'Posisi';
            ActivityLog::log('Mengubah status lamaran ' . $applicantName . ' (' . $positionName . ') menjadi ' . $request->status, 'Lamaran');
        }

        return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui');
    }

    public function addSelectionStage(Request $request, $id)
    {
        $request->validate([
            'selection_id' => 'required|exists:selection,selection_id',
            'batch_stage_id' => 'nullable|exists:recruitment_batch_stages,id',
            'selection_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $jobApp = JobApplication::with('jobApplicant', 'jobVacancie.position')->findOrFail($id);
        $selection = Selection::findOrFail($request->selection_id);

        SelectionApplicant::create([
            'selection_id' => $request->selection_id,
            'application_id' => $id,
            'batch_stage_id' => $request->batch_stage_id,
            'status' => 'unprocess',
            'score' => 0,
            'notes' => '-',
        ]);

        $applicantName = $jobApp->jobApplicant->name ?? 'Pelamar';
        ActivityLog::log('Menambah tahapan seleksi ' . $selection->name . ' untuk pelamar ' . $applicantName, 'Seleksi');

        return redirect()->back()->with('success', 'Tahapan seleksi berhasil ditambahkan');
    }

    public function updateSelectionStage(Request $request, $selectionApplicantId)
    {
        $selection = SelectionApplicant::with('selection', 'jobapplication.jobApplicant')->findOrFail($selectionApplicantId);

        $request->validate([
            'status' => 'required|in:unprocess,process,passed,failed',
            'notes' => 'nullable|string',
            'score' => 'nullable|numeric',
            'selection_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cek jika status ingin diubah ke 'process' (Mulai Proses)
        if ($request->status == 'process' && $selection->status == 'unprocess') {
            $date = $request->selection_date ?? $selection->selection_date;
            if ($date && \Carbon\Carbon::parse($date)->isFuture()) {
            // return redirect()->back()->with('error', 'Proses seleksi belum bisa dimulai sebelum tanggal yang dijadwalkan (' . \Carbon\Carbon::parse($date)->format('d-m-Y') . ').');
            }
        }

        $selection->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $selection->notes,
            'score' => $request->score ?? $selection->score,
            'selection_date' => $request->selection_date ?? $selection->selection_date,
            'start_time' => $request->start_time ?? $selection->start_time,
            'end_time' => $request->end_time ?? $selection->end_time,
            'location' => $request->location ?? $selection->location,
            'description' => $request->description ?? $selection->description,
        ]);

        // Send Email Notification for Stage Update
        if (in_array($request->status, ['passed', 'failed'])) {
            try {
                $app = JobApplication::with('jobApplicant', 'jobVacancie')->find($selection->application_id);
                $email = $app->jobApplicant->user->email ?? $app->jobApplicant->email;
                if ($email) {
                    $stageName = $selection->selection->name ?? 'Seleksi';
                    $statusLabel = ($request->status == 'passed') ? 'Lulus Tahap Seleksi' : 'Tidak Lulus Tahap Seleksi';
                    $msg = ($request->status == 'passed')
                        ? "Selamat! Anda dinyatakan LULUS pada tahap seleksi **{$stageName}**."
                        : "Mohon maaf, Anda dinyatakan belum lulus pada tahap seleksi **{$stageName}**.";

                    Mail::to($email)->send(new ApplicationStatusMail($app, $statusLabel, $msg));
                }
            }
            catch (\Exception $e) {
                \Log::error('Gagal kirim email stage update: ' . $e->getMessage());
            }
        }

        $selectionName = $selection->selection->name ?? 'Seleksi';
        $applicantName = $selection->jobapplication->jobApplicant->name ?? 'Pelamar';
        ActivityLog::log('Memperbarui tahapan seleksi ' . $selectionName . ' untuk ' . $applicantName, 'Seleksi');

        return redirect()->back()->with('success', 'Detail seleksi berhasil diperbarui');
    }

    public function deleteSelectionStage($selectionApplicantId)
    {
        $selection = SelectionApplicant::with('selection', 'jobapplication.jobApplicant')->findOrFail($selectionApplicantId);
        $appId = $selection->application_id;
        $selectionName = $selection->selection->name ?? 'Seleksi';
        $applicantName = $selection->jobapplication->jobApplicant->name ?? 'Pelamar';

        $selection->delete();

        ActivityLog::log('Menghapus tahapan seleksi ' . $selectionName . ' untuk pelamar ' . $applicantName, 'Seleksi');

        return redirect()->back()->with('success', 'Tahapan seleksi dihapus');
    }

    public function sendSelectionUpdateEmail($id)
    {
        $jobApplication = JobApplication::with([
            'jobApplicant',
            'jobVacancie',
            'selectionApplicant' => function ($query) {
            $query->with(['selection', 'batchStage.batch'])->orderBy('created_at', 'asc');
        }
        ])->findOrFail($id);

        try {
            $applicant = $jobApplication->jobApplicant;
            $user = $applicant->user ?? null;
            $email = $user ? $user->email : $applicant->email;

            if ($email) {
                // Get all selection stages
                $stages = $jobApplication->selectionApplicant;

                $customMessage = "Detail Tahapan Seleksi:\n";
                if ($stages->count() > 0) {
                    foreach ($stages as $stage) {
                        $selectionDate = $stage->batchStage->batch->date ?? null;
                        $date = $selectionDate ?\Carbon\Carbon::parse($selectionDate)->translatedFormat('d F Y') : 'Jadwal Menyusul';

                        $stageStatusMap = [
                            'unprocess' => 'Belum Diproses',
                            'process' => 'Sedang Berlangsung',
                            'passed' => 'LULUS',
                            'failed' => 'TIDAK LULUS'
                        ];
                        $stageStatusLabel = $stageStatusMap[$stage->status] ?? ucfirst($stage->status);

                        $customMessage .= "• " . $stage->selection->name . " (Status: " . $stageStatusLabel . ")\n";
                        if (($stage->status == 'passed' || $stage->status == 'failed') && !empty($stage->notes) && $stage->notes != '-') {
                            $customMessage .= "  Catatan: " . $stage->notes . "\n";
                        }
                    }
                }

                $statusMap = [
                    'pending' => 'Menunggu Review',
                    'process' => 'Dalam Proses Seleksi',
                    'accepted' => 'DITERIMA',
                    'rejected' => 'TIDAK LOLOS',
                    'applied' => 'Lamaran Diterima',
                    'offering' => 'Tahap Offering'
                ];
                $statusLabel = $statusMap[$jobApplication->status] ?? ucfirst($jobApplication->status);

                Mail::to($email)->send(new ApplicationStatusMail($jobApplication, $statusLabel, $customMessage));

                return redirect()->back()->with('success', 'Email update berhasil dikirim ke pelamar.');
            }
            else {
                return redirect()->back()->with('error', 'Email pelamar tidak ditemukan.');
            }

        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send update email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobapplication = JobApplication::findOrFail($id);

        // Hapus data seleksi yang berelasi terlebih dahulu
        $jobapplication->selectionApplicant()->delete();

        // Baru hapus data lamarannya
        $jobapplication->delete();

        return redirect()->route('jobapplication.index')->with('success', 'Data lamaran berhasil dihapus');
    }




    /**
     * Helper: Hapus akun login pelamar setelah keputusan final (hired/rejected).
     * Data lamaran, profil pelamar, dan hasil seleksi TETAP disimpan sebagai arsip.
     * Hanya akun login (tabel users) yang dihapus agar pelamar tidak bisa login lagi.
     */
    private function cleanupFinalizedApplicant($jobApplication)
    {
        $applicant = $jobApplication->jobApplicant;
        if (!$applicant || !$applicant->user_id)
            return;

        // Cek apakah SEMUA lamaran pelamar ini sudah berstatus final (hired/rejected)
        $hasActiveApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
            ->whereNotIn('status', ['hired', 'rejected'])
            ->exists();

        // Jika masih ada lamaran yang belum final, jangan hapus dulu
        if ($hasActiveApplications)
            return;

        DB::beginTransaction();
        try {
            $userId = $applicant->user_id;
            $email = $applicant->email;

            // Lepas relasi user_id dari job_applicants agar tidak ikut terhapus cascade
            $applicant->update(['user_id' => null]);

            // Hapus akun login (users table) saja
            if ($userId) {
                User::where('user_id', $userId)->delete();
            }

            DB::commit();
            ActivityLog::log('Akun login pelamar ' . $email . ' dihapus otomatis setelah keputusan final. Data lamaran tetap tersimpan sebagai arsip.', 'System');
        }
        catch (\Exception $e) {
            DB::rollback();
            \Log::error('Gagal menghapus akun login pelamar: ' . $e->getMessage());
        }
    }
}
