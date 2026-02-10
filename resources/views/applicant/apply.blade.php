{{-- 
    Form Lamaran Pekerjaan
    Menampilkan detail lowongan dan form untuk melamar
--}}

@extends('layouts.applicant')

@section('title', 'Lamar Pekerjaan')
@section('page_title', 'Lamar Pekerjaan')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
    </div>
</div>

{{-- Cek apakah ada lamaran yang sedang aktif --}}
@if($activeApplication)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            @if($activeApplication->vacancies_id == $vacancy->vacancies_id)
                <strong>Perhatian!</strong> Anda sudah melamar posisi ini pada {{ $activeApplication->created_at->format('d M Y H:i') }}.
            @else
                <strong>Perhatian!</strong> Anda masih dalam proses seleksi untuk posisi <strong>{{ $activeApplication->jobVacancie->title ?? 'Lainnya' }}</strong>.
                <br>
                Anda hanya dapat memiliki satu lamaran aktif dalam satu waktu.
            @endif
            <br>
            Status lamaran: 
            @if($activeApplication->status == 'pending')
                <span class="badge badge-warning">Menunggu Review</span>
            @elseif($activeApplication->status == 'approved')
                <span class="badge badge-info">Disetujui</span>
            @elseif($activeApplication->status == 'process')
                <span class="badge badge-primary">Dalam Proses</span>
            @elseif($activeApplication->status == 'accepted')
                <span class="badge badge-success">Diterima</span>
            @elseif($activeApplication->status == 'rejected')
                <span class="badge badge-danger">Ditolak</span>
            @else
                <span class="badge badge-secondary">{{ ucfirst($activeApplication->status) }}</span>
            @endif
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Detail Lowongan --}}
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-briefcase"></i> {{ $vacancy->title }}
                </h3>
                <div class="card-tools">
                    <span class="badge badge-success">Open</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-building text-primary"></i> 
                            <strong>Departemen:</strong> {{ $vacancy->departement->name ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-user-tag text-info"></i> 
                            <strong>Posisi:</strong> {{ $vacancy->position->name ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($vacancy->description)
                <h5><i class="fas fa-file-alt"></i> Deskripsi Pekerjaan</h5>
                <div class="mb-4">
                    {!! nl2br(e($vacancy->description)) !!}
                </div>
                @endif

                @if($vacancy->requirements)
                <h5><i class="fas fa-list-ul"></i> Persyaratan</h5>
                <div class="mb-4">
                    {!! nl2br(e($vacancy->requirements)) !!}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Form Lamar --}}
    <div class="col-md-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-paper-plane"></i> Kirim Lamaran
                </h3>
            </div>
            <div class="card-body">
                @if(!$applicant)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Anda harus melengkapi profil terlebih dahulu sebelum melamar.
                    </div>
                    <a href="{{ route('applicant.profile') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-user-edit"></i> Lengkapi Profil
                    </a>
                @elseif($activeApplication)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        @if($activeApplication->vacancies_id == $vacancy->vacancies_id)
                            Anda sudah melamar posisi ini.
                        @else
                            Anda sedang dalam proses seleksi untuk posisi lain.
                        @endif
                        Silakan tunggu hingga proses selesai.
                    </div>
                    <a href="{{ route('applicant.applications') }}" class="btn btn-info btn-block">
                        <i class="fas fa-history"></i> Lihat Riwayat Lamaran
                    </a>
                @else
                    <div class="mb-3">
                        <p><strong>Data yang akan dikirim:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-user"></i> {{ $applicant->name }}</li>
                            <li><i class="fas fa-envelope"></i> {{ $applicant->email }}</li>
                            <li><i class="fas fa-phone"></i> {{ $applicant->phone }}</li>
                            @if($applicant->cv_file)
                            <li><i class="fas fa-file-pdf text-success"></i> CV sudah diupload</li>
                            @else
                            <li><i class="fas fa-file-pdf text-danger"></i> CV belum diupload</li>
                            @endif
                        </ul>
                    </div>

                    <form action="{{ route('applicant.apply.submit', $vacancy->vacancies_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block" 
                                onclick="return confirm('Apakah Anda yakin ingin melamar posisi ini?')">
                            <i class="fas fa-paper-plane"></i> Kirim Lamaran
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('applicant.vacancies') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
