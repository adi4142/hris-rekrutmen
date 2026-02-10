{{-- 
    Dashboard Karyawan
    Menampilkan statistik kehadiran pribadi karyawan
    Variabel yang diterima dari AttendanceController@employeeDashboard:
    - $user, $employee, $totalPresent, $totalPermission, $totalLate,
    - $todayAttendance, $recentAttendances
--}}

@extends('layouts.employee')

@section('title', 'Dashboard Karyawan')
@section('page_title', 'Dashboard Karyawan')

@section('content')
<div class="row">
    {{-- Card Absensi Hari Ini --}}
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-day"></i> Absensi Hari Ini
                </h3>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge badge-success">{{ $todayAttendance->status }}</span>
                            </p>
                            <p><strong>Jam Masuk:</strong> {{ $todayAttendance->time_in ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jam Keluar:</strong> {{ $todayAttendance->time_out ?? 'Belum absen keluar' }}</p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Anda belum absen hari ini.
                        <a href="{{ route('attendance.scan') }}" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-camera"></i> Absen Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Card Total Hadir --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalPresent }}</h3>
                <p>Hadir Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="small-box-footer">
                <i class="fas fa-calendar"></i> {{ now()->format('F Y') }}
            </span>
        </div>
    </div>

    {{-- Card Total Izin --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPermission }}</h3>
                <p>Izin Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <span class="small-box-footer">
                <i class="fas fa-calendar"></i> {{ now()->format('F Y') }}
            </span>
        </div>
    </div>

    {{-- Card Total Terlambat --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalLate }}</h3>
                <p>Terlambat Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <span class="small-box-footer">
                <i class="fas fa-calendar"></i> {{ now()->format('F Y') }}
            </span>
        </div>
    </div>
</div>

{{-- Riwayat Absensi Terbaru --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Riwayat Absensi Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                            <td>{{ $attendance->time_in ?? '-' }}</td>
                            <td>{{ $attendance->time_out ?? '-' }}</td>
                            <td>
                                @if($attendance->status == 'Present')
                                    <span class="badge badge-success">Hadir</span>
                                @elseif($attendance->status == 'Late')
                                    <span class="badge badge-warning">Terlambat</span>
                                @elseif($attendance->status == 'Permission')
                                    <span class="badge badge-info">Izin</span>
                                @else
                                    <span class="badge badge-secondary">{{ $attendance->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada riwayat absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Informasi Karyawan --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Informasi Karyawan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $employee->name ?? $user->name }}</p>
                        <p><strong>NIP:</strong> {{ $employee->nip }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Posisi:</strong> {{ $employee->position->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
