@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Peran</h3>
    </div>
    <div class="card-body">
            <form action="{{route('role.update', $editrole->roles_id)}}" method="POST">
                {{csrf_field()}}
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama : </label>
                    <input type="text" name="name" value="{{$editrole->name}}"required class="form-control">
                    <label for="description">Deskripsi : </label>
                    <textarea name="description" class="form-control">{{$editrole->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ganti</button>
                <a href="{{route('role.index')}}" class="btn btn-secondary">Kembali</a>
            </form>
    </div>
</div>
@endsection