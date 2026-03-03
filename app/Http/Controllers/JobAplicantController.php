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

class JobAplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobApplicants = JobApplicant::all();
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
        ];

        if (!$jobApplicant) {
            $validationRules['name'] = 'required';
            $validationRules['email'] = 'required|email|unique:job_applicants,email';
            $validationRules['phone'] = 'required';
            $validationRules['address'] = 'required';
            $validationRules['date_of_birth'] = 'required';
            $validationRules['gender'] = 'required|in:Male,Female';
        }

        $request->validate($validationRules);

        $data = $request->only(['name', 'email', 'phone', 'address', 'date_of_birth', 'gender']);
        
        // Map dynamic files to specific applicant fields if possible
        $fileFields = ['cv_file', 'cover_letter', 'portfolio', 'last_diploma', 'transcript', 'supporting_certificates', 'work_experience'];

        // Process dynamic required documents
        $uploadedDocs = [];
        if ($request->hasFile('required_files')) {
            foreach ($request->file('required_files') as $docName => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('applicant_documents', 'public');
                    $uploadedDocs[] = [
                        'name' => $docName,
                        'path' => $path,
                        'type' => 'required'
                    ];

                    // Try to map to profile fields
                    $mapping = [
                        'CV' => 'cv_file',
                        'Curriculum Vitae' => 'cv_file',
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
            $data['user_id'] = auth()->id();
            $jobApplicant = JobApplicant::create($data);
        } else {
            // Only update fields that are present in request
            $jobApplicant->update(array_filter($data));
        }

        // Create job application
        JobApplication::create([
            'vacancies_id' => $request->vacancies_id,
            'job_applicant_id' => $jobApplicant->job_applicant_id,
            'status' => 'pending',
            'documents' => $uploadedDocs,
        ]);

        if (auth()->check()) {
            if (auth()->user()->role && strtolower(auth()->user()->role->name) == 'tamu') {
                return redirect()->route('applicant.dashboard')->with('success', 'Lamaran berhasil dikirim!');
            }
            return redirect()->route('jobapplicant.index')->with('success', 'Lamaran berhasil dikirim!');
        }

        return redirect()->route('lowongan')->with('success', 'Lamaran berhasil dikirim! Kami akan menghubungi Anda melalui email jika Anda lolos tahap awal.');
    }

    public function createUserAccount(Request $request, $id)
    {
        $applicant = JobApplicant::findOrFail($id);

        if ($applicant->user_id) {
            return back()->with('error', 'Pendaftar ini sudah memiliki akun.');
        }

        // Generate password
        $password = Str::random(10);
        
        // Find Pelamar/Tamu role
        $role = Role::where('name', 'Pelamar')->orWhere('name', 'Tamu')->first();
        
        if (!$role) {
            return back()->with('error', 'Role Pelamar tidak ditemukan di sistem.');
        }

        $user = User::create([
            'name' => $applicant->name,
            'email' => $applicant->email,
            'password' => Hash::make($password),
            'roles_id' => $role->roles_id,
            'status' => 'active',
            'is_role_verified' => true
        ]);

        $applicant->update(['user_id' => $user->user_id]);

        // In a real app, you would send an email here
        // Mail::to($user->email)->send(new ApplicantAccountCreated($user, $password));

        return back()->with('success', 'Akun berhasil dibuat untuk ' . $applicant->name . '. Password: ' . $password . ' (Pastikan Anda menyalin password ini untuk diberikan ke pelamar jika sistem email belum aktif)');
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
            'gender' => 'required|in:Male,Female',
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
        $jobApplicant = JobApplicant::findOrFail($id);
        
        if ($jobApplicant->cv_file && \Storage::disk('public')->exists($jobApplicant->cv_file)) {
            \Storage::disk('public')->delete($jobApplicant->cv_file);
        }

        $jobApplicant->delete();
        return redirect()->route('jobapplicant.index');
    }

    public function totalApplicant()
    {
        $totalApplicant = JobApplicant::count();
        return response()->json(['totalApplicant' => $totalApplicant]);
    }
}
