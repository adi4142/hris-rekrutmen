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
    public function create()
    {
        $jobVacancies = JobVacancie::all();
        return view('jobapplicant.create', compact('jobVacancies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'vacancies_id' => 'required|exists:job_vacancies,vacancies_id',
            'cv_file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $cv_file = null;

        if ($request->hasFile('cv_file')) {
            $cv_file = $request->file('cv_file')->store('cv_files', 'public');
        }

        $jobApplicant = JobApplicant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'cv_file' => $cv_file,
        ]);

        // Otomatis buat data di tabel job_applications
        JobApplication::create([
            'vacancies_id' => $request->vacancies_id,
            'job_applicant_id' => $jobApplicant->job_applicant_id,
            'status' => 'pending',
        ]);

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
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'cv_file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $jobApplicant = JobApplicant::findOrFail($id);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ];

        if ($request->hasFile('cv_file')) {
            // Delete old file if exists
            if ($jobApplicant->cv_file && \Storage::disk('public')->exists($jobApplicant->cv_file)) {
                \Storage::disk('public')->delete($jobApplicant->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('cv_files', 'public');
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
}
