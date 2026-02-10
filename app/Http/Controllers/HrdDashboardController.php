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

        // Query Eloquent untuk statistik HRD
        
        // Total pelamar yang sedang diproses (status pending)
        $totalInProcess = JobApplication::where('status', 'pending')->count();
        
        // Total pelamar yang diterima
        $totalAccepted = JobApplication::where('status', 'accepted')->count();
        
        // Total pelamar yang ditolak
        $totalRejected = JobApplication::where('status', 'rejected')->count();
        
        // Total lowongan aktif
        $totalActiveVacancies = JobVacancie::where('status', 'open')->count();
        
        // Total seluruh lowongan (aktif + tidak aktif)
        $totalVacancies = JobVacancie::count();
        
        // Total seluruh lamaran masuk
        $totalApplications = JobApplication::count();
        
        // Total pelamar terdaftar
        $totalApplicants = JobApplicant::count();
        
        // Data lamaran terbaru untuk ditampilkan (5 terakhir)
        $recentApplications = JobApplication::with(['jobApplicant', 'jobVacancie'])
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
