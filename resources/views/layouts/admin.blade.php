<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <title>{{ config('app.name', 'HRIS') }} | @yield('title', 'Admin')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php
        $userRole = auth()->user()->role ? str_replace(' ', '', strtolower(auth()->user()->role->name)) : '';
        
        switch ($userRole) {
            case 'superadmin':
                $dashboardRoute = 'superadmin.dashboard';
                break;
            case 'admin':
                $dashboardRoute = 'admin.dashboard';
                break;
            case 'hrd':
                $dashboardRoute = 'hrd.dashboard';
                break;
            default:
                $dashboardRoute = 'dashboard';
                break;
        }
    @endphp
    <a href="{{ route($dashboardRoute) }}" class="brand-link">
      <img src="{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">HRIS System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name ?? 'Guest' }}</a>
          <small class="text-info">{{ ucfirst($userRole) }}</small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route($dashboardRoute) }}" class="nav-link {{ request()->is('superadmin/dashboard') || request()->is('admin/dashboard') || request()->is('hrd/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
@if($userRole == 'hrd' || $userRole == 'admin' || $userRole == 'superadmin')
          <li class="nav-header">REKRUTMEN</li>
          <li class="nav-item">
            <a href="{{ route('jobvacancie.index') }}" class="nav-link {{ request()->is('jobvacancie*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>Lowongan Kerja</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jobapplicant.index') }}" class="nav-link {{ request()->is('jobapplicant*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen Pendaftar</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jobapplication.index') }}" class="nav-link {{ request()->is('jobapplication*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Lamaran Masuk</p>
            </a>
          </li>
@endif

@if($userRole == 'superadmin')
          <li class="nav-header">CONTROL PANEL</li>
          <li class="nav-item">
            <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->is('superadmin/users*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Role & User Management</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.settings') }}" class="nav-link {{ request()->is('superadmin/settings*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Sistem Settings</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.logs') }}" class="nav-link {{ request()->is('superadmin/logs*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-history"></i>
              <p>Audit Logs</p>
            </a>
          </li>
@endif

@if($userRole == 'hrd' || $userRole == 'admin' || $userRole == 'superadmin')
          <li class="nav-header">Master Data</li>
          <li class="nav-item">
            <a href="{{ route('selection.index') }}" class="nav-link {{ request()->is('selection*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Proses Seleksi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('division.index') }}" class="nav-link {{ request()->is('division*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Divisi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('departement.index') }}" class="nav-link {{ request()->is('departement*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Departemen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('position.index') }}" class="nav-link {{ request()->is('position*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Jabatan</p>
            </a>
          </li>
@endif          
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
            <h1 class="m-0"></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">@yield('page_title')</h3>
            <div class="card-tools">
                @yield('card_tools')
            </div>
          </div>
          <div class="card-body">
            @yield('content')
          </div>
        </div>
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

        {{-- Chatbot Widget HTML --}}
<div class="chat-widget minimized" id="chatWidget">
    <div class="chat-toggle-btn" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </div>
    
    <div class="chat-header" onclick="toggleChat()">
        <span><i class="fas fa-robot mr-2"></i> HR Assistant</span>
        <i class="fas fa-times"></i>
    </div>
    
    <div class="chat-body" id="chatBody">
        <div class="message bot">
          @php
            $user = Auth::user();
          @endphp
            Halo {{ $user->name }}! 👋<br><br>
            Saya HR Assistant, siap membantu Anda.<br><br>
            <div class="quick-replies">
                <button class="quick-reply-btn" onclick="quickReply('status lamaran')">Status Lamaran</button>
                <button class="quick-reply-btn" onclick="quickReply('lowongan kerja')">Lowongan</button>
                <button class="quick-reply-btn" onclick="quickReply('cara melamar')">Cara Melamar</button>
            </div>
        </div>
    </div>
    
    <div class="chat-footer">
        <div class="input-group">
            <input type="text" id="chatInput" class="form-control" placeholder="Ketik pertanyaan..." onkeypress="handleKeyPress(event)">
            <div class="input-group-append">
                <button class="btn btn-primary" onclick="sendChatMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    // Toggle chatbot visibility
    function toggleChat() {
        const widget = document.getElementById('chatWidget');
        widget.classList.toggle('minimized');
        if (!widget.classList.contains('minimized')) {
            document.getElementById('chatInput').focus();
        }
    }

    // Handle Enter key press
    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            sendChatMessage();
        }
    }

    // Quick reply button
    function quickReply(message) {
        document.getElementById('chatInput').value = message;
        sendChatMessage();
    }

    // Send message to chatbot
    async function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        // Append user message
        appendChatMessage('user', message);
        input.value = '';

        // Show typing indicator
        const typingId = 'typing-' + Date.now();
        appendChatMessage('bot', '<div class="typing-indicator"><span></span><span></span><span></span></div>', typingId);

        try {
            const response = await fetch('{{ route("hrd.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Replace typing indicator with actual response
            const typingEl = document.getElementById(typingId);
            if (typingEl) {
                typingEl.innerHTML = data.reply;
            }
        } catch (error) {
            const typingEl = document.getElementById(typingId);
            if (typingEl) {
                typingEl.innerHTML = 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.';
            }
        }
    }

    // Append message to chat body
    function appendChatMessage(role, text, id = null) {
        const chatBody = document.getElementById('chatBody');
        const div = document.createElement('div');
        div.className = 'message ' + role;
        if (id) div.id = id;
        div.innerHTML = text;
        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
</script>

<!-- Chatbot Toggle Button (Visible when minimized) -->
<div class="chat-toggle-btn" id="chatToggleBtnMinimized">
    <i class="fas fa-comments"></i>
</div>

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
