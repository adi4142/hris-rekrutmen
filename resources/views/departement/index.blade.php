@extends('layouts.app')
@section('content') 

<h2>Daftar Departement</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Departement</th>
            <th>Deskripsi</th>
            <th>
                <a href="{{ route('departement.create') }}">Tambah Departement</a>
            </th>
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
                    <a href="{{ route('departement.edit', $v->departement_id) }}">Edit</a>
                    <button type="submit" onclick="return confirm('Kamu serius?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection