@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Tambah Pengguna</h3>
    </div>
    <div class="card-body">
<form action="{{ route('user.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name">Nama :</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
<div class="form-group">
    <label for="password">Password :</label>

    <div class="input-group">
        <input type="text" 
               id="generatedPassword"
               name="password" 
               class="form-control" 
               value="{{ $generatedPassword }}" 
               readonly>

        <button class="btn btn-outline-primary" 
                type="button" 
                onclick="copyPassword(this)">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>

    <small id="copyMessage" class="text-success d-none">
        Password berhasil disalin ✔
    </small>

    @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
    @endif
</div>

    <div class="form-group">
        <label for="roles_id">Role :</label>
        <select name="roles_id" class="form-control" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
            <option value="{{ $role->roles_id }}">
                {{ $role->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</form>
    </div>
</div>
@endsection

<script>
function copyPassword(button) {
    const password = document.getElementById("generatedPassword").value;
    const message = document.getElementById("copyMessage");

    navigator.clipboard.writeText(password).then(() => {

        message.classList.remove("d-none");

        button.innerHTML = '<i class="bi bi-check-lg"></i>';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');

        setTimeout(() => {
            message.classList.add("d-none");
            button.innerHTML = '<i class="bi bi-clipboard"></i>';
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);

    });
}
</script>
