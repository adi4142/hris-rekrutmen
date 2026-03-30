<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\JobVacancie;
use App\JobApplication;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;

class SuperAdminDashboardController extends Controller
{
    // ─────────────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────────────

    public function index()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_vacancies'    => JobVacancie::count(),
            'total_applicants'   => User::whereHas('role', function ($q) {
                $q->where('name', 'Pelamar')->orWhere('name', 'Tamu');
            })->count(),
            'total_applications' => JobApplication::count(),
        ];

        $applicationStats = JobApplication::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )->groupBy('month')->orderBy('month')->get();

        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.super_admin', compact('stats', 'applicationStats', 'recentLogs'));
    }

    // ─────────────────────────────────────────────────
    // Activity Logs
    // ─────────────────────────────────────────────────

    public function logs()
    {
        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.logs', compact('logs'));
    }
}
