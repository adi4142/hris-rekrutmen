@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')
@section('page_title', 'Log Aktivitas')

@section('content')
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
@endsection
