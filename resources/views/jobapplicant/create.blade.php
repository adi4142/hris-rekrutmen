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
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
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
                                        <select name="vacancies_id" class="form-control @error('vacancies_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Posisi --</option>
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
                            <h5 class="border-bottom pb-2 mb-3 text-secondary font-weight-bold"><i class="fas fa-file-upload mr-2"></i>Dokumen Pendukung</h5>
                            <p class="text-muted small mb-3">Format yang diperbolehkan: PDF, JPG, PNG, DOC (Maks. 5MB per file)</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cv_file">CV / Curriculum Vitae <span class="text-danger small">(Opsional jika sudah ada)</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="cv_file" class="custom-file-input @error('cv_file') is-invalid @enderror" id="cv_file">
                                            <label class="custom-file-label" for="cv_file">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->cv_file)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah CV</small>
                                        @endif
                                        @error('cv_file')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cover_letter">Surat Lamaran (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="cover_letter" class="custom-file-input @error('cover_letter') is-invalid @enderror" id="cover_letter">
                                            <label class="custom-file-label" for="cover_letter">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->cover_letter)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('cover_letter')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="portfolio">Portofolio (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="portfolio" class="custom-file-input @error('portfolio') is-invalid @enderror" id="portfolio">
                                            <label class="custom-file-label" for="portfolio">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->portfolio)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('portfolio')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_diploma">Ijazah Terakhir (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="last_diploma" class="custom-file-input @error('last_diploma') is-invalid @enderror" id="last_diploma">
                                            <label class="custom-file-label" for="last_diploma">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->last_diploma)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('last_diploma')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transcript">Transkrip Nilai (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="transcript" class="custom-file-input @error('transcript') is-invalid @enderror" id="transcript">
                                            <label class="custom-file-label" for="transcript">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->transcript)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('transcript')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supporting_certificates">Sertifikat Pendukung (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="supporting_certificates" class="custom-file-input @error('supporting_certificates') is-invalid @enderror" id="supporting_certificates">
                                            <label class="custom-file-label" for="supporting_certificates">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->supporting_certificates)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('supporting_certificates')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work_experience">Surat Pengalaman Kerja (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="work_experience" class="custom-file-input @error('work_experience') is-invalid @enderror" id="work_experience">
                                            <label class="custom-file-label" for="work_experience">Pilih file...</label>
                                        </div>
                                        @if($applicant && $applicant->work_experience)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah mengunggah</small>
                                        @endif
                                        @error('work_experience')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pb-4 px-4 d-flex justify-content-between">
                        <a href="{{ route('lowongan') }}" class="btn btn-outline-secondary px-4"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold"><i class="fas fa-paper-plane mr-2"></i>Kirim Lamaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update filename in custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
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
