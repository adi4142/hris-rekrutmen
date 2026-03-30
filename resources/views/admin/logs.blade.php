@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page_title', 'Audit Log')
@section('page_subtitle', 'Riwayat aktivitas seluruh user di sistem')

@section('content')

<div class="card">
                <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-history mr-2 text-primary"></i> Audit Log</h3>
                    </div>
                    <div class="col-md-6 text-right d-flex justify-content-end align-items-center" style="gap:8px">
                        <form action="" method="GET">
                            <div class="input-group input-group-sm" style="width: 220px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    @if(request('search'))
                                    <a href="" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                    @endif
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Module</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-size:12.5px;color:var(--tx-muted)">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                        <td style="font-weight:500;color:var(--tx-primary)">{{ $log->user_name ?? 'System' }}</td>
                        <td>{{ $log->activity }}</td>
                        <td><span class="badge badge-info">{{ $log->module }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px 0;color:var(--tx-muted);font-size:13px">
                            <i class="fas fa-history mb-2 d-block" style="font-size:20px;opacity:.3"></i>
                            Belum ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $logs->links() }}
    </div>
</div>

@endsection
