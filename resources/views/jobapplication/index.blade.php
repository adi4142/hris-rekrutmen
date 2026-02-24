@extends('layouts.admin')
@section('title', 'Daftar Pelamar')
@section('page_title', 'Data Pelamar (Job Applications)')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Pelamar</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelamar</th>
                            <th>Kontak</th>
                            <th>Total Lamaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $loop->iteration + ($applicants->currentPage() - 1) * $applicants->perPage() }}</td>
                            <td>
                                <strong>{{ $applicant->name }}</strong><br>
                                <span class="badge badge-light">{{ $applicant->gender }}</span>
                                <small class="text-muted">{{ $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->age . ' Tahun' : '-' }}</small>
                            </td>
                            <td>
                                <i class="fas fa-envelope mr-1 text-muted"></i> {{ $applicant->email }}<br>
                                <i class="fas fa-phone mr-1 text-muted"></i> {{ $applicant->phone }}
                            </td>
                            <td>
                                <span class="badge badge-info text-md">{{ $applicant->applications_count }} Lamaran</span>
                            </td>
                            <td>
                                <a href="{{ route('jobapplication.applicant', $applicant->job_applicant_id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i> Lihat Lamaran
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Belum ada data pelamar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $applicants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
