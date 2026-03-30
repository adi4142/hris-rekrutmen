@extends('layouts.auth')

@section('card-class', 'card-info')

@section('title', 'Lupa Password')

@section('back_url', 'login')

@section('content')
<div class="login-box">
      <div class="text-center mb-3">
        <span class="fas fa-unlock-alt fa-3x text-info"></span>
      </div>
      <p class="login-box-msg">
        <strong>Lupa Password?</strong><br>
        <small class="text-muted">Masukkan email Anda. Kami akan mengirim link verifikasi untuk mengatur ulang password.</small>
      </p>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif

      @if(session('dev_link'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="fas fa-code mr-1"></i> <strong>Dev Mode:</strong> 
          <a href="{{ session('dev_link') }}" class="alert-link">Klik di sini untuk verifikasi</a>
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

      <form action="{{ route('password.send.link') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" 
                 class="form-control @error('email') is-invalid @enderror" 
                 placeholder="Masukkan Email Anda" 
                 value="{{ old('email') }}" 
                 required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-info btn-block">
              <i class="fas fa-paper-plane mr-1"></i> Kirim Link Verifikasi
            </button>
          </div>
        </div>
      </form>
    </div>
    </div>
@endsection
