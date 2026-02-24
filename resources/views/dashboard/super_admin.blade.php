@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'Super Admin Dashboard')

@section('content')
<div class="row">
    {{-- Card Total User --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total User & Role</p>
            </div>
            <div class="icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="small-box-footer">
                Kelola User <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Total Lowongan --}}
    <div class="col-lg-3 col-6">
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
    <div class="col-lg-3 col-6">
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
    <div class="col-lg-3 col-6">
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
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs mr-1"></i> Shortcut Pengaturan</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('superadmin.settings') }}" class="btn btn-block btn-outline-warning mb-2">
                    <i class="fas fa-sliders-h mr-2"></i> Pengaturan Sistem
                </a>
                <a href="{{ route('superadmin.logs') }}" class="btn btn-block btn-outline-info">
                    <i class="fas fa-history mr-2"></i> Audit Logs
                </a>
            </div>
        </div>

        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-shield-alt mr-1"></i> Status Sistem</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="icon fas fa-check"></i> Sistem dalam keadaan <strong>Aktif</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
