<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <title>{{ config('app.name', 'HRIS') }} | @yield('title', 'Pelamar')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
  @stack('styles')
  <style>
    /* Chatbot Widget Styles */
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px;
        max-height: 500px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 25px rgba(0,0,0,0.25);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .chat-widget.minimized {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #3f25e6 100%);
        color: white;
        padding: 12px 15px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-body {
        background: white;
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
        height: 320px;
    }
    
    .chat-footer {
        padding: 10px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .message {
        margin-bottom: 12px;
        padding: 10px 14px;
        border-radius: 18px;
        max-width: 85%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .message.user {
        background: linear-gradient(135deg, #667eea 0%, #3f25e6 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }
    
    .message.bot {
        background: #f1f3f4;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }
    
    .chat-toggle-btn {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #3f25e6 100%);
        color: white;
        border-radius: 50%;
        display: none;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: transform 0.2s ease;
    }
    
    .chat-toggle-btn:hover {
        transform: scale(1.1);
    }
    
    .chat-widget.minimized .chat-header,
    .chat-widget.minimized .chat-body,
    .chat-widget.minimized .chat-footer {
        display: none;
    }
    
    .chat-widget.minimized .chat-toggle-btn {
        display: flex;
    }
    
    .typing-indicator {
        display: flex;
        gap: 4px;
    }
    
    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    
    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    
    /* Quick Reply Buttons */
    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 8px;
    }
    
    .quick-reply-btn {
        background: #667eea;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .quick-reply-btn:hover {
        background: #5a6fd6;
    }
</style>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> {{ auth()->user()->name ?? 'Guest' }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="{{ route('applicant.profile') }}" class="dropdown-item">
            <i class="fas fa-user-edit mr-2"></i> Profil Saya
          </a>
          <div class="dropdown-divider"></div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('applicant.dashboard') }}" class="brand-link">
      <img src="{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Portal Pelamar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('applicant.profile') }}" class="d-block">{{ auth()->user()->name ?? 'Guest' }}</a>
          <small class="text-info">Pelamar</small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('applicant.dashboard') }}" class="nav-link {{ request()->is('applicant/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-header">LAMARAN</li>
          <li class="nav-item">
            <a href="{{ route('applicant.vacancies') }}" class="nav-link {{ request()->is('applicant/vacancies*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>Lowongan Tersedia</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('applicant.applications') }}" class="nav-link {{ request()->is('applicant/applications*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Riwayat Lamaran</p>
            </a>
          </li>
          <li class="nav-header">AKUN</li>
          <li class="nav-item">
            <a href="{{ route('applicant.profile') }}" class="nav-link {{ request()->is('applicant/profile*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-edit"></i>
              <p>Profil Saya</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('applicant.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">@yield('page_title', 'Dashboard')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">HRIS System</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
