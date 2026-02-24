{{-- 
    Dashboard HRD
    Menampilkan statistik rekrutmen untuk HRD
    Variabel yang diterima dari HrdDashboardController:
    - $user, $totalInProcess, $totalAccepted, $totalRejected, 
    - $totalActiveVacancies, $totalVacancies, $totalApplications, 
    - $totalApplicants, $recentApplications
--}}

@extends('layouts.admin')

@section('title', 'Dashboard HRD')
@section('page_title', 'Dashboard HRD')

@section('content')
<div class="row">
    {{-- Card Lamaran Pending --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalInProcess }}</h3>
                <p>Lamaran Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('jobapplication.index') }}" class="small-box-footer">
                Proses Sekarang <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Pelamar Diterima --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
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

    {{-- Card Pelamar Ditolak --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
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

    {{-- Card Lowongan Aktif --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalActiveVacancies }}</h3>
                <p>Lowongan Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('jobvacancie.index') }}" class="small-box-footer">
                Kelola Lowongan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Row kedua: Statistik Tambahan --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalApplications }}</h3>
                <p>Total Lamaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('jobapplication.index') }}" class="small-box-footer">
                Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $totalApplicants }}</h3>
                <p>Total Pelamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="{{ route('jobapplicant.index') }}" class="small-box-footer">
                Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $totalVacancies }}</h3>
                <p>Total Lowongan</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <a href="{{ route('jobvacancie.index') }}" class="small-box-footer">
                Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Tabel Lamaran Terbaru --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i> Lamaran Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelamar</th>
                            <th>Posisi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentApplications as $index => $application)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $application->jobApplicant->name ?? '-' }}</td>
                            <td>{{ $application->jobVacancie->title ?? '-' }}</td>
                            <td>
                                @if($application->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($application->status == 'accepted')
                                    <span class="badge badge-success">Diterima</span>
                                @elseif($application->status == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-info">Seleksi</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada lamaran masuk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('jobapplication.index') }}" class="btn btn-primary btn-sm">
                    Lihat Semua Lamaran <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Informasi Selamat Datang --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i> Selamat Datang, {{ $user->name }}!
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="icon fas fa-check"></i> 
                    Anda login sebagai <strong>HRD</strong>. 
                    Anda dapat mengelola proses rekrutmen dan seleksi pelamar.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
