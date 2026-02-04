<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicantDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ensure user has an applicant profile
        if (!$user->tamu) {
            // handle case where user is logged in but has no applicant profile
            // maybe redirect to a profile completion page?
             return redirect()->route('jobapplicant.create')->with('warning', 'Silakan lengkapi data diri Anda terlebih dahulu.');
            // For now, let's assume we want to avoid error
        }

        $tamu = $user->tamu;

        // lamaran terbaru
        $latestApplication = $tamu->applications()
            ->with('jobVacancie')
            ->latest()
            ->first();

        // riwayat lamaran
        $applications = $tamu->applications()
            ->with('jobVacancie')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applicant.dashboard', compact(
            'tamu',
            'latestApplication',
            'applications'
        ));
    }

}
