<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\JobApplicant;
use App\JobVacancie;
use App\JobApplication;
use App\SelectionApplicant;
use App\Selection;

/**
 * AdminDashboardController
 * 
 * Controller khusus untuk dashboard ADMIN
 * Menampilkan statistik keseluruhan sistem HRIS
 */
class AdminDashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik lengkap
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user yang sedang login menggunakan auth() helper (lebih aman)
        $user = auth()->user();

        // Query Eloquent untuk menghitung data dari database
        // Menggunakan count() langsung untuk performa optimal
        
        // Total seluruh pelamar (job applicants)
        $totalApplicants = JobApplicant::count();
        
        // Total lowongan aktif (status = 'open')
        $totalActiveVacancies = JobVacancie::where('status', 'open')->count();
        
        // Total semua user di sistem
        $totalUsers = User::count();
        
        // Total lamaran masuk (job applications)
        $totalApplications = JobApplication::count();
        
        // Total proses seleksi yang ada
        $totalSelections = Selection::count();
        
        // Total pelamar yang sedang dalam proses seleksi
        $totalInProcess = JobApplication::where('status', 'pending')->count();
        
        // Total pelamar diterima
        $totalAccepted = JobApplication::where('status', 'accepted')->count();
        
        // Total pelamar ditolak
        $totalRejected = JobApplication::where('status', 'rejected')->count();

        // Kirim variabel ke view dengan nama yang SAMA PERSIS
        return view('dashboard.admin', compact(
            'user',
            'totalApplicants',
            'totalActiveVacancies',
            'totalUsers',
            'totalApplications',
            'totalSelections',
            'totalInProcess',
            'totalAccepted',
            'totalRejected'
        ));
    }
}
