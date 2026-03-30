{{-- 
    Riwayat Lamaran
    Menampilkan daftar lamaran yang pernah diajukan
--}}

@extends('layouts.applicant')

@section('title', 'Riwayat Lamaran')
@section('page_title', 'Riwayat Lamaran')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Daftar Lamaran Saya
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Posisi</th>
                            <th>Departemen</th>
                            <th>Tanggal Melamar</th>
                            <th style="width: 120px">Status</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                        <tr>
                            <td>{{ method_exists($applications, 'firstItem') ? $applications->firstItem() + $index : $index + 1 }}</td>
                            <td>
                                <strong>{{ $application->jobVacancie->title ?? 'Posisi tidak tersedia' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $application->jobVacancie->position->name ?? '-' }}
                                </small>
                            </td>
                            <td>{{ $application->jobVacancie->departement->name ?? '-' }}</td>
                            <td>{{ $application->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @if($application->status == 'pending' || $application->status == 'applied')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Review Berkas
                                    </span>
                                @elseif($application->status == 'accepted')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Diterima
                                    </span>
                                @elseif($application->status == 'rejected')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($application->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('applicant.application.detail', $application->application_id) }}" 
                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Anda belum pernah melamar pekerjaan.</p>
                                <a href="{{ route('applicant.vacancies') }}" class="btn btn-primary">
                                    <i class="fas fa-briefcase"></i> Lihat Lowongan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($applications, 'hasPages') && $applications->hasPages())
            <div class="card-footer">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
