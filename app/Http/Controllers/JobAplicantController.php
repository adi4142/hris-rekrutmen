<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobApplicant;
use App\JobVacancie;
use App\JobApplication;

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
        
        // If user already has a profile, we might want to pass it to the view to pre-fill
        $applicant = $user ? $user->tamu : null;
        
        return view('jobapplicant.create', compact('jobVacancies', 'selectedVacancyId', 'applicant'));
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

        $validationRules = [
            'vacancies_id' => 'required|exists:job_vacancies,vacancies_id',
            'cv_file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'cover_letter' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'portfolio' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'last_diploma' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'transcript' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'supporting_certificates' => 'nullable|mimes:pdf,zip,jpg,jpeg,png|max:10240',
            'work_experience' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ];

        // Reuse or create applicant profile
        $jobApplicant = $user ? $user->tamu : null;

        if (!$jobApplicant) {
            $validationRules['name'] = 'required';
            $validationRules['email'] = 'required|email|unique:job_applicants,email';
            $validationRules['phone'] = 'required';
            $validationRules['address'] = 'required';
            $validationRules['date_of_birth'] = 'required';
            $validationRules['gender'] = 'required|in:male,female';
        }

        $request->validate($validationRules);

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
                if ($jobApplicant && $jobApplicant->$field && \Storage::disk('public')->exists($jobApplicant->$field)) {
                    \Storage::disk('public')->delete($jobApplicant->$field);
                }
                $data[$field] = $request->file($field)->store('applicant_documents', 'public');
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
        ]);

        if (auth()->check() && auth()->user()->role && strtolower(auth()->user()->role->name) == 'tamu') {
            return redirect()->route('applicant.dashboard')->with('success', 'Lamaran berhasil dikirim!');
        }

        return redirect()->route('jobapplicant.index');
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
