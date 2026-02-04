@extends('layouts.admin')

@section('title', 'Job Vacancie')
@section('page_title', 'Job Vacancie')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Posisi</h3>
                <div class="card-tools">
                    <a href="{{ route('jobvacancie.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Posisi
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Posisi</th>
                                <th>Departemen</th>
                                <th>Deskripsi</th>
                                <th>Requirements</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobVacancies as $jobVacancie)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jobVacancie->title }}</td>
                                <td>{{ $jobVacancie->departement->name }}</td>
                                <td>{{ $jobVacancie->description }}</td>
                                <td>{{ $jobVacancie->requirements }}</td>
                                <td>{{ $jobVacancie->status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('jobvacancie.edit', $jobVacancie->vacancies_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('jobvacancie.destroy', $jobVacancie->vacancies_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus posisi ini?')" title="Hapus">
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
    </div>
</div>
@endsection