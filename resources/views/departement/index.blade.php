@extends('layouts.admin')
@section('content') 

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Departement</h3>
        <div class="card-tools">
            <a href="{{ route('departement.create') }}" class="btn btn-success">Tambah Departement</a>
        </div>
    </div>
    <div class="card-body">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Departement</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departement as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            <td>    
                <form action="{{ route('departement.destroy', $v->departement_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <a href="{{ route('departement.edit', $v->departement_id) }}" class="btn btn-warning">Edit</a>
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