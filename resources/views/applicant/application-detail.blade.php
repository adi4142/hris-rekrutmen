{{-- 
    Detail Lamaran
    Menampilkan detail lengkap lamaran
--}}

@extends('layouts.applicant')

@section('title', 'Detail Lamaran')
@section('page_title', 'Detail Lamaran')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="{{ route('applicant.applications') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Detail Lowongan --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-briefcase"></i> {{ $application->jobVacancie->title ?? 'Posisi tidak tersedia' }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-building text-primary"></i> 
                            <strong>Departemen:</strong> {{ $application->jobVacancie->departement->name ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-user-tag text-info"></i> 
                            <strong>Posisi:</strong> {{ $application->jobVacancie->position->name ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($application->jobVacancie && $application->jobVacancie->description)
                <h5><i class="fas fa-file-alt"></i> Deskripsi Pekerjaan</h5>
                <div class="mb-4">
                    {!! nl2br(e($application->jobVacancie->description)) !!}
                </div>
                @endif
            </div>
        </div>

        {{-- Proses Seleksi --}}
        @if($application->selectionApplicant && $application->selectionApplicant->count() > 0)
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i> Proses Seleksi
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tahap Seleksi</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($application->selectionApplicant as $selection)
                        <tr>
                            <td>{{ $selection->selection->name ?? '-' }}</td>
                            <td>{{ $selection->score ?? '-' }}</td>
                            <td>
                                @if($selection->status == 'passed')
                                    <span class="badge badge-success">Lulus</span>
                                @elseif($selection->status == 'failed')
                                    <span class="badge badge-danger">Tidak Lulus</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $selection->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Status Lamaran --}}
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Status Lamaran
                </h3>
            </div>
            <div class="card-body text-center">
                @if($application->status == 'pending')
                    <span class="badge badge-warning" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-clock"></i> Menunggu Review
                    </span>
                    <p class="mt-3 text-muted">
                        Lamaran Anda sedang dalam proses review oleh tim HRD.
                    </p>
                @elseif($application->status == 'accepted')
                    <span class="badge badge-success" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-check"></i> Diterima
                    </span>
                    <p class="mt-3 text-success">
                        <strong>Selamat!</strong> Lamaran Anda telah diterima.
                    </p>
                @elseif($application->status == 'rejected')
                    <span class="badge badge-danger" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-times"></i> Ditolak
                    </span>
                    <p class="mt-3 text-muted">
                        Mohon maaf, lamaran Anda tidak dapat kami proses lebih lanjut.
                    </p>
                @endif

                <hr>
                <p class="mb-1"><strong>Tanggal Melamar:</strong></p>
                <p>{{ $application->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
