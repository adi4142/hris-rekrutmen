@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <a href="{{ route('user.create') }}" class="btn btn-success">Tambah Pengguna</a>
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
            <td>
                <form action="{{ route('user.destroy', $v->user_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <a href="{{ route('user.edit', $v->user_id) }}" class="btn btn-warning">Edit</a>
                    <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
@endsection