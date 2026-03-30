<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\RecruitmentBatch;
use App\RecruitmentBatchStage;
use App\Selection;
use App\ActivityLog;
use DB;

class RecruitmentBatchController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $vacanciesQuery = JobVacancie::with(['batches' => function($q) {
                $q->withCount('applications')->with('stages.selection');
            }])
            ->withCount('jobApplications')
            ->orderBy('status', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter for HR
        if ($user && !$user->isSuperAdmin()) {
             $vacanciesQuery->whereHas('hrs', function($q) use ($user) {
                 $q->where('job_vacancy_hr.user_id', $user->user_id);
             });
        }

        $vacancies = $vacanciesQuery->get();
        $selections = Selection::all();
        return view('recruitment_batch.index', compact('vacancies', 'selections'));
    }

    public function create($vacancyId)
    {
        $vacancy = JobVacancie::findOrFail($vacancyId);
        $selections = Selection::all();
        return view('recruitment_batch.create', compact('vacancy', 'selections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vacancies_id' => 'required|exists:job_vacancies,vacancies_id',
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'status' => 'required|in:draft,active,closed',
            'stages' => 'required|array',
            'stages.*.selection_id' => 'required|exists:selection,selection_id',
            'stages.*.date' => 'required|date',
            'quota' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $stages = collect($request->stages)->sortBy(['date', 'start_time']);

            // Validate duplicate selection_id
            $duplicateStages = collect($request->stages)->duplicates('selection_id');
            if ($duplicateStages->isNotEmpty()) {
                $duplicateSelectionIds = $duplicateStages->unique();
                $selectionNames = Selection::whereIn('selection_id', $duplicateSelectionIds)->pluck('name')->toArray();
                throw new \Exception("Tahapan seleksi tidak boleh duplikat: " . implode(', ', $selectionNames));
            }

            // Auto-set batch date from first stage if not provided
            $batchDate = $request->date;
            if (!$batchDate && $stages->count() > 0) {
                $batchDate = $stages->min('date');
            }

            // Validate overlaps
            $lastDate = null;
            $lastEndTime = null;

            foreach ($stages as $stageData) {
                // Validasi agar jam selesai lebih besar dari jam mulai
                if (!empty($stageData['start_time']) && !empty($stageData['end_time'])) {
                    if ($stageData['start_time'] >= $stageData['end_time']) {
                        throw new \Exception("Waktu tidak valid: Jam selesai ({$stageData['end_time']}) pada tanggal {$stageData['date']} tidak boleh lebih awal atau sama dengan jam mulai ({$stageData['start_time']}).");
                    }
                }

                if ($stageData['date'] === $lastDate) {
                    if ($lastEndTime && $stageData['start_time'] < $lastEndTime) {
                        throw new \Exception("Jadwal tabrakan: Tahapan di tanggal " . $stageData['date'] . " mulai jam " . $stageData['start_time'] . " sebelum tahapan sebelumnya selesai (jam " . $lastEndTime . ")");
                    }
                }
                $lastDate = $stageData['date'];
                $lastEndTime = $stageData['end_time'];
            }

            $batch = RecruitmentBatch::create([
                'vacancies_id' => $request->vacancies_id,
                'name' => $request->name,
                'date' => $batchDate,
                'status' => $request->status ?? 'active',
                'quota' => $request->quota,
                'description' => $request->description,
            ]);

            foreach ($stages as $stageData) {
                RecruitmentBatchStage::create([
                    'batch_id' => $batch->id,
                    'selection_id' => $stageData['selection_id'],
                    'date' => $stageData['date'] ?? $request->date,
                    'start_time' => $stageData['start_time'] ?? null,
                    'end_time' => $stageData['end_time'] ?? null,
                    'location' => $stageData['location'] ?? null,
                    'description' => $stageData['description'] ?? null,
                    'room_url' => $stageData['room_url'] ?? null,
                ]);
            }

            DB::commit();
            ActivityLog::log('Membuat batch seleksi: ' . $batch->name, 'Batch Management');

            return redirect()->route('recruitment-batch.index')->with('success', 'Jadwal Batch berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat batch: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'status' => 'required|in:draft,active,closed',
            'stages' => 'required|array',
            'stages.*.selection_id' => 'required|exists:selection,selection_id',
            'stages.*.date' => 'required|date',
            'quota' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $batch = RecruitmentBatch::findOrFail($id);

        try {
            DB::beginTransaction();

            // Sort and Validate Overlaps
            $stages = collect($request->stages)->sortBy(['date', 'start_time']);
            
            // Validate duplicate selection_id
            $duplicateStages = collect($request->stages)->duplicates('selection_id');
            if ($duplicateStages->isNotEmpty()) {
                $duplicateSelectionIds = $duplicateStages->unique();
                $selectionNames = Selection::whereIn('selection_id', $duplicateSelectionIds)->pluck('name')->toArray();
                throw new \Exception("Tahapan seleksi tidak boleh duplikat: " . implode(', ', $selectionNames));
            }

            // Auto-set batch date from first stage if not provided
            $batchDate = $request->date;
            if (!$batchDate && $stages->count() > 0) {
                $batchDate = $stages->min('date');
            }

            $lastDate = null;
            $lastEndTime = null;

            foreach ($stages as $stageData) {
                // Validasi agar jam selesai lebih besar dari jam mulai
                if (!empty($stageData['start_time']) && !empty($stageData['end_time'])) {
                    if ($stageData['start_time'] >= $stageData['end_time']) {
                        throw new \Exception("Waktu tidak valid: Jam selesai ({$stageData['end_time']}) pada tanggal {$stageData['date']} tidak boleh lebih awal atau sama dengan jam mulai ({$stageData['start_time']}).");
                    }
                }

                if ($stageData['date'] === $lastDate) {
                    if ($lastEndTime && $stageData['start_time'] < $lastEndTime) {
                        throw new \Exception("Jadwal tabrakan: Tahapan di tanggal " . $stageData['date'] . " mulai jam " . $stageData['start_time'] . " sebelum tahapan sebelumnya selesai (jam " . $lastEndTime . ")");
                    }
                }
                $lastDate = $stageData['date'];
                $lastEndTime = $stageData['end_time'];
            }

            $batch->update([
                'name' => $request->name,
                'date' => $batchDate,
                'status' => $request->status,
                'quota' => $request->quota,
                'description' => $request->description,
            ]);

            $batch->stages()->delete();

            foreach ($stages as $stageData) {
                RecruitmentBatchStage::create([
                    'batch_id' => $batch->id,
                    'selection_id' => $stageData['selection_id'],
                    'date' => $stageData['date'] ?? $request->date,
                    'start_time' => $stageData['start_time'] ?? null,
                    'end_time' => $stageData['end_time'] ?? null,
                    'location' => $stageData['location'] ?? null,
                    'description' => $stageData['description'] ?? null,
                    'room_url' => $stageData['room_url'] ?? null,
                ]);
            }

            DB::commit();
            ActivityLog::log('Memperbarui batch seleksi: ' . $batch->name, 'Batch Management');

            return redirect()->route('recruitment-batch.index')->with('success', 'Jadwal Batch berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui batch: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $batch = RecruitmentBatch::findOrFail($id);
        $batch->delete();
        ActivityLog::log('Menghapus batch seleksi: ' . $batch->name, 'Batch Management');
        return redirect()->back()->with('success', 'Batch berhasil dihapus.');
    }
}
