{{-- 
    Profil Pelamar
    Form untuk melihat dan mengedit profil pelamar
--}}

@extends('layouts.applicant')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

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
    <div class="col-md-4">
        {{-- Card Foto Profil --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Profil Saya
                </h3>
            </div>
            @php
                $applicant = $user->applicant;
                $photo = $applicant && $applicant->photo 
                    ? asset('storage/' . $applicant->photo) 
                    : asset('img/user2-160x160.jpg');
            @endphp
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $photo }}"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">Pelamar</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Bergabung</b> <a class="float-right">{{ $user->created_at->format('d M Y') }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-footer px-0">
                <button type="button" class="btn btn-primary btn-block mb-2 font-weight-bold" data-toggle="modal" data-target="#modalEditProfile">
                    <i class="fas fa-user-edit"></i> Edit Profil
                </button>
                <button type="button" class="btn btn-warning btn-block font-weight-bold" data-toggle="modal" data-target="#modalChangePassword">
                    <i class="fas fa-key"></i> Ganti Password
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Card Data Profil --}}
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-id-card mr-1 text-primary"></i> Data Personal & Berkas
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="text-muted border-bottom pb-2 mb-3"><i class="fas fa-info-circle mr-1"></i> Informasi Dasar</h5>
                        <div class="row px-2">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Nama Lengkap</label>
                                <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Email Utama</label>
                                <div class="font-weight-bold text-dark">{{ $user->email }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                                <div class="font-weight-bold text-dark">{{ $applicant->phone ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Tanggal Lahir</label>
                                <div class="font-weight-bold text-dark">
                                    {{ $applicant && $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->format('d F Y') : '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Jenis Kelamin</label>
                                <div class="font-weight-bold text-dark">
                                    {{ ($applicant->gender ?? '') == 'male' ? 'Laki-laki' : (($applicant->gender ?? '') == 'female' ? 'Perempuan' : '-') }}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small text-muted mb-1 d-block">Alamat Lengkap</label>
                                <div class="text-dark">{{ $applicant->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light text-center py-2">
                <small class="text-muted font-italic">Terakhir diperbarui: {{ $applicant ? $applicant->updated_at->format('d M Y H:i') : '-' }}</small>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT PROFIL --}}
<div class="modal fade" id="modalEditProfile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Lengkapi Profil Anda</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('applicant.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $applicant->phone ?? '') }}" required>
                                @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $applicant->date_of_birth ?? '') }}" required>
                                @error('date_of_birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="male" {{ old('gender', $applicant->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $applicant->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $applicant->address ?? '') }}</textarea>
                                @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Foto Profil (Opsi)</label>
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="photo_input" accept="image/*">
                                    <label class="custom-file-label" for="photo_input">Pilih Foto</label>
                                </div>
                                @error('photo') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary border-0" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL GANTI PASSWORD --}}
<div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-key mr-2"></i> Ganti Password Akun</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('applicant.password.change') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 small">
                        <i class="fas fa-info-circle mr-1"></i> Password minimal 8 karakter.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Password Baru</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light text-dark">
                    <button type="button" class="btn btn-secondary border-0" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark shadow-sm px-4">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle File Input Labels
    $(document).on('change', '.custom-file-input', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file';
        $(e.target).next('.custom-file-label').text(fileName);
    });

    // Auto open modals on validation errors
    @if($errors->has('current_password') || $errors->has('new_password'))
        $('#modalChangePassword').modal('show');
    @elseif($errors->any())
        $('#modalEditProfile').modal('show');
    @endif
</script>
@endpush
