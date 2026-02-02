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
}
