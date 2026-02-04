@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Posisi</h3>
    </div>
    <div class="card-body">
            <form action="{{route('position.update', $editposition->position_id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <div>   
                        <label for="name">Nama : </label>
                        <input type="text" name="name" value="{{$editposition->name}}"required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi : </label>
                        <textarea type="text" name="description" value="{{$editposition->description}}"required class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ganti</button>
                <a href="{{route('position.index')}}" class="btn btn-secondary">Kembali</a>
            </form>
    </div>
</div>
@endsection