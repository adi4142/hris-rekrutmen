@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Divisi</h3>
        <div class="card-tools">
            <a href="{{ route('division.create') }}" class="btn btn-success">Tambah Divisi</a>
        </div>
    </div>
    <div class="card-body">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Divisi</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($division as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            @if (auth()->user()->role->name !== 'Super Admin')            
            <td>
                <form action="{{ route('division.destroy', $v->division_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <a href="{{ route('division.edit', $v->division_id) }}" class="btn btn-warning">Edit</a>
                    <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger">Hapus</button>
                </form>
            </td>
            @endif            
        </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@endsection