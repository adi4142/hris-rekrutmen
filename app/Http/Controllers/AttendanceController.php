<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')->orderBy('date', 'desc')->orderBy('time_in', 'desc')->get();
        return view('attendance.index', compact('attendances'));
    }

    public function scan()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $employee = Employee::where('user_id', $user->user_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Akun anda tidak terhubung dengan data karyawan.');
        }

        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('employee_nip', $employee->nip)
            ->where('date', $today)
            ->first();

        return view('attendance.scan', compact('employee', 'attendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'nip' => 'required|exists:employees,nip'
        ]);

        $img = $request->image;
        $folderPath = "public/attendance/";
        
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $request->nip . '_' . time() . '.png';
        
        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $attendance = Attendance::where('employee_nip', $request->nip)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // Check-in
            Attendance::create([
                'employee_nip' => $request->nip,
                'date' => $today,
                'time_in' => $now,
                'photo_in' => $fileName,
                'status' => 'Present'
            ]);
            return response()->json(['success' => 'Berhasil Absen Masuk!']);
        } else {
            // Check-out
            if ($attendance->time_out) {
                return response()->json(['error' => 'Anda sudah absen keluar hari ini.']);
            }
            
            $attendance->update([
                'time_out' => $now,
                'photo_out' => $fileName
            ]);
            return response()->json(['success' => 'Berhasil Absen Keluar!']);
        }
    }

    /**
     * Dashboard untuk Karyawan
     * Menampilkan statistik kehadiran pribadi
     * 
     * @return \Illuminate\View\View
     */
    public function employeeDashboard()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->user_id)->first();
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Statistik kehadiran bulan ini
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // Total kehadiran bulan ini
        $totalPresent = Attendance::where('employee_nip', $employee->nip)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'Present')
            ->count();

        // Total izin bulan ini
        $totalPermission = Attendance::where('employee_nip', $employee->nip)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'Permission')
            ->count();

        // Total terlambat bulan ini
        $totalLate = Attendance::where('employee_nip', $employee->nip)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'Late')
            ->count();

        // Absensi hari ini
        $todayAttendance = Attendance::where('employee_nip', $employee->nip)
            ->where('date', $today->toDateString())
            ->first();

        // Riwayat absensi 7 hari terakhir
        $recentAttendances = Attendance::where('employee_nip', $employee->nip)
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('attendance.employee_dashboard', compact(
            'user',
            'employee',
            'totalPresent',
            'totalPermission',
            'totalLate',
            'todayAttendance',
            'recentAttendances'
        ));
    }
}
