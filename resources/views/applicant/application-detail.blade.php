{{-- 
    Detail Lamaran
    Menampilkan detail lengkap lamaran
--}}

@extends('layouts.applicant')

@section('title', 'Detail Ldamaran')
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
        {{-- Proses Seleksi --}}
        @if($application->selectionApplicant && $application->selectionApplicant->count() > 0)
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i> Riwayat Proses Seleksi
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($application->selectionApplicant as $selection)
                        @php
                            $bgClass = 'bg-gray';
                            $icon = 'fa-clock';
                            if($selection->status == 'passed') {
                                $bgClass = 'bg-success';
                                $icon = 'fa-check';
                            } elseif($selection->status == 'failed') {
                                $bgClass = 'bg-danger';
                                $icon = 'fa-times';
                            } elseif($selection->status == 'process') {
                                $bgClass = 'bg-primary';
                                $icon = 'fa-spinner fa-spin';
                            } elseif($selection->status == 'unprocess') {
                                $bgClass = 'bg-warning';
                                $icon = 'fa-hourglass-start';
                            }
                        @endphp

                        <!-- timeline time label -->
                        <div class="time-label">
                            <span class="{{ $bgClass }}">
                                {{ $selection->selection_date ? \Carbon\Carbon::parse($selection->selection_date)->translatedFormat('d F Y') : 'Jadwal Menyusul' }}
                            </span>
                        </div>
                        
                        <div>
                            <i class="fas {{ $icon }} {{ $bgClass }}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> {{ $selection->updated_at->diffForHumans() }}</span>
                                <h3 class="timeline-header">
                                    <strong>{{ $selection->selection->name ?? 'Tahapan Seleksi' }}</strong>
                                </h3>

                                <div class="timeline-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Status:</strong>
                                            @if($selection->status == 'passed')
                                                <span class="badge badge-success">Lulus</span>
                                            @elseif($selection->status == 'failed')
                                                <span class="badge badge-danger">Tidak Lulus</span>
                                            @elseif($selection->status == 'process')
                                                <span class="badge badge-primary">Sedang Berlangsung</span>
                                            @else
                                                <span class="badge badge-warning">Belum Diproses</span>
                                            @endif
                                        </div>
                                        @if($selection->status == 'passed' || $selection->status == 'failed')
                                            <div class="col-sm-6">
                                                <strong>Skor:</strong> {{ $selection->score > 0 ? $selection->score : '-' }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($selection->notes && ($selection->status == 'passed' || $selection->status == 'failed'))
                                        <div class="mt-2 p-2 bg-light rounded border">
                                            <strong>Catatan:</strong><br>
                                            {{ $selection->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> Belum Ada Jadwal Seleksi</h5>
            Saat ini belum ada tahapan seleksi yang dijadwalkan untuk lamaran Anda. Mohon menunggu informasi selanjutnya.
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
                @elseif($application->status == 'process')
                    <span class="badge badge-primary" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-spinner fa-spin"></i> Dalam Proses
                    </span>
                    <p class="mt-3 text-muted">
                        Lamaran Anda sedang dalam proses seleksi oleh tim HRD.
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
