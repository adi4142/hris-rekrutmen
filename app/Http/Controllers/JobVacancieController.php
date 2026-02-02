<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\Departement;
use App\Position;

class JobVacancieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobVacancies = JobVacancie::all();
        return view('jobvacancie.index', compact('jobVacancies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departements = Departement::all();
        $positions = Position::all();
        return view('jobvacancie.create', compact('departements', 'positions'));
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
            'title' => 'required',
            'departement_id' => 'required|exists:departements,departement_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'required',
            'requirements' => 'required',
            'status' => 'required|in:open,closed',
        ]);

        JobVacancie::create([
            'title' => $request->title,
            'departement_id' => $request->departement_id,
            'position_id' => $request->position_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status,
        ]);
        return redirect()->route('jobvacancie.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editJobVacancie = JobVacancie::findOrFail($id);
        $departement = Departement::all();
        $position = Position::all();
        return view('jobvacancie.edit', compact('editJobVacancie', 'departement', 'position'));
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
            'title' => 'required',
            'departement_id'=>'required|exists:departements,departement_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'required',
            'requirements' => 'required',
            'status' => 'required|in:open,closed',
        ]);

        $editJobVacancie = JobVacancie::findOrFail($id);
        $editJobVacancie->update([
            'title' => $request->title,
            'departement_id' => $request->departement_id,
            'position_id' => $request->position_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status,
        ]);
        return redirect()->route('jobvacancie.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteJobVacancie = JobVacancie::findOrFail($id);
        $deleteJobVacancie->delete();
        return redirect()->route('jobvacancie.index');
    }
}
