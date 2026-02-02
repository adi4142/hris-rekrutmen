@extends('layouts.app')

@section('title', 'Karyawan')
@section('page_title', 'Data Karyawan')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Karyawan</h3>
        <div class="card-tools">
            <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>
    <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Akun</th>
                                    <th>Email</th>
                                    <th>Departemen</th>
                                    <th>Posisi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee as $v)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $v->nip }}</td>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->user->name ?? '-' }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->departement->name ?? '-' }}</td>
                                    <td>{{ $v->position->name ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employee.edit', $v->nip) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employee.destroy', $v->nip) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Kamu serius?')">
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