@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools d-flex align-items-center">
            <form action="{{ route('user.index') }}" method="GET" class="mr-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email atau role..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        @if(request('search'))
                        <a href="{{ route('user.index') }}" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <a href="{{ route('user.create') }}" class="btn btn-success btn-sm">Tambah Pengguna</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->email }}</td>
            <td>{{ $v->role->name ?? '_'}}</td>
            <td style="white-space: nowrap;">
                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                    <a href="{{ route('user.edit', $v->user_id) }}" class="btn btn-sm btn-warning text-dark"><i class="fas fa-edit"></i> Edit</a>
                    <form action="{{ route('user.destroy', $v->user_id) }}" method="POST" class="m-0 p-0">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

        @if($users->hasPages())
        <div class="mt-4 text-center">
            {{ $users->appends(request()->input())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection