<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobApplicant;
use App\JobVacancie;
use App\JobApplication;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantAccountCreatedMail;


class JobAplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = JobApplicant::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $jobApplicants = $query->paginate(50);
        return view('jobapplicant.index', compact('jobApplicants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Check if user already has an active application
        if ($user && $user->tamu) {
            $activeApplication = $user->tamu->applications()
                ->whereNotIn('status', ['accepted', 'rejected'])
                ->exists();
            
            if ($activeApplication) {
                return redirect()->route('applicant.dashboard')->with('error', 'Anda masih dalam proses seleksi. Selesaikan proses tersebut sebelum melamar lowongan lain.');
            }
        }

        $jobVacancies = JobVacancie::all();
        $selectedVacancyId = $request->query('vacancies_id');
        $vacancy = $selectedVacancyId ? JobVacancie::find($selectedVacancyId) : null;
        
        // If user already has a profile, we might want to pass it to the view to pre-fill
        $applicant = $user ? $user->tamu : null;
        
        return view('jobapplicant.create', compact('jobVacancies', 'selectedVacancyId', 'applicant', 'vacancy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Double check for active application
        if ($user && $user->tamu) {
            $activeApplication = $user->tamu->applications()
                ->whereNotIn('status', ['accepted', 'rejected'])
                ->exists();
            
            if ($activeApplication) {
                return redirect()->route('applicant.dashboard')->with('error', 'Anda masih dalam proses seleksi.');
            }

        }

        // Reuse or create applicant profile
        $jobApplicant = $user ? $user->tamu : null;

        $validationRules = [
            'vacancies_id' => 'required|exists:job_vacancies,vacancies_id',
            'required_files.*' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'cv_file' => 'required|mimes:pdf,doc,docx|max:3072',
        ];

        if (!$jobApplicant) {
            $validationRules['name'] = 'required';
            $validationRules['email'] = 'required|email'; // Unique removed to allow re-apply
            $validationRules['phone'] = 'required';
            $validationRules['address'] = 'required';
            $validationRules['date_of_birth'] = 'required';
            $validationRules['gender'] = 'required|in:male,female';
        }

        $request->validate($validationRules);

        // Manual check for active applications if email exists
        if (!$jobApplicant) {
            $existing = JobApplicant::where('email', $request->email)->first();
            if ($existing) {
                $hasActive = \App\JobApplication::where('job_applicant_id', $existing->job_applicant_id)
                    ->whereNotIn('status', ['accepted', 'rejected', 'hired'])
                    ->exists();
                if ($hasActive) {
                    return back()->with('error', 'Email Anda sudah terdaftar dengan lamaran yang sedang aktif. Silakan tunggu hingga proses seleksi selesai atau gunakan email lain.');
                }
                $jobApplicant = $existing;
            }
        }

        $data = $request->only(['name', 'email', 'phone', 'address', 'date_of_birth', 'gender']);
        $data['cv_file'] = '-'; // Default placeholder as it's now handled per-vacancy
        
        // Process dynamic required documents
        $uploadedDocs = [];

        // 0. Simpan CV (Wajib di setiap lamaran sekarang)
        if ($request->hasFile('cv_file')) {
            $path = $request->file('cv_file')->store('application_cvs', 'public');
            $uploadedDocs[] = [
                'name' => 'Curriculum Vitae (CV)',
                'path' => $path,
                'type' => 'cv'
            ];
        }

        if ($request->hasFile('required_files')) {
            foreach ($request->file('required_files') as $docName => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('applicant_documents', 'public');
                    $uploadedDocs[] = [
                        'name' => $docName,
                        'path' => $path,
                        'type' => 'required'
                    ];

                    // Map only non-CV files to specific applicant fields if they exist in mapping
                    $mapping = [
                        'Foto' => 'photo',
                        'Ijazah' => 'last_diploma',
                        'Transkrip' => 'transcript',
                        'Sertifikat' => 'supporting_certificates',
                        'Portofolio' => 'portfolio',
                        'Surat Lamaran' => 'cover_letter'
                    ];

                    foreach ($mapping as $key => $field) {
                        if (stripos($docName, $key) !== false || stripos($key, $docName) !== false) {
                            $data[$field] = $path;
                        }
                    }
                }
            }
        }

        if (!$jobApplicant) {
            // Cek apakah email sudah ada di JobApplicant (mungkin pernah melamar tapi tidak punya akun)
            $jobApplicant = JobApplicant::where('email', $data['email'])->first();
            
            if (!$jobApplicant) {
                // Pelamar Benar-benar Baru -> Cukup buat data pelamar saja, tanpa User account
                $jobApplicant = JobApplicant::create($data);
                \Illuminate\Support\Facades\Log::info('Berhasil membuat profil pelamar baru tanpa akun user: ' . $data['email']);
            } else {
                // Update data lama jika email cocok
                $jobApplicant->update(array_filter($data));
            }
        } else {
            // Pelamar sedang login (mungkin Admin mendaftarkan pelamar atau pelamar lama yang masih punya akun)
            $jobApplicant->update(array_filter($data));
        }

        // Create job application
        $application = JobApplication::create([
            'vacancies_id' => $request->vacancies_id,
            'job_applicant_id' => $jobApplicant->job_applicant_id,
            'status' => 'applied',
            'documents' => $uploadedDocs,
        ]);

        // Email konfirmasi lamaran dinonaktifkan atas permintaan user.


        if (auth()->check()) {
            return redirect()->route('lowongan')->with('success', 'Lamaran berhasil dikirim! Silakan memantau email Anda untuk mengetahui progress lamaran Anda.');
        }

        return redirect()->route('lowongan')->with('success', 'Lamaran berhasil dikirim! Silakan memantau email Anda untuk mengetahui progress lamaran Anda.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editjobApplicant = JobApplicant::findOrFail($id);
        return view('jobapplicant.edit', compact('editjobApplicant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:job_applicants,email,'.$id.',job_applicant_id',
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required|in:male,female',
            'cv_file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'cover_letter' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'portfolio' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'last_diploma' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'transcript' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'supporting_certificates' => 'nullable|mimes:pdf,zip,jpg,jpeg,png|max:10240',
            'work_experience' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $jobApplicant = JobApplicant::findOrFail($id);
        
        $data = $request->only(['name', 'email', 'phone', 'address', 'date_of_birth', 'gender']);

        $fileFields = [
            'cv_file', 
            'cover_letter', 
            'portfolio', 
            'last_diploma', 
            'transcript', 
            'supporting_certificates', 
            'work_experience'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($jobApplicant->$field && \Storage::disk('public')->exists($jobApplicant->$field)) {
                    \Storage::disk('public')->delete($jobApplicant->$field);
                }
                $data[$field] = $request->file($field)->store('applicant_documents', 'public');
            }
        }

        $jobApplicant->update($data);
        return redirect()->route('jobapplicant.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobApplicant = JobApplicant::with('applications')->findOrFail($id);
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Hapus hasil seleksi untuk semua lamaran pelamar ini
            foreach ($jobApplicant->applications as $app) {
                \App\SelectionApplicant::where('application_id', $app->application_id)->delete();
                $app->delete();
            }

            // 2. Hapus file-file dokumen pelamar
            $fileFields = ['cv_file', 'cover_letter', 'portfolio', 'last_diploma', 'transcript', 'supporting_certificates', 'work_experience', 'photo'];
            foreach ($fileFields as $field) {
                if ($jobApplicant->$field && \Illuminate\Support\Facades\Storage::disk('public')->exists($jobApplicant->$field)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($jobApplicant->$field);
                }
            }

            // 3. Simpan user_id sebelum data pelamar dihapus
            $userId = $jobApplicant->user_id;

            // 4. Hapus data pelamar
            $jobApplicant->delete();

            // 5. Hapus akun User jika ini adalah akun pelamar
            if ($userId) {
                User::where('user_id', $userId)->delete();
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('jobapplicant.index')->with('success', 'Data pelamar, semua riwayat lamaran, dan akun user berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->route('jobapplicant.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function totalApplicant()
    {
        $totalApplicant = JobApplicant::count();
        return response()->json(['totalApplicant' => $totalApplicant]);
    }

    /**
     * Get applicant profile detail for AJAX
     */
    public function getProfile($id)
    {
        $applicant = JobApplicant::findOrFail($id);
        
        // Format data to be sent
        $data = [
            'name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => $applicant->phone ?: '-',
            'address' => $applicant->address ?: '-',
            'date_of_birth' => $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->format('d F Y') : '-',
            'gender' => $applicant->gender == 'male' ? 'Laki-laki' : ($applicant->gender == 'female' ? 'Perempuan' : '-'),
            'photo' => $applicant->photo ? asset('storage/' . $applicant->photo) : asset('img/user2-160x160.jpg')
        ];

        return response()->json($data);
    }

    /**
     * Get applicant documents for AJAX
     */
    public function getDocuments($id)
    {
        $applicant = JobApplicant::findOrFail($id);
        
        $docsData = [
            ["title" => "CV", "url" => $applicant->cv_file && $applicant->cv_file != '-' ? asset("storage/" . $applicant->cv_file) : null, "icon" => "fa-file-pdf"],
            ["title" => "Surat Lamaran", "url" => $applicant->cover_letter ? asset("storage/" . $applicant->cover_letter) : null, "icon" => "fa-envelope-open-text"],
            ["title" => "Portofolio", "url" => $applicant->portfolio ? asset("storage/" . $applicant->portfolio) : null, "icon" => "fa-briefcase"],
            ["title" => "Ijazah", "url" => $applicant->last_diploma ? asset("storage/" . $applicant->last_diploma) : null, "icon" => "fa-graduation-cap"],
            ["title" => "Transkrip", "url" => $applicant->transcript ? asset("storage/" . $applicant->transcript) : null, "icon" => "fa-file-invoice"],
            ["title" => "Sertifikat", "url" => $applicant->supporting_certificates ? asset("storage/" . $applicant->supporting_certificates) : null, "icon" => "fa-certificate"],
            ["title" => "Pengalaman Kerja", "url" => $applicant->work_experience ? asset("storage/" . $applicant->work_experience) : null, "icon" => "fa-user-tie"]
        ];

        return response()->json($docsData);
    }

    public function cleanupRejected()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('applicants:delete-rejected');
            $output = \Illuminate\Support\Facades\Artisan::output();
            return back()->with('success', 'Proses pembersihan selesai. ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menjalankan pembersihan: ' . $e->getMessage());
        }
    }
}
