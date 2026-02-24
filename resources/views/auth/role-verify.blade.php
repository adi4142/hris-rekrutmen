@extends('layouts.auth')

@section('title', 'Verifikasi Role')

@section('content')
<div class="login-box">
  <div class="card card-outline card-warning">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>HRIS</b> System</a>
    </div>
    <div class="card-body">
      <div class="text-center mb-3">
        <span class="fas fa-shield-alt fa-3x text-warning"></span>
      </div>
      <p class="login-box-msg">
        <strong>Verifikasi Lisensi Diperlukan</strong><br>
        <small class="text-muted">
          Akun Anda terdaftar sebagai <span class="badge badge-{{ $roleName === 'admin' ? 'danger' : 'info' }}">{{ ucfirst($roleName) }}</span>. 
          Silakan masukkan <strong>Kode Lisensi</strong> khusus untuk role ini agar dapat mengakses dashboard.
        </small>
      </p>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif

      <form action="{{ route('role.verify') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="verification_code" 
                 class="form-control @error('verification_code') is-invalid @enderror" 
                 placeholder="Masukkan Kode Lisensi" 
                 required autofocus autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
          @error('verification_code')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-warning btn-block">
              <i class="fas fa-check mr-1"></i> Verifikasi Kode
            </button>
          </div>
        </div>
      </form>

      <hr>
      <div class="text-center">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout & Kembali
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
