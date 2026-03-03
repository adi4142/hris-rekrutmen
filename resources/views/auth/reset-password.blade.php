@extends('layouts.auth')

@section('card-class', 'card-success')

@section('title', 'Reset Password')

@section('hide')

@section('content')
<div class="login-box">

      <div class="text-center mb-3">
        <span class="fas fa-lock fa-3x text-success"></span>
      </div>
      <p class="login-box-msg">
        <strong>Buat Password Baru</strong><br>
        <small class="text-muted">Email Anda sudah terverifikasi. Silakan masukkan password baru.</small>
      </p>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle mr-1"></i>
          @foreach($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif

      <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="input-group mb-3">
          <input type="email" class="form-control" value="{{ $email }}" disabled>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" 
                 class="form-control @error('password') is-invalid @enderror" 
                 placeholder="Password Baru (min. 8 karakter)" 
                 required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" 
                 class="form-control" 
                 placeholder="Konfirmasi Password Baru" 
                 required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">
              <i class="fas fa-save mr-1"></i> Simpan Password Baru
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
