@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')
@section('page_title', 'Log Aktivitas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Aktivitas Sistem</h3>
                <div class="card-tools">
                    <button class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Modul</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->user_name ?? 'System' }}</td>
                            <td>{{ $log->activity }}</td>
                            <td><span class="badge badge-primary">{{ $log->module }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada log aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
