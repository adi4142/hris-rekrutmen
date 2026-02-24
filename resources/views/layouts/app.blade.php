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
  @stack('styles')
</head>
<body>
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('applicant/dashboard') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('applicant.profile') }}" class="nav-link">Profil</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> {{ Auth::user()->name ?? 'Guest' }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Keluar
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper text-sm">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
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

{{-- Chatbot Widget HTML --}}
<div class="chat-widget minimized" id="chatWidget">
    <div class="chat-header" id="chatHeader">
        <span><strong>HR Assistant</strong></span>
        <span id="chatToggleBtn" style="cursor: pointer;">_</span>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="message bot">Halo! 👋 Saya adalah HR Assistant. Saya siap membantu Anda dengan informasi seputar lowongan, status lamaran, dan pertanyaan umum lainnya.</div>
    </div>
    <div class="chat-footer">
        <div class="input-group">
            <input type="text" id="chatInput" class="form-control form-control-sm" placeholder="Ketik pesan..." autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" id="sendChatBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
        <div class="quick-replies" id="quickReplies"></div>
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
            const response = await fetch('{{ route("applicant.chat") }}', {
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
