<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\Departement;
use App\Position;

use App\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobVacancieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $query = JobVacancie::with(['departement', 'position', 'hrs']);
        
        // If HR (not Super Admin), only show assigned ones
        if ($user && !$user->isSuperAdmin()) {
             $query->whereHas('hrs', function($q) use ($user) {
                 $q->where('job_vacancy_hr.user_id', $user->user_id);
             });
        }

        $jobVacancies = $query->get();
        $departements = Departement::all();
        $positions = Position::all();
        $selections = \App\Selection::all();
        
        $hrUsers = \App\User::whereHas('role', function ($q) {
            $q->where('name', 'NOT LIKE', '%Pelamar%')
              ->where('name', 'NOT LIKE', '%Tamu%');
        })->get();

        JobVacancie::whereDate('expired_at', '<=', Carbon::today())
            ->where('status', 'open')
            ->update(['status' => 'closed']);
        return view('jobvacancie.index', compact('jobVacancies', 'departements', 'positions', 'selections', 'hrUsers'));
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
        $selections = \App\Selection::all();
        
        $hrUsers = \App\User::whereHas('role', function ($q) {
            $q->where('name', 'NOT LIKE', '%Pelamar%')
              ->where('name', 'NOT LIKE', '%Tamu%');
        })->get();

        return view('jobvacancie.create', compact('departements', 'positions', 'selections', 'hrUsers'));
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
            'description' => 'required', // Migration says not null, though 02_09 makes it nullable
            'expired_at' => 'required|date',
            'requirements' => 'nullable|array',
            'required_documents' => 'nullable|array',
            'job_type' => 'required|in:full time,part time,contract',
            'salary_type' => 'required|in:daily,weekly,monthly,negotiate',
            'salary_nominal' => 'required_unless:salary_type,negotiate|nullable',
            'quota' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $position = Position::findOrFail($request->position_id);

            // Clean salary nominal
            $salaryNominal = $request->salary_nominal;
            if ($salaryNominal) {
                $salaryNominal = (float) str_replace('.', '', $salaryNominal);
            }

            $job = JobVacancie::create([
                'title' => $position->name,
                'departement_id' => $request->departement_id,
                'position_id' => $request->position_id,
                'description' => $request->description,
                'expired_at' => $request->expired_at,
                'requirements' => $request->requirements ? array_values(array_filter($request->requirements)) : [],
                'required_documents' => $request->required_documents ? $request->required_documents : [],
                'job_type' => $request->job_type,
                'salary_type' => $request->salary_type,
                'salary_nominal' => $request->salary_type === 'negotiate' ? null : $salaryNominal,
                'quota' => $request->quota,
                'status' => 'open',
            ]);

            if ($request->has('hr_ids')) {
                $hrIds = array_filter($request->hr_ids);
                if (!empty($hrIds)) {
                    $job->hrs()->sync($hrIds);
                }
            }

            DB::commit();
            ActivityLog::log('Membuat lowongan baru: ' . $position->name, 'Lowongan Kerja');
            return redirect()->route('jobvacancie.index')->with('success', 'Lowongan kerja berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan lowongan: ' . $e->getMessage());
        }
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
        $editJobVacancie = JobVacancie::with('hrs')->findOrFail($id);
        $departements = Departement::all();
        $positions = Position::all();
        $selections = \App\Selection::all();

        $hrUsers = \App\User::whereHas('role', function ($q) {
            $q->where('name', 'NOT LIKE', '%Pelamar%')
              ->where('name', 'NOT LIKE', '%Tamu%');
        })->get();

        return view('jobvacancie.edit', compact('editJobVacancie', 'departements', 'positions', 'selections', 'hrUsers'));
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
            'description' => 'required',
            'expired_at' => 'required|date',
            'requirements' => 'nullable|array',
            'required_documents' => 'nullable|array',
            'job_type' => 'required|in:full time,part time,contract',
            'salary_type' => 'required|in:daily,weekly,monthly,negotiate',
            'salary_nominal' => 'required_unless:salary_type,negotiate|nullable',
            'quota' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $editJobVacancie = JobVacancie::findOrFail($id);
            $position = Position::findOrFail($request->position_id);
            
            // Clean salary nominal
            $salaryNominal = $request->salary_nominal;
            if ($salaryNominal) {
                $salaryNominal = (float) str_replace('.', '', $salaryNominal);
            }

            $editJobVacancie->update([
                'title' => $position->name,
                'departement_id' => $request->departement_id,
                'position_id' => $request->position_id,
                'description' => $request->description,
                'expired_at' => $request->expired_at,
                'requirements' => $request->requirements ? array_values(array_filter($request->requirements)) : [],
                'required_documents' => $request->required_documents ? $request->required_documents : [],
                'job_type' => $request->job_type,
                'salary_type' => $request->salary_type,
                'salary_nominal' => $request->salary_type === 'negotiate' ? null : $salaryNominal,
                'quota' => $request->quota,
            ]);

            if ($request->has('hr_ids')) {
                $hrIds = array_filter($request->hr_ids);
                if (!empty($hrIds)) {
                    $editJobVacancie->hrs()->sync($hrIds);
                } else {
                    $editJobVacancie->hrs()->detach();
                }
            }

            DB::commit();
            ActivityLog::log('Memperbarui lowongan: ' . $position->name, 'Lowongan Kerja');
            return redirect()->route('jobvacancie.index')->with('success', 'Lowongan kerja berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui lowongan: ' . $e->getMessage());
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
