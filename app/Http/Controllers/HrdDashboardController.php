<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobApplicant;
use App\JobVacancie;
use App\JobApplication;
use App\SelectionApplicant;

/**
 * HrdDashboardController
 * 
 * Controller khusus untuk dashboard HRD
 * Menampilkan statistik terkait proses rekrutmen
 */
class HrdDashboardController extends Controller
{
    /**
     * Tampilkan dashboard HRD dengan statistik rekrutmen
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        $isAdmin = $user && $user->role && str_replace(' ', '', strtolower($user->role->name)) === 'admin';

        // Query Base untuk JobVacancie yang diassign ke HR ini
        $assignedVacanciesQuery = JobVacancie::query();
        if (!$isAdmin) {
            $assignedVacanciesQuery->whereHas('hrs', function($q) use ($user) {
                $q->where('job_vacancy_hr.user_id', $user->user_id);
            });
        }

        $assignedVacancyIds = $assignedVacanciesQuery->pluck('vacancies_id')->toArray();

        // Total pelamar yang sedang diproses (status pending atau process)
        $totalInProcess = JobApplication::whereIn('status', ['pending', 'applied', 'process'])
            ->whereIn('vacancies_id', $assignedVacancyIds)
            ->count();
        
        // Total pelamar yang diterima
        $totalAccepted = JobApplication::whereIn('status', ['accepted', 'hired'])
            ->whereIn('vacancies_id', $assignedVacancyIds)
            ->count();
        
        // Total pelamar yang ditolak
        $totalRejected = JobApplication::where('status', 'rejected')
            ->whereIn('vacancies_id', $assignedVacancyIds)
            ->count();
        
        // Total lowongan aktif (hanya yang diassign)
        $totalActiveVacancies = JobVacancie::where('status', 'open')
            ->whereIn('vacancies_id', $assignedVacancyIds)
            ->count();
        
        // Total seluruh lowongan (hanya yang diassign)
        $totalVacancies = count($assignedVacancyIds);
        
        // Total seluruh lamaran masuk (hanya yang diassign)
        $totalApplications = JobApplication::whereIn('vacancies_id', $assignedVacancyIds)->count();
        
        // Total pelamar terdaftar (yang melamar ke lowongan HR ini)
        $totalApplicants = JobApplication::whereIn('vacancies_id', $assignedVacancyIds)
            ->distinct('job_applicant_id')
            ->count();
        
        // Data lamaran terbaru untuk ditampilkan (5 terakhir dari lowongan yang diassign)
        $recentApplications = JobApplication::with(['jobApplicant', 'jobVacancie'])
            ->whereIn('vacancies_id', $assignedVacancyIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.hrd', compact(
            'user',
            'totalInProcess',
            'totalAccepted',
            'totalRejected',
            'totalActiveVacancies',
            'totalVacancies',
            'totalApplications',
            'totalApplicants',
            'recentApplications'
        ));
    }
}
