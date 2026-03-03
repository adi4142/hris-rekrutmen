<?php

namespace App\Http\Controllers;
use App\JobApplication;
use App\JobApplicant;
use App\JobVacancie;
use Illuminate\Http\Request;

use App\Selection;
use App\SelectionApplicant;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource (Grouped by Applicant).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil data applicant yang memiliki lamaran, beserta jumlah lamarannya
        $applicants = JobApplicant::has('applications')
            ->withCount('applications')
            ->with(['applications.jobVacancie']) // Eager load untuk detail jika diperlukan
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('jobapplication.index', compact('applicants'));
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
            'selectionApplicant' => function($query) {
                $query->with('selection')->orderBy('selection_date', 'asc');
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
            'selection_date' => 'nullable|date|after_or_equal:today',
        ]);

        $jobApp = JobApplication::with('jobApplicant', 'jobVacancie.position')->findOrFail($id);
        $selection = Selection::findOrFail($request->selection_id);

        SelectionApplicant::create([
            'selection_id' => $request->selection_id,
            'application_id' => $id,
            'selection_date' => $request->selection_date,
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
            'selection_date' => 'nullable|date|after_or_equal:today',
        ]);

        // Cek jika status ingin diubah ke 'process' (Mulai Proses)
        if ($request->status == 'process' && $selection->status == 'unprocess') {
            $date = $request->selection_date ?? $selection->selection_date;
            if ($date && \Carbon\Carbon::parse($date)->isFuture()) {
                return redirect()->back()->with('error', 'Proses seleksi belum bisa dimulai sebelum tanggal yang dijadwalkan (' . \Carbon\Carbon::parse($date)->format('d-m-Y') . ').');
            }
        }

        $selection->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $selection->notes,
            'score' => $request->score ?? $selection->score,
            'selection_date' => $request->selection_date ?? $selection->selection_date,
        ]);

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
            'selectionApplicant' => function($query) {
                $query->with('selection')->orderBy('selection_date', 'asc');
            }
        ])->findOrFail($id);
        
        try {
            $applicant = $jobApplication->jobApplicant;
            $user = $applicant->user ?? null;
            $email = $user ? $user->email : $applicant->email;

            if ($email) {
                // Get all selection stages
                $stages = $jobApplication->selectionApplicant;
                
                // Format email content
                $emailSubject = 'Update Informasi Lamaran - ' . $jobApplication->jobVacancie->title;
                $emailContent = "Halo {$applicant->name},\n\n";
                $emailContent .= "Berikut adalah informasi terbaru mengenai status lamaran Anda untuk posisi {$jobApplication->jobVacancie->title}.\n\n";
                
                // Status Lamaran Global
                $statusMap = [
                    'pending' => 'Menunggu Review',
                    'process' => 'Dalam Proses Seleksi',
                    'accepted' => 'DITERIMA',
                    'rejected' => 'TIDAK LOLOS',
                    'applied' => 'Lamaran Diterima'
                ];
                $statusLabel = $statusMap[$jobApplication->status] ?? ucfirst($jobApplication->status);
                $emailContent .= "Status Lamaran: **{$statusLabel}**\n\n";
                
                // Detail Tahapan Seleksi
                if ($stages->count() > 0) {
                    $emailContent .= "Detail Tahapan Seleksi:\n";
                    $emailContent .= "--------------------------------------------------\n";
                    
                    foreach ($stages as $stage) {
                        $date = $stage->selection_date ? \Carbon\Carbon::parse($stage->selection_date)->translatedFormat('d F Y') : 'Jadwal Menyusul';
                        
                        $stageStatusMap = [
                            'unprocess' => 'Belum Diproses',
                            'process' => 'Sedang Berlangsung',
                            'passed' => 'LULUS',
                            'failed' => 'TIDAK LULUS'
                        ];
                        $stageStatusLabel = $stageStatusMap[$stage->status] ?? ucfirst($stage->status);

                        $emailContent .= "• " . $stage->selection->name . "\n";
                        $emailContent .= "  Tanggal: " . $date . "\n";
                        $emailContent .= "  Status: " . $stageStatusLabel . "\n";
                        
                        // Show notes if final
                        if (($stage->status == 'passed' || $stage->status == 'failed') && !empty($stage->notes) && $stage->notes != '-') {
                            $emailContent .= "  Catatan: " . $stage->notes . "\n";
                        }
                        $emailContent .= "\n";
                    }
                    $emailContent .= "--------------------------------------------------\n\n";
                }

                $emailContent .= "Silakan cek website untuk informasi lebih lengkap.\n\n";
                $emailContent .= "Salam,\nTim HRD";

                // Send Email
                \Illuminate\Support\Facades\Mail::raw($emailContent, function ($message) use ($email, $emailSubject) {
                    $message->to($email)
                            ->subject($emailSubject);
                });

                return redirect()->back()->with('success', 'Email update berhasil dikirim ke pelamar.');
            } else {
                return redirect()->back()->with('error', 'Email pelamar tidak ditemukan.');
            }

        } catch (\Exception $e) {
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
}
