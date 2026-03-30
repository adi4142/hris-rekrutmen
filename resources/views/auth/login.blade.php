@extends('layouts.auth')

@section('title', 'Masuk')
@section('subtitle', 'Login untuk mengakses panel HRD & Super Admin')

@section('content')

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="error-list">
      @foreach($errors->all() as $error)
        <li><i class="fas fa-times-circle mr-1"></i> {{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('login') }}" method="POST">
  @csrf

  <div class="form-group">
    <label>Alamat Email</label>
    <input type="email" name="email"
      class="form-control @error('email') is-invalid @enderror"
      placeholder="nama@perusahaan.com"
      value="{{ old('email') }}" required autofocus>
    @error('email')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  <div class="form-group">
    <label>Kata Sandi</label>
    <input type="password" name="password"
      class="form-control @error('password') is-invalid @enderror"
      placeholder="••••••••" required>
    @error('password')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>

  <button type="submit" class="btn-submit" style="margin-top:8px">
    Masuk ke Dashboard
  </button>
</form>

<div class="auth-divider"><span>atau</span></div>

<div class="auth-links">
  <a href="{{ route('password.forgot') }}">Lupa kata sandi?</a>
</div>

@endsection
