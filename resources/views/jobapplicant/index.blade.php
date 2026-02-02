@extends('layouts.app')

@section('title', 'Job Applicant')
@section('page_title', 'Manajemen Pendaftar')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Pendaftar</h3>
        <div class="card-tools">
            <a href="{{ route('jobapplicant.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pendaftar
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>CV</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobApplicants as $jobApplicant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jobApplicant->name }}</td>
                        <td>{{ $jobApplicant->email }}</td>
                        <td>{{ $jobApplicant->phone }}</td>
                        <td>{{ $jobApplicant->address }}</td>
                        <td>{{ $jobApplicant->date_of_birth }}</td>
                        <td>{{ $jobApplicant->gender }}</td>
                        <td>
                            @if ($jobApplicant->cv_file)
                                <a href="{{ asset('storage/' . $jobApplicant->cv_file) }}" target="_blank">Lihat CV</a>
                            @else
                                -
                            @endif</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('jobapplicant.edit', $jobApplicant->job_applicant_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jobapplicant.destroy', $jobApplicant->job_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pendaftar ini?')" title="Hapus">
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