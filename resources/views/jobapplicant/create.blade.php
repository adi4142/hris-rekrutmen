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
                    <input type="hidden" name="vacancies_id" value="{{ old('vacancies_id', $selectedVacancyId ?? '') }}">
                    <div class="card-body p-4">

                        <!-- Bagian 1: Data Diri -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary font-weight-bold"><i class="fas fa-user mr-2"></i>Data Diri</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ old('name', $applicant->name ?? '') }}" 
                                            placeholder="Masukkan nama lengkap" required>
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
                                            placeholder="Masukkan email aktif" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Nomor HP <span class="text-danger">*</span></label>
                                        <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                            value="{{ old('phone', $applicant->phone ?? '') }}" 
                                            placeholder="Contoh: 08123456789" required>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <div class="d-flex mt-2">
                                            <div class="custom-control custom-radio mr-4">
                                                <input class="custom-control-input" type="radio" name="gender" id="genderMale" value="male" {{ old('gender', $applicant->gender ?? '') == 'male' ? 'checked' : '' }} required>
                                                <label class="custom-control-label font-weight-normal" for="genderMale">Laki-laki</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" name="gender" id="genderFemale" value="female" {{ old('gender', $applicant->gender ?? '') == 'female' ? 'checked' : '' }} required>
                                                <label class="custom-control-label font-weight-normal" for="genderFemale">Perempuan</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" max="{{ date('Y-m-d') }}" 
                                            value="{{ old('date_of_birth', $applicant->date_of_birth ?? '') }}" required>
                                        @error('date_of_birth')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Alamat Domisili <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                            rows="2" placeholder="Masukkan alamat lengkap" required>{{ old('address', $applicant->address ?? '') }}</textarea>
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
                                            $docs = (is_array($vacancy->required_documents)) ? $vacancy->required_documents : (json_decode($vacancy->required_documents, true) ?: []);
                                            // Jangan masukkan CV lagi jika sudah ada di input khusus
                                            $requiredDocs = array_filter($docs, function($doc) {
                                                return !in_array(strtolower($doc), ['cv', 'curriculum vitae']);
                                            });
                                        }
                                    @endphp

                                    @if($vacancy)
                                        <div class="alert alert-light border mb-4">
                                            <div class="mb-3 p-3 bg-light border-left border-primary rounded shadow-sm">
                                                <label class="text-primary font-weight-bold d-block"><i class="fas fa-file-pdf mr-1"></i> Dokumen Utama</label>
                                                <div class="form-group mb-1">
                                                    <label class="small font-weight-bold">Curriculum Vitae (CV) <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="cv_file" class="custom-file-input @error('cv_file') is-invalid @enderror" id="doc_cv_fixed" required accept=".pdf,.doc,.docx">
                                                        <label class="custom-file-label" for="doc_cv_fixed">Pilih file CV Anda...</label>
                                                    </div>
                                                    @error('cv_file')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <small class="text-muted">Format PDF/DOCX, Max 3MB</small>
                                            </div>

                                            @if(count($requiredDocs) > 0)
                                                <h6 class="border-bottom pb-2 my-3 text-secondary font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Dokumen Tambahan:</h6>
                                            @endif
                                            
                                            @if($errors->any())
                                                <div class="alert alert-danger py-2 px-3 mb-3">
                                                    <ul class="mb-0 small">
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if($requiredDocs !== [])
                                                <div class="row">
                                                    @foreach($requiredDocs as $index => $docName)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">{{ $docName }} <span class="text-danger">*</span></label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="required_files[{{ $docName }}]" class="custom-file-input @error('required_files.'.$docName) is-invalid @enderror" id="doc_{{ $index }}" required>
                                                                    <label class="custom-file-label" for="doc_{{ $index }}">Pilih file {{ $docName }}...</label>
                                                                </div>
                                                                @error('required_files.'.$docName)
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i> Silakan pilih <strong>Posisi yang Dilamar</strong> terlebih dahulu untuk melihat persyaratan dokumen.
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
    .custom-control-label { cursor: pointer; }
</style>
@endpush
@endsection
