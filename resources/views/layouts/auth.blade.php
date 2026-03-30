<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <title>{{ config('app.name', 'HRIS') }} — @yield('title')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
      height: 100%;
      font-family: 'Inter', sans-serif;
      background: #0a0a0a;
      color: #f5f5f5;
      -webkit-font-smoothing: antialiased;
    }

    body {
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* ── LEFT PANEL ────────────────────────── */
    .auth-left {
      background: #111;
      border-right: 1px solid rgba(255,255,255,0.06);
      display: flex; flex-direction: column;
      padding: 48px;
      position: relative; overflow: hidden;
    }

    .auth-left::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 20% 30%, rgba(255,255,255,0.03) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 80% 70%, rgba(255,255,255,0.02) 0%, transparent 70%);
      pointer-events: none;
    }

    .auth-left-brand {
      display: flex; align-items: center; gap: 10px;
      position: relative; z-index: 1;
    }

    .brand-icon-box {
      width: 32px; height: 32px;
      background: #fff; border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: #000;
    }

    .brand-name { font-size: 14px; font-weight: 700; color: #f5f5f5; }

    .auth-left-body {
      flex: 1; display: flex; flex-direction: column; justify-content: center;
      position: relative; z-index: 1;
      max-width: 420px;
    }

    .auth-tagline {
      font-size: clamp(28px, 3vw, 42px);
      font-weight: 800; line-height: 1.15;
      letter-spacing: -1.2px; color: #f5f5f5;
      margin-bottom: 16px;
    }

    .auth-tagline span { color: rgba(255,255,255,0.35); }

    .auth-desc { font-size: 15px; color: rgba(255,255,255,0.4); line-height: 1.7; }

    .auth-left-footer { font-size: 12px; color: rgba(255,255,255,0.2); position: relative; z-index: 1; }

    /* Decorative grid lines */
    .auth-grid-lines {
      position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
      background-size: 48px 48px;
    }

    /* ── RIGHT PANEL ───────────────────────── */
    .auth-right {
      background: #0a0a0a;
      display: flex; align-items: center; justify-content: center;
      padding: 48px 40px;
    }

    .auth-box {
      width: 100%; max-width: 380px;
    }

    .auth-back {
      display: inline-flex; align-items: center; gap: 7px;
      color: rgba(255,255,255,0.35); font-size: 13px; font-weight: 500;
      text-decoration: none; margin-bottom: 36px;
      transition: color 0.15s;
    }
    .auth-back:hover { color: rgba(255,255,255,0.7); }
    .auth-back i { font-size: 11px; }

    .auth-heading { font-size: 22px; font-weight: 700; color: #f5f5f5; letter-spacing: -0.5px; margin-bottom: 6px; }
    .auth-subheading { font-size: 13.5px; color: rgba(255,255,255,0.4); margin-bottom: 28px; }

    /* Form */
    .form-group { margin-bottom: 16px; }

    label {
      display: block; font-size: 12.5px; font-weight: 500;
      color: rgba(255,255,255,0.5); margin-bottom: 6px;
    }

    .form-control {
      width: 100%;
      background: #161616 !important;
      border: 1px solid rgba(255,255,255,0.1) !important;
      color: #f5f5f5 !important;
      border-radius: 8px;
      font-size: 14px; font-family: 'Inter', sans-serif;
      padding: 10px 14px; height: auto;
      transition: border-color 0.15s, box-shadow 0.15s;
      outline: none;
    }
    .form-control:focus {
      border-color: rgba(255,255,255,0.35) !important;
      box-shadow: 0 0 0 3px rgba(255,255,255,0.05) !important;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.2) !important; }
    .form-control.is-invalid { border-color: #ef4444 !important; }

    .invalid-feedback { color: #f87171; font-size: 12px; margin-top: 5px; display: block; }

    .input-group { position: relative; margin-bottom: 0; }
    .input-group .form-control { padding-right: 40px; }
    .input-group-append { display: none; }

    /* Submit button */
    .btn-submit {
      width: 100%; padding: 11px;
      background: #fff; color: #0a0a0a;
      border: none; border-radius: 8px;
      font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif;
      cursor: pointer; letter-spacing: 0.3px;
      transition: all 0.15s; margin-top: 4px;
    }
    .btn-submit:hover { background: #e5e5e5; transform: translateY(-1px); }
    .btn-submit:active { transform: none; }

    /* Links */
    a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.15s; }
    a:hover { color: rgba(255,255,255,0.85); }

    .auth-links { margin-top: 20px; text-align: center; }
    .auth-links a { font-size: 13px; }

    .auth-divider {
      display: flex; align-items: center; gap: 12px;
      margin: 20px 0;
    }
    .auth-divider::before, .auth-divider::after {
      content: ''; flex: 1; height: 1px;
      background: rgba(255,255,255,0.08);
    }
    .auth-divider span { font-size: 12px; color: rgba(255,255,255,0.2); }

    /* Alerts */
    .alert {
      border: none; border-radius: 8px;
      font-size: 13px; font-weight: 500; padding: 12px 14px;
      margin-bottom: 20px;
    }
    .alert-success { background: rgba(34,197,94,.12); color: #4ade80; }
    .alert-danger  { background: rgba(239,68,68,.12);  color: #f87171; }
    .alert-warning { background: rgba(245,158,11,.12); color: #fbbf24; }
    .alert-info    { background: rgba(56,189,248,.12); color: #7dd3fc; }
    .alert .close  { color: currentColor !important; opacity: 0.6; font-size: 16px; background: none; border: none; cursor: pointer; float: right; }
    .alert-dismissible { padding-right: 36px; }

    /* Session error list */
    .error-list { list-style: none; }
    .error-list li { margin-bottom: 4px; }

    /* ── RESPONSIVE ─────────────────────────── */
    @media (max-width: 768px) {
      body { grid-template-columns: 1fr; }
      .auth-left { display: none; }
      .auth-right { padding: 32px 24px; align-items: flex-start; padding-top: 64px; }
    }
  </style>
</head>

<body>
  <!-- Left decorative panel -->
  <div class="auth-left">
    <div class="auth-grid-lines"></div>

    <div class="auth-left-brand">
      <div class="brand-icon-box">VN</div>
      <span class="brand-name">HRIS System</span>
    </div>

    <div class="auth-left-body">
      <h1 class="auth-tagline">
        Sistem Rekrutmen<br>
        <span>Terstruktur &amp;</span><br>
        Transparan.
      </h1>
      <p class="auth-desc">
        Platform manajemen rekrutmen untuk HRD dan Super Admin PT Vneu Teknologi Indonesia.
      </p>
    </div>

    <div class="auth-left-footer">
      &copy; {{ date('Y') }} PT Vneu Teknologi Indonesia
    </div>
  </div>

  <!-- Right form panel -->
  <div class="auth-right">
    <div class="auth-box">

      <a href="{{ route('home') }}" class="auth-back">
        <i class="fas fa-arrow-left"></i> Kembali ke beranda
      </a>

      <h2 class="auth-heading">@yield('title', 'Masuk')</h2>
      <p class="auth-subheading">@yield('subtitle', 'Masuk ke panel HRIS Anda')</p>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
          <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
          <button class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      @if(session('verification_sent'))
        <div class="alert alert-warning">
          <i class="fas fa-envelope mr-1"></i> {{ session('verification_sent') }}
        </div>
      @endif

      @yield('content')

    </div>
  </div>
</body>

<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  // dismiss alerts
  $(document).on('click', '.close[data-dismiss="alert"]', function() {
    $(this).closest('.alert').fadeOut(200, function(){ $(this).remove(); });
  });
</script>
</html>
