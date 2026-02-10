@extends('layouts.admin')

@section('title', 'Job Applicant')
@section('page_title', 'Manajemen Pendaftar')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Pendaftar</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Info Diri</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobApplicants as $jobApplicant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $jobApplicant->name }}</strong><br>
                            <small class="text-muted">{{ $jobApplicant->email }}</small>
                        </td>
                        <td>
                            <i class="fas fa-phone mr-1"></i> {{ $jobApplicant->phone }}
                        </td>
                        <td>
                            <small>
                                <strong>Lahir:</strong> {{ $jobApplicant->date_of_birth }}<br>
                                <strong>Gender:</strong> {{ ucfirst($jobApplicant->gender) }}
                            </small>
                        </td>
                        <td>
                            @php
                                $docs = [
                                    'cv_file' => ['icon' => 'fa-file-pdf', 'title' => 'CV'],
                                    'cover_letter' => ['icon' => 'fa-envelope-open-text', 'title' => 'Surat Lamaran'],
                                    'portfolio' => ['icon' => 'fa-briefcase', 'title' => 'Portofolio'],
                                    'last_diploma' => ['icon' => 'fa-graduation-cap', 'title' => 'Ijazah'],
                                    'transcript' => ['icon' => 'fa-file-invoice', 'title' => 'Transkrip'],
                                    'supporting_certificates' => ['icon' => 'fa-certificate', 'title' => 'Sertifikat'],
                                    'work_experience' => ['icon' => 'fa-user-tie', 'title' => 'Pengalaman']
                                ];
                                $found = false;
                            @endphp
                            
                            <div class="d-flex flex-wrap" style="gap: 5px;">
                                @foreach($docs as $field => $data)
                                    @if($jobApplicant->$field)
                                        <a href="{{ asset('storage/' . $jobApplicant->$field) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="{{ $data['title'] }}">
                                            <i class="fas {{ $data['icon'] }}"></i>
                                        </a>
                                        @php $found = true; @endphp
                                    @endif
                                @endforeach
                                
                                @if(!$found)
                                    <span class="text-muted small">Tidak ada dokumen</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('jobapplicant.edit', $jobApplicant->job_applicant_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jobapplicant.destroy', $jobApplicant->job_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pendaftar ini? Semua data lamaran terkait juga akan berpengaruh.')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection