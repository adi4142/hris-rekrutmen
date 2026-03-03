@extends('layouts.auth')

@section('title', 'Ganti Password')
@section('card-class', 'card-warning')


@section('content')

<h5 class="text-center mb-3">Ganti Password Anda</h5>
<p class="login-box-msg text-sm">
Ini adalah login pertama Anda atau password Anda baru saja direset.
Silakan buat password baru demi keamanan akun Anda.
</p>

<form action="{{ route('password.change.update') }}" method="POST">
  @csrf

  <div class="input-group mb-3">
    <input type="password" name="password"
      class="form-control @error('password') is-invalid @enderror"
      placeholder="Password Baru" required autofocus>

    <div class="input-group-append">
      <div class="input-group-text">
        <span class="fas fa-lock"></span>
      </div>
    </div>

    @error('password')
      <span class="invalid-feedback">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>

  <div class="input-group mb-3">
    <input type="password" name="password_confirmation"
      class="form-control"
      placeholder="Konfirmasi Password Baru" required>

    <div class="input-group-append">
      <div class="input-group-text">
        <span class="fas fa-lock"></span>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-warning btn-block font-weight-bold">
    Simpan & Masuk ke Dashboard
  </button>

</form>

@endsection