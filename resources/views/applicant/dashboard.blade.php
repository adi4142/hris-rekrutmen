@extends('layouts.app')

@section('page_title', 'Dashboard Pelamar')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="callout callout-info">
            <h5><i class="fas fa-user"></i> Selamat datang, {{ Auth::user()->name }} 👋</h5>
            <p>Pantau status lamaran pekerjaanmu dan cek riwayat seleksi di sini.</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Status Lamaran Terakhir -->
    <div class="col-md-12">
        @if($latestApplication)
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Lamaran Terakhir
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="info-box shadow-none bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-briefcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Posisi Dilamar</span>
                                    <span class="info-box-number">{{ $latestApplication->jobVacancie->title }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="info-box shadow-none bg-light">
                                <span class="info-box-icon bg-info"><i class="far fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tanggal Melamar</span>
                                    <span class="info-box-number">{{ $latestApplication->created_at->format('d F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <h5 class="text-muted mb-2">Status Saat Ini</h5>
                        <span class="badge 
                            @if($latestApplication->status == 'sent') badge-secondary
                            @elseif($latestApplication->status == 'hrd') badge-info
                            @elseif($latestApplication->status == 'interview') badge-warning
                            @elseif($latestApplication->status == 'approved') badge-success
                            @else badge-danger
                            @endif
                            p-2" style="font-size: 1.2rem; width: 200px;">
                            {{ strtoupper($latestApplication->status) }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Info!</h5>
                Kamu belum melamar pekerjaan apa pun. Silakan cek lowongan yang tersedia.
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Riwayat Lamaran
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                        <th>Posisi</th>
                        <th>Tanggal Lamar</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <i class="fas fa-briefcase text-muted mr-2"></i>
                            {{ $app->jobVacancie->title }}
                        </td>
                        <td>
                            {{ $app->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge 
                                @if($app->status == 'sent') bg-secondary
                                @elseif($app->status == 'hrd') bg-info
                                @elseif($app->status == 'interview') bg-warning
                                @elseif($app->status == 'accepted') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center p-4 text-muted">Belum ada data lamaran.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
