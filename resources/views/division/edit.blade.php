@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Divisi</h3>
    </div>
    <div class="card-body">
            <form action="{{route('division.update', $editdivision->division_id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama : </label>
                    <input type="text" name="name" value="{{$editdivision->name}}"required class="form-control">
                    <label for="description">Deskripsi : </label>
                    <textarea name="description" class="form-control">{{$editdivision->description}}</textarea>
                    <button type="submit" class="btn btn-primary">Ganti</button>
                    <a href="{{route('division.index')}}" class="btn btn-danger">Kembali</a>
                </div>
            </form>
    </div>
</div>
@endsection