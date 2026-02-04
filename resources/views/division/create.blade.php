@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Tambah Divisi</h3>
    </div>
    <div class="card-body">
<form action="{{ route('division.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nama Divisi :</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="description">Deskripsi :</label>
        <textarea name="description" value="{{ old('description') }}" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('division.index') }}" class="btn btn-secondary">Kembali</a>
</form>
</div>
</div>
@endsection