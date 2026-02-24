@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Pengguna</h3>
    </div>
    <div class="card-body">
<form action="{{ route('user.update', $edituser->user_id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    <div class="form-group">
        <label for="name">Nama :</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $edituser->name) }}" required>
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $edituser->email) }}" required>
        @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="password">Password :</label>
        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
        @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="roles_id">Role :</label>
        <select name="roles_id" class="form-control" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $rolies)
            <option value="{{ $rolies->roles_id }}" {{ $edituser->roles_id == $rolies->roles_id ? 'selected' : '' }}>
                {{ $rolies->name }}
            </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
</form>
    </div>
</div>
@endsection