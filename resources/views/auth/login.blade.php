@extends('layouts.auth')

@section('card-class', 'card-primary')

@section('title', 'Login')


@section('content')
<div class="login-box">
        <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name') }}" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-block btn-primary">Masuk</button> 
          </div>

          <!-- /.col -->
        </div>          
      </form>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif

      <div class="social-auth-links text-center mt-2 mb-3">
        <hr>
      </div>
<div class="row">
  <div class="col-6">
      <p class="mb-1 mt-3 text-left">
        <a href="{{ route('password.forgot') }}">Lupa Password</a>
      </p>
  </div>
  <div class="col-6">
      <p class="mb-1 mt-3 text-right">
        <a href="{{ route('register') }}" class="text-center">Belum punya akun?
      </p>
</div>
</div>
</div>


@endsection
