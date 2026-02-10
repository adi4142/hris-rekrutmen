{{-- 
    Dashboard Admin
    Menampilkan statistik keseluruhan sistem HRIS
    Variabel yang diterima dari AdminDashboardController:
    - $user, $totalApplicants, $totalActiveVacancies, $totalUsers, 
    - $totalApplications, $totalSelections, $totalInProcess, $totalAccepted, $totalRejected
--}}

@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@section('content')
<div class="row">
    {{-- Card Total User --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total User</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('user.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Total Pelamar --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalApplicants }}</h3>
                <p>Total Pelamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="{{ route('jobapplicant.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Lowongan Aktif --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalActiveVacancies }}</h3>
                <p>Lowongan Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('jobvacancie.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Proses Seleksi --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalSelections }}</h3>
                <p>Proses Seleksi</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
            <a href="{{ route('selection.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Row kedua: Status Lamaran --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalApplications }}</h3>
                <p>Total Lamaran Masuk</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('jobapplication.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-olive">
            <div class="inner">
                <h3>{{ $totalAccepted }}</h3>
                <p>Pelamar Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="{{ route('jobapplication.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-maroon">
            <div class="inner">
                <h3>{{ $totalRejected }}</h3>
                <p>Pelamar Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
            <a href="{{ route('jobapplication.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Informasi Selamat Datang --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i> Selamat Datang, {{ $user->name }}!
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="icon fas fa-info"></i> 
                    Anda login sebagai <strong>Administrator</strong>. 
                    Anda memiliki akses penuh ke seluruh fitur sistem HRIS.
                </div>
                <p>Gunakan menu di sebelah kiri untuk mengelola:</p>
                <ul>
                    <li><strong>Rekrutmen:</strong> Lowongan, Lamaran, Pelamar, Seleksi</li>
                    <li><strong>Pengaturan:</strong> User Management, Roles</li>
                    <li><strong>Master Data:</strong> Divisi, Departemen, Jabatan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
