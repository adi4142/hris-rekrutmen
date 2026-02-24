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
                    @php
                        $requiredDocs = json_decode($vacancy->required_documents) ?? [];
                    @endphp
                    <div class="mb-3">
                        <p><strong>Data Profil:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-user"></i> {{ $applicant->name }}</li>
                            <li><i class="fas fa-envelope"></i> {{ $applicant->email }}</li>
                            <li><i class="fas fa-phone"></i> {{ $applicant->phone }}</li>
                        </ul>
                    </div>

                    <form action="{{ route('applicant.apply.submit', $vacancy->vacancies_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="text-primary"><i class="fas fa-file-upload"></i> Dokumen Yang Diperlukan</label>
                            <small class="d-block text-muted mb-2">* Wajib diisi sesuai permintaan lowongan ini.</small>
                            
                            @if(count($requiredDocs) > 0)
                                @foreach($requiredDocs as $docName)
                                    <div class="mb-3">
                                        <label for="req_doc_{{ $loop->index }}">{{ $docName }}</label>
                                        <input type="file" name="required_files[{{ $docName }}]" id="req_doc_{{ $loop->index }}" class="form-control-file @error('required_files.'.$docName) is-invalid @enderror btn btn-primary" required>
                                        @error('required_files.'.$docName)
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small">Tidak ada dokumen khusus yang diwajibkan.</p>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group mb-4">
                            <label class="text-info"><i class="fas fa-folder-plus"></i> Dokumen Tambahan (Opsional)</label>
                            <div id="additional-docs-container">
                                {{-- Dynamically added fields --}}
                            </div>
                            <button type="button" class="btn btn-outline-info btn-xs mt-2" onclick="addDocField()">
                                <i class="fas fa-plus"></i> Tambah Dokumen Lainnya
                            </button>
                        </div>
                        <button type="submit" class="btn btn-success btn-block" 
                                onclick="return confirm('Apakah Anda yakin ingin melamar posisi ini?')">
                            <i class="fas fa-paper-plane"></i> Kirim Lamaran
                        </button>
                    </form>

                    <script>
                        function addDocField() {
                            const container = document.getElementById('additional-docs-container');
                            const newItem = document.createElement('div');
                            newItem.className = 'additional-doc-item mb-2';
                            newItem.innerHTML = `
                                <div class="input-group">
                                    <input type="text" name="additional_doc_names[]" class="form-control form-control-sm" placeholder="Nama Dokumen">
                                    <input type="file" name="additional_files[]" class="form-control-file ml-2" style="width: auto;">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.additional-doc-item').remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            container.appendChild(newItem);
                        }
                    </script>
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
