@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Pengguna</h3>
    </div>
    <div class="card-body">
<form action="{{ route('user.update', $user->user_id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    <div>
        <label for="name">Nama Pengguna :</label>
        <br>
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div>
        <label for="email">Email :</label>
        <br>
        <input type="email" name="email" value="{{ old('email', $user->email) }}">
        @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div>
        <label for="password">Password :</label>
        <br>
        <input type="password" name="password">
        @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div>
        <label for="roles_id">Role :</label>
        <br>
        <select name="roles_id">
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $rolies)
            <option value="{{ $rolies->roles_id }}" {{ $user->roles_id == $rolies->roles_id ? 'selected' : '' }}>
                {{ $rolies->name }}
            </option>
            @endforeach
        </select>
    </div>
    <button type="submit">Simpan</button>
    <a href="{{ route('user.index') }}">Kembali</a>
</form>
    </div>
</div>
@endsection