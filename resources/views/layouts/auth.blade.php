<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <title>{{ config('app.name', 'HRIS') }} | @yield('title')</title>


  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
  <style>
    .login-box, .register-box {
      width: 400px;
    }
    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
  </style>
</head>

<body class="hold-transition login-page">

  <div class="card card-outline @yield('card-class')">
    <div class="card-header text-center">
      <span class="h1"><b>@yield('title')</b></span>
    <div class="card-tools d-flex justify-content-between align-items-center">
        <a href="javascript:history.back()" 
           class="btn btn-light btn-sm">
           <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    </div>
    <div class="card-body">
      @yield('content')
    </div>

  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
</body>
</html>