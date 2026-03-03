@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'Super Admin Dashboard')

@section('content')
<div class="row">

    {{-- Card Total Lowongan --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['total_vacancies'] }}</h3>
                <p>Total Lowongan</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="small-box-footer" style="padding: 3px 0;">Monitoring Only</div>
        </div>
    </div>

    {{-- Card Total Pelamar --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_applicants'] }}</h3>
                <p>Total Pelamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="small-box-footer" style="padding: 3px 0;">Monitoring Only</div>
        </div>
    </div>

    {{-- Card Total Lamaran --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_applications'] }}</h3>
                <p>Total Lamaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="small-box-footer" style="padding: 3px 0;">Monitoring Only</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Statistik Lamaran</h3>
            </div>
            <div class="card-body">
                <p class="text-center"></p>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Jumlah Lamaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applicationStats as $stat)
                            <tr>
                                <td>{{ date('F', mktime(0, 0, 0, $stat->month, 10)) }}</td>
                                <td>{{ $stat->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Aktivitas Terakhir</h3>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($recentLogs as $log)
                    <li class="item">
                        <div class="product-info ml-1">
                            <a href="javascript:void(0)" class="product-title">
                                {{ $log->user->name ?? 'System' }}
                                <span class="badge badge-info float-right">{{ $log->created_at->diffForHumans() }}</span>
                            </a>
                            <span class="product-description" style="white-space: normal;">
                                {{ $log->activity }}
                                <br>
                                <small class="text-muted"><i class="fas fa-tag mr-1"></i>{{ $log->module }}</small>
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="item text-center p-3">
                        <span class="text-muted">Belum ada aktivitas baru.</span>
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('superadmin.logs') }}" class="uppercase">Lihat Semua Log</a>
            </div>
        </div>
    </div>
</div>
@endsection
