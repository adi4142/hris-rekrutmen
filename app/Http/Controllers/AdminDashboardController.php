<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\JobApplicant;
use App\JobVacancie;
use App\JobApplication;
use App\Selection;
use App\ActivityLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalApplicants      = JobApplicant::count();
        $totalActiveVacancies = JobVacancie::where('status', 'open')->count();
        $totalUsers           = User::count();
        $totalApplications    = JobApplication::count();
        $totalSelections      = Selection::count();
        $totalInProcess       = JobApplication::whereIn('status', ['pending', 'applied', 'process'])->count();
        $totalAccepted        = JobApplication::whereIn('status', ['accepted', 'hired'])->count();
        $totalRejected        = JobApplication::where('status', 'rejected')->count();

        // Stats untuk dashboard admin (sama seperti superadmin dulu)
        $stats = [
            'total_users'        => $totalUsers,
            'total_vacancies'    => JobVacancie::count(),
            'total_applicants'   => $totalApplicants,
            'total_applications' => $totalApplications,
        ];

        $applicationStats = JobApplication::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )->groupBy('month')->orderBy('month')->get();

        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.admin', compact(
            'user',
            'stats',
            'applicationStats',
            'recentLogs',
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

    public function logs()
    {
        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.logs', compact('logs'));
    }
}
