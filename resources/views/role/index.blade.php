@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Role</h3>
        <div class="card-tools">
            <a href="{{ route('role.create') }}" class="btn btn-primary">Tambah Role</a>
        </div>
    </div>
    <div class="card-body">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Role</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            <td>
                <form action="{{ route('role.destroy', $v->roles_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <a href="{{ route('role.edit', $v->roles_id) }}" class="btn btn-warning">Edit</a>
                    <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
@endsection