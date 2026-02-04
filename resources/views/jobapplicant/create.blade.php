@extends('layouts.lamaran')
@section('title', 'Lamar Pekerjaan')
@section('page_title', 'Form Lamaran Kerja')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="padding: 20px;">
    <div class="col-md-8 col-lg-6"> 
        <div class="card card-primary card-outline shadow-lg">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Isi Data Lamaran</h3>
            </div>
            <form action="{{ route('jobapplicant.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email aktif">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Nomor HP</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">-- Pilih Gender --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vacancies_id">Posisi yang Dilamar</label>
                                <select name="vacancies_id" class="form-control @error('vacancies_id') is-invalid @enderror">
                                    @foreach($jobVacancies as $vacancy)
                                        <option value="{{ $vacancy->vacancies_id }}" {{ (old('vacancies_id', $selectedVacancyId ?? '') == $vacancy->vacancies_id) ? 'selected' : '' }}>
                                            {{ $vacancy->title }}
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
                                <label for="address">Alamat Domisili</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cv_file">Upload CV (PDF/DOC)</label>
                                <div class="custom-file">
                                    <input type="file" name="cv_file" class="custom-file-input @error('cv_file') is-invalid @enderror" id="cv_file">
                                    <label class="custom-file-label" for="cv_file">Pilih file</label>
                                </div>
                                <small class="form-text text-muted">Maksimal ukuran file: 2MB</small>
                                @error('cv_file')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('lowongan') }}" class="btn btn-default">Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i> Kirim Lamaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

