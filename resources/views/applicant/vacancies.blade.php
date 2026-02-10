{{-- 
    Daftar Lowongan Tersedia
    Menampilkan lowongan yang bisa dilamar
--}}

@extends('layouts.applicant')

@section('title', 'Lowongan Tersedia')
@section('page_title', 'Lowongan Tersedia')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
    </div>
</div>

<div class="row">
    @forelse($vacancies as $vacancy)
    <div class="col-md-6 col-lg-4">
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
                <p class="mb-2">
                    <i class="fas fa-building text-primary"></i> 
                    <strong>Departemen:</strong> {{ $vacancy->departement->name ?? '-' }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-user-tag text-info"></i> 
                    <strong>Posisi:</strong> {{ $vacancy->position->name ?? '-' }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-calendar text-secondary"></i> 
                    <strong>Dibuka:</strong> {{ $vacancy->created_at->format('d M Y') }}
                </p>
                
                @if($vacancy->description)
                <hr>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                    {{ \Illuminate\Support\Str::limit($vacancy->description, 100) }}
                </p>
                @endif
            </div>
            <div class="card-footer text-center">
                @if($activeApplication && $activeApplication->vacancies_id == $vacancy->vacancies_id)
                    <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-info btn-block">
                        <i class="fas fa-check-circle"></i> Sudah Dilamar
                    </a>
                @elseif($activeApplication)
                    <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-clock"></i> Dalam Seleksi Lain
                    </a>
                @else
                    <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Lamar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada lowongan tersedia saat ini</h4>
                <p class="text-muted">Silakan cek kembali nanti untuk lowongan baru.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($vacancies->hasPages())
<div class="row">
    <div class="col-md-12 d-flex justify-content-center">
        {{ $vacancies->links() }}
    </div>
</div>
@endif
@endsection
