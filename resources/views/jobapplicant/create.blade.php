@extends('layouts.lamaran')
@section('title', 'Lamar Pekerjaan')
@section('page_title', 'Form Lamaran Kerja')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card card-primary card-outline shadow-lg border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h3 class="card-title font-weight-bold text-primary"><i class="fas fa-file-signature mr-2"></i>Form Lamaran Kerja</h3>
                </div>
                <form action="{{ route('jobapplicant.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        @if($applicant)
                            <div class="alert alert-info alert-dismissible bg-light-info border-info mb-4">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info-circle"></i> Selamat Datang Kembali!</h5>
                                Data profil Anda sudah tersimpan. Anda hanya perlu memilih posisi yang dilamar dan mengunggah dokumen terbaru jika diperlukan.
                            </div>
                        @endif

                        <!-- Bagian 1: Data Diri -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary font-weight-bold"><i class="fas fa-user mr-2"></i>Data Diri</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ old('name', $applicant->name ?? '') }}" 
                                            placeholder="Masukkan nama lengkap" {{ $applicant ? 'readonly' : 'required' }}>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ old('email', $applicant->email ?? '') }}" 
                                            placeholder="Masukkan email aktif" {{ $applicant ? 'readonly' : 'required' }}>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Nomor HP <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                            value="{{ old('phone', $applicant->phone ?? '') }}" 
                                            placeholder="Contoh: 08123456789" {{ $applicant ? 'readonly' : 'required' }}>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                                        @if($applicant)
                                            <input type="text" class="form-control" value="{{ $applicant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}" readonly>
                                            <input type="hidden" name="gender" value="{{ $applicant->gender }}">
                                        @else
                                            <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                                <option value="">-- Pilih Gender --</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        @endif
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                            value="{{ old('date_of_birth', $applicant->date_of_birth ?? '') }}" {{ $applicant ? 'readonly' : 'required' }}>
                                        @error('date_of_birth')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vacancies_id">Posisi yang Dilamar <span class="text-danger">*</span></label>
                                        <select name="vacancies_id" id="vacancies_id" class="form-control @error('vacancies_id') is-invalid @enderror" required onchange="window.location.href = '{{ route('jobapplicant.create') }}?vacancies_id=' + this.value">
                                            <option value="">-- Pilih Posisi --</option>
                                            @foreach($jobVacancies as $v)
                                                <option value="{{ $v->vacancies_id }}" {{ (old('vacancies_id', $selectedVacancyId ?? '') == $v->vacancies_id) ? 'selected' : '' }}>
                                                    {{ $v->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vacancies_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Alamat Domisili <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                            rows="2" placeholder="Masukkan alamat lengkap" {{ $applicant ? 'readonly' : 'required' }}>{{ old('address', $applicant->address ?? '') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Dokumen -->
                        <div>
                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $requiredDocs = [];
                                        if (isset($vacancy) && $vacancy->required_documents) {
                                            $requiredDocs = json_decode($vacancy->required_documents, true) ?: [];
                                        }
                                    @endphp

                                    @if($vacancy)
                                        <div class="alert alert-light border  mb-4">
                                             <h6 class="border-bottom pb-2 mb-3 text-secondary font-weight-bold"><i class="fas fa-file-upload mr-2"></i>Dokumen yang Diperlukan untuk Posisi: <u>{{ $vacancy->title }}</u></h6>
                                            @if(!empty($requiredDocs))
                                                <div class="row">
                                                    @foreach($requiredDocs as $docName)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">{{ $docName }} <span class="text-danger">*</span></label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="required_files[{{ $docName }}]" class="custom-file-input @error('required_files.'.$docName) is-invalid @enderror" id="doc_{{ $loop->index }}" required>
                                                                    <label class="custom-file-label" for="doc_{{ $loop->index }}">Pilih file {{ $docName }}...</label>
                                                                </div>
                                                                @error('required_files.'.$docName)
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">Tidak ada dokumen khusus yang diwajibkan untuk posisi ini.</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i> Silakan pilih <strong>Posisi yang Dilamar</strong> terlebih dahulu untuk melihat dokumen kerja yang diperlukan.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pb-4 px-4 d-flex justify-content-between">
                        <a href="{{ route('lowongan') }}" class="btn btn-outline-secondary px-4"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold" id="submitBtn" {{ !$vacancy ? 'disabled' : '' }}><i class="fas fa-paper-plane mr-2"></i>Kirim Lamaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Update filename in custom file input label
        $(document).on('change', '.custom-file-input', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush

@push('css')
<style>
    .bg-light-info { background-color: #e7f3ff; }
    .border-info { border-color: #b8daff; }
    .custom-file-label::after { content: "Cari"; }
</style>
@endpush
@endsection
