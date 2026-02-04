@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>150</h3>
                <p>Total Karyawan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('employee.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>95<sup style="font-size: 20px">%</sup></h3>
                <p>Kehadiran Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('attendance.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>12</h3>
                <p>Lowongan Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('jobvacancie.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>45</h3>
                <p>Pelamar Baru</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="{{ route('jobapplicant.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i> Statistik Kehadiran
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                   <i class="icon fas fa-info"></i> Selamat datang di Sistem HRIS! Anda dapat mengelola data SDM dengan mudah di sini.
                </div>
                <p>Gunakan menu di sebelah kiri untuk menavigasi ke berbagai modul sistem.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Aktivitas Terakhir
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Admin ditambahkan sebagai karyawan</li>
                    <li class="list-group-item">Lowongan Software Engineer dibuka</li>
                    <li class="list-group-item">5 Pelamar baru mendaftar hari ini</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
