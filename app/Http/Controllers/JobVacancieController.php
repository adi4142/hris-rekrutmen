<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\Departement;
use App\Position;
use App\ActivityLog;
use Carbon\Carbon;

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
        $departements = Departement::all();
        $positions = Position::all();

        JobVacancie::whereDate('expired_at', '<=', Carbon::today())
            ->where('status', 'open')
            ->update(['status' => 'closed']);
        return view('jobvacancie.index', compact('jobVacancies', 'departements', 'positions'));
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
            'departement_id' => 'required|exists:departements,departement_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'nullable',
            'expired_at' => 'nullable|date',
            'requirements' => 'nullable|array',
            'required_documents' => 'nullable|array',
        ]);

        $position = Position::findOrFail($request->position_id);

        JobVacancie::create([
            'title' => $position->name,
            'departement_id' => $request->departement_id,
            'position_id' => $request->position_id,
            'description' => $request->description,
            'expired_at' => $request->expired_at,
            'requirements' => $request->requirements ? json_encode(array_values(array_filter($request->requirements))) : null,
            'required_documents' => $request->required_documents ? json_encode($request->required_documents) : null,
            'status' => 'closed',
        ]);

        ActivityLog::log('Membuat lowongan baru: ' . $position->name, 'Lowongan Kerja');

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
        $departements = Departement::all();
        $positions = Position::all();
        return view('jobvacancie.edit', compact('editJobVacancie', 'departements', 'positions'));
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
            'departement_id'=>'required|exists:departements,departement_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'nullable',
            'expired_at' => 'nullable|date',
            'requirements' => 'nullable|array',
            'required_documents' => 'nullable|array',
        ]);

        $editJobVacancie = JobVacancie::findOrFail($id);
        $position = Position::findOrFail($request->position_id);
        
        $editJobVacancie->update([
            'title' => $position->name,
            'departement_id' => $request->departement_id,
            'position_id' => $request->position_id,
            'description' => $request->description,
            'expired_at' => $request->expired_at,
            'requirements' => $request->requirements ? json_encode(array_values(array_filter($request->requirements))) : null,
            'required_documents' => $request->required_documents ? json_encode($request->required_documents) : null,
        ]);

        ActivityLog::log('Memperbarui lowongan: ' . $position->name, 'Lowongan Kerja');

        return redirect()->route('jobvacancie.index')->with('success', 'Lowongan kerja berhasil diperbarui.');
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
        $title = $deleteJobVacancie->title;
        $deleteJobVacancie->delete();

        ActivityLog::log('Menghapus lowongan: ' . $title, 'Lowongan Kerja');

        return redirect()->route('jobvacancie.index');
    }

    public function toggleStatus($id)
    {
        $jobVacancie = JobVacancie::findOrFail($id);
        $jobVacancie->status = $jobVacancie->status === 'open' ? 'closed' : 'open';
        $jobVacancie->save();

        ActivityLog::log('Mengubah status lowongan ' . $jobVacancie->title . ' menjadi ' . $jobVacancie->status, 'Lowongan Kerja');

        return response()->json([
            'success' => true,
            'status' => $jobVacancie->status,
            'message' => 'Status berhasil diubah menjadi ' . $jobVacancie->status
        ]);
    }
}
