<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SelectionApplicant;
use App\JobApplication;
use App\Selection;

class SelectionApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil data lamaran yang statusnya 'process' atau 'approved' (siap diseleksi)
        $jobApplications = JobApplication::with(['jobApplicant', 'jobVacancie', 'selectionApplicant.selection'])
                            ->whereIn('status', ['approved', 'process', 'accepted'])
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        return view('selectionapplicant.index', compact('jobApplications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $application_id = $request->query('application_id');
        $selectedApplication = null;
        
        if($application_id){
            $selectedApplication = JobApplication::find($application_id);
        }

        $jobApplications = JobApplication::whereIn('status', ['approved', 'process', 'accepted'])->get();
        $selections = Selection::all();
        return view('selectionapplicant.create', compact('jobApplications', 'selections', 'selectedApplication'));
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
            'selection_id' => 'required',
            'application_id' => 'required',
            'score' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'status' => 'required|in:passed,failed,pending',
        ]);

        SelectionApplicant::create([
            'selection_id' => $request->selection_id,
            'application_id' => $request->application_id,
            'score' => $request->score ?? 0,
            'notes' => $request->notes ?? '-',
            'status' => $request->status,
        ]);

        return redirect()->route('selectionapplicant.index')->with('success', 'Tahapan seleksi berhasil ditambahkan');
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
        $selectionApplicant = SelectionApplicant::findOrFail($id);
        $jobApplications = JobApplication::all();
        $selections = Selection::all();
        return view('selectionapplicant.edit', compact('selectionApplicant', 'jobApplications', 'selections'));
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
            'selection_id' => 'required',
            'application_id' => 'required',
            'score' => 'required',
            'notes' => 'required',
            'status' => 'required|in:passed,failed',
        ]);

        $selectionApplicant = SelectionApplicant::findOrFail($id);
        $selectionApplicant->update([
            'selection_id' => $request->selection_id,
            'application_id' => $request->application_id,
            'score' => $request->score,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('selectionapplicant.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $selectionApplicant = SelectionApplicant::findOrFail($id);
        $selectionApplicant->delete();
        return redirect()->route('selectionapplicant.index');
    }
}
