@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Absensi</h5>
                    <a href="{{ route('attendance.scan') }}" class="btn btn-primary btn-sm">Absen Sekarang</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>NIP</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Foto Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $att)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $att->employee->name }}</td>
                                    <td>{{ $att->employee->nip }}</td>
                                    <td>{{ Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                    <td>{{ $att->time_in ? Carbon\Carbon::parse($att->time_in)->format('H:i') : '-' }}</td>
                                    <td>
                                        @if($att->photo_in)
                                            <img src="{{ Storage::url('attendance/' . $att->photo_in) }}" width="50" class="img-thumbnail" onclick="window.open(this.src)">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $att->time_out ? Carbon\Carbon::parse($att->time_out)->format('H:i') : '-' }}</td>
                                    <td>
                                        @if($att->photo_out)
                                            <img src="{{ Storage::url('attendance/' . $att->photo_out) }}" width="50" class="img-thumbnail" onclick="window.open(this.src)">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $att->status == 'Present' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $att->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data absensi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-success { background-color: #28a745; color: white; }
    .badge-warning { background-color: #ffc107; color: black; }
    .img-thumbnail { cursor: pointer; }
</style>
@endsection
