@extends('layouts.admin')

@section('title', 'Edit Job Applicant')
@section('page_title', 'Edit Data Pendaftar')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Data: {{ $editjobApplicant->name }}</h3>
            </div>
            <form action="{{ route('jobapplicant.update', $editjobApplicant->job_applicant_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ $editjobApplicant->name }}">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $editjobApplicant->email }}">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Nomor HP</label>
                                <input type="text" name="phone" class="form-control" value="{{ $editjobApplicant->phone }}">
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select name="gender" class="form-control">
                                    <option value="male" {{ $editjobApplicant->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ $editjobApplicant->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ $editjobApplicant->date_of_birth }}">
                                @error('date_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea name="address" class="form-control" rows="2">{{ $editjobApplicant->address }}</textarea>
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Dokumen Pendaftar</h5>
                    <div class="row">
                        @php
                            $docs = [
                                'cv_file' => 'CV / Resume',
                                'cover_letter' => 'Surat Lamaran',
                                'portfolio' => 'Portofolio',
                                'last_diploma' => 'Ijazah Terakhir',
                                'transcript' => 'Transkrip Nilai',
                                'supporting_certificates' => 'Sertifikat',
                                'work_experience' => 'Surat Pengalaman Kerja'
                            ];
                        @endphp

                        @foreach($docs as $field => $label)
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label>{{ $label }}</label>
                                <input type="file" name="{{ $field }}" class="form-control-file">
                                @if($editjobApplicant->$field)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/' . $editjobApplicant->$field) }}" target="_blank" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye"></i> Lihat File
                                        </a>
                                    </div>
                                @else
                                    <small class="text-muted">Belum ada file.</small>
                                @endif
                                @error($field) <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('jobapplicant.index') }}" class="btn btn-default">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection