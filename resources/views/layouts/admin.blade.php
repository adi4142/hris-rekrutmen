<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <title>{{ config('app.name', 'HRIS') }} — @yield('title', 'Dashboard')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css') }}">

  @stack('styles')

  <style>
    :root {
      --sidebar-w:   240px;
      --topbar-h:    56px;

      /* Surface - biru putih */
      --bg-page:     #f0f4f8;
      --bg-sidebar:  #ffffff;
      --bg-surface:  #ffffff;
      --bg-card:     #ffffff;
      --bg-elevated: #f8fafc;
      --bg-hover:    rgba(37, 99, 235, 0.08);

      /* Border */
      --border:      #e2e8f0;
      --border-md:   #cbd5e1;
      --border-focus:#3b82f6;

      /* Text */
      --tx-primary:  #1e293b;
      --tx-secondary:#475569;
      --tx-muted:    #94a3b8;

      /* Accent - biru */
      --accent:      #3b82f6;
      --accent-dim:  rgba(59, 130, 246, 0.1);
      --accent-glow: rgba(59, 130, 246, 0.3);

      /* Semantic */
      --success:  #22c55e;
      --warning:  #f59e0b;
      --danger:   #ef4444;
      --info:     #3b82f6;

      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 14px;
    }

    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      font-family: 'Inter', sans-serif;
      background: var(--bg-page);
      color: var(--tx-primary);
      font-size: 13.5px;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    ::-webkit-scrollbar            { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track      { background: transparent; }
    ::-webkit-scrollbar-thumb      { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover{ background: #94a3b8; }

    /* ─── SIDEBAR ─────────────────────────────── */
    .main-sidebar {
      background: var(--bg-sidebar) !important;
      border-right: 1px solid var(--border);
      width: var(--sidebar-w) !important;
      position: fixed; top: 0; bottom: 0; left: 0;
      z-index: 1000;
      display: flex; flex-direction: column;
      transition: transform 0.28s cubic-bezier(.4,0,.2,1), width 0.28s;
    }

    /* Brand */
    .brand-link {
      display: flex; align-items: center; gap: 11px;
      padding: 0 18px !important;
      height: var(--topbar-h);
      border-bottom: 1px solid var(--border);
      text-decoration: none !important;
      flex-shrink: 0;
    }

    .brand-icon {
      width: 30px; height: 30px;
      background: var(--accent);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: #fff;
      flex-shrink: 0; letter-spacing: -0.5px;
      transition: opacity 0.15s;
    }
    .brand-link:hover .brand-icon { opacity: 0.85; }

    .brand-text-main { font-size: 14px; font-weight: 700; color: var(--tx-primary); letter-spacing: -0.3px; }
    .brand-text-sub  { font-size: 10.5px; color: var(--tx-muted); letter-spacing: 0.5px; }

    /* User panel */
    .sidebar-user {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 18px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }

    .sidebar-user-avatar {
      width: 32px; height: 32px;
      background: var(--accent-dim);
      border: 1px solid var(--border);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 700; color: var(--accent);
      flex-shrink: 0;
    }

    .sidebar-user-name  { font-size: 12.5px; font-weight: 600; color: var(--tx-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
    .sidebar-user-role  { font-size: 10px; color: var(--tx-muted); letter-spacing: 0.5px; text-transform: uppercase; }

    /* Nav */
    .sidebar { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px 0 16px; }

    .nav-section-label {
      padding: 12px 18px 3px;
      font-size: 9.5px; font-weight: 700; color: var(--tx-muted);
      letter-spacing: 1.4px; text-transform: uppercase;
    }

    .nav-sidebar .nav-item { padding: 1px 8px; }

    .nav-sidebar .nav-link {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 10px !important;
      border-radius: var(--radius-sm);
      color: var(--tx-secondary) !important;
      font-size: 13px; font-weight: 500;
      text-decoration: none;
      transition: all 0.15s;
      position: relative;
    }

    .nav-sidebar .nav-link i {
      width: 16px; text-align: center; font-size: 12px;
      color: var(--tx-muted); transition: color 0.15s; flex-shrink: 0;
    }

    .nav-sidebar .nav-link p { margin: 0; }

    .nav-sidebar .nav-link:hover { background: var(--bg-hover) !important; color: var(--tx-primary) !important; }
    .nav-sidebar .nav-link:hover i { color: var(--tx-secondary); }

    .nav-sidebar .nav-link.active {
      background: var(--accent-dim) !important;
      color: var(--accent) !important;
    }
    .nav-sidebar .nav-link.active i { color: var(--accent); }
    .nav-sidebar .nav-link.active::before {
      content: '';
      position: absolute; left: 0; top: 50%; transform: translateY(-50%);
      width: 2px; height: 16px;
      background: var(--accent);
      border-radius: 0 2px 2px 0;
      margin-left: -8px;
    }

    /* Logout */
    .sidebar-logout { padding: 8px 8px 12px; border-top: 1px solid var(--border); flex-shrink: 0; }

    .btn-logout {
      display: flex; align-items: center; gap: 9px;
      width: 100%; padding: 8px 10px;
      background: none; border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--tx-secondary); font-size: 13px; font-weight: 500;
      cursor: pointer; transition: all 0.15s;
    }
    .btn-logout:hover { background: rgba(239,68,68,.08); border-color: rgba(239,68,68,.2); color: var(--danger); }
    .btn-logout i { font-size: 12px; }

    /* ─── TOPBAR ────────────────────────────────── */
    .main-header {
      position: fixed; top: 0;
      left: var(--sidebar-w); right: 0;
      height: var(--topbar-h);
      background: var(--bg-surface);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 22px; z-index: 900; gap: 14px;
    }

    .topbar-toggle {
      display: none; width: 34px; height: 34px;
      align-items: center; justify-content: center;
      background: none; border: 1px solid var(--border);
      border-radius: var(--radius-sm); color: var(--tx-secondary);
      cursor: pointer; font-size: 14px; flex-shrink: 0;
      transition: all 0.15s;
    }
    .topbar-toggle:hover { background: var(--bg-hover); color: var(--tx-primary); }

    .topbar-breadcrumb { display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0; }
    .breadcrumb-item { font-size: 12.5px; color: var(--tx-muted); white-space: nowrap; }
    .breadcrumb-item.active { color: var(--tx-primary); font-weight: 500; }
    .breadcrumb-sep { color: var(--tx-muted); font-size: 11px; }

    .topbar-actions { display: flex; align-items: center; gap: 8px; margin-left: auto; }

    .topbar-avatar {
      width: 30px; height: 30px;
      background: var(--accent-dim); border: 1px solid var(--border);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 700; color: var(--accent); cursor: pointer;
    }

    /* ─── CONTENT WRAPPER ─────────────────────── */
    .content-wrapper {
      margin-left: var(--sidebar-w) !important;
      margin-top: var(--topbar-h) !important;
      background: var(--bg-page) !important;
      min-height: calc(100vh - var(--topbar-h));
      padding: 24px 24px 60px;
    }

    /* Page header */
    .page-header {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
    }
    .page-header-left h1 {
      font-size: 20px; font-weight: 700; color: var(--tx-primary);
      margin: 0; letter-spacing: -0.4px;
    }
    .page-header-left p { font-size: 12.5px; color: var(--tx-secondary); margin: 3px 0 0; }

    /* ─── CARDS ─────────────────────────────────── */
    .card {
      background: var(--bg-card) !important;
      border: 1px solid var(--border) !important;
      border-radius: var(--radius-md) !important;
      box-shadow: none !important;
    }

    .card-header {
      background: transparent !important;
      border-bottom: 1px solid var(--border) !important;
      padding: 14px 18px !important;
    }

    .card-title {
      font-size: 13.5px !important; font-weight: 600 !important;
      color: var(--tx-primary) !important;
      margin: 0 !important;
    }

    .card-body { padding: 18px !important; }
    .card-footer { background: transparent !important; border-top: 1px solid var(--border) !important; padding: 12px 18px !important; }
    .card-primary.card-outline { border-top: none !important; }

    /* ─── TABLES ───────────────────────────────── */
    .table { color: var(--tx-secondary) !important; margin: 0; }

    .table thead th {
      background: var(--bg-elevated) !important;
      color: var(--tx-muted) !important;
      font-size: 10px !important; font-weight: 700 !important;
      text-transform: uppercase; letter-spacing: 0.1em;
      border: none !important; padding: 10px 14px !important;
      white-space: nowrap;
    }

    .table td {
      border-color: var(--border) !important;
      padding: 11px 14px !important; vertical-align: middle;
    }

    .table tbody tr { transition: background 0.12s; }
    .table tbody tr:hover td { background: var(--bg-hover) !important; color: var(--tx-primary); }
    .table-striped tbody tr:nth-of-type(even) td { background: rgba(255,255,255,.015) !important; }
    .table-hover tbody tr:hover td { background: var(--bg-hover) !important; }

    /* ─── BUTTONS ──────────────────────────────── */
    .btn {
      font-family: 'Inter', sans-serif;
      font-weight: 500; font-size: 13px;
      border-radius: var(--radius-sm);
      padding: 7px 14px;
      transition: all 0.15s; border: none;
      display: inline-flex; align-items: center; gap: 6px;
    }

    /* Primary = white button */
    .btn-primary {
      background: var(--accent) !important; color: #fff !important;
    }
    .btn-primary:hover { background: #2563eb !important; transform: translateY(-1px); }
    .btn-primary:focus { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important; }

    .btn-success { background: var(--success) !important; color: #fff !important; }
    .btn-success:hover { background: #16a34a !important; }

    .btn-danger { background: var(--danger) !important; color: #fff !important; }
    .btn-danger:hover { background: #dc2626 !important; }

    .btn-warning { background: var(--warning) !important; color: #000 !important; }
    .btn-warning:hover { background: #d97706 !important; }

    .btn-info { background: var(--info) !important; color: #0a0a0a !important; }

    .btn-secondary, .btn-default {
      background: var(--bg-elevated) !important;
      border: 1px solid var(--border) !important;
      color: var(--tx-secondary) !important;
    }
    .btn-secondary:hover, .btn-default:hover { color: var(--tx-primary) !important; background: #252525 !important; }

    .btn-dark { background: #222 !important; border: 1px solid var(--border-md) !important; color: var(--tx-primary) !important; }

    .btn-outline-primary {
      background: transparent !important;
      border: 1px solid var(--border-md) !important;
      color: var(--tx-primary) !important;
    }
    .btn-outline-primary:hover { background: var(--bg-hover) !important; border-color: var(--border-focus) !important; }

    .btn-sm  { padding: 5px 11px !important; font-size: 12px !important; }
    .btn-xs  { padding: 3px 8px  !important; font-size: 11px !important; }
    .btn-lg  { padding: 10px 20px !important; font-size: 14px !important; }

    /* ─── BADGES ───────────────────────────────── */
    .badge {
      font-size: 10.5px !important; font-weight: 600 !important;
      padding: 3px 8px !important; border-radius: 4px !important;
      letter-spacing: 0.02em;
    }
    .badge-success   { background: rgba(34,197,94,.12) !important;  color: #4ade80 !important; }
    .badge-danger    { background: rgba(239,68,68,.12) !important;   color: #f87171 !important; }
    .badge-warning   { background: rgba(245,158,11,.12) !important;  color: #fbbf24 !important; }
    .badge-info      { background: rgba(56,189,248,.12) !important;  color: #7dd3fc !important; }
    .badge-primary   { background: rgba(255,255,255,.08) !important; color: var(--tx-primary) !important; }
    .badge-secondary { background: rgba(255,255,255,.06) !important; color: var(--tx-secondary) !important; }
    .badge-dark      { background: #1c1c1c !important; color: var(--tx-secondary) !important; border: 1px solid var(--border); }

    /* ─── FORMS ────────────────────────────────── */
    .form-control, .custom-select {
      background: var(--bg-elevated) !important;
      border: 1px solid var(--border) !important;
      color: var(--tx-primary) !important;
      border-radius: var(--radius-sm);
      font-size: 13.5px; font-family: 'Inter', sans-serif;
      padding: 8px 12px; height: auto;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .form-control:focus, .custom-select:focus {
      border-color: var(--border-focus) !important;
      box-shadow: 0 0 0 3px rgba(255,255,255,.06) !important;
      background: var(--bg-elevated) !important;
      color: var(--tx-primary) !important;
    }
    .form-control::placeholder { color: var(--tx-muted) !important; }
    .form-control.is-invalid { border-color: var(--danger) !important; }

    label { color: var(--tx-secondary); font-weight: 500; font-size: 12.5px; margin-bottom: 5px; display: block; }

    .input-group-text {
      background: var(--bg-elevated) !important;
      border-color: var(--border) !important;
      color: var(--tx-muted) !important;
    }

    select option { background: #1c1c1c; color: var(--tx-primary); }

    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
      background-color: var(--success) !important; border-color: var(--success) !important;
    }
    .custom-control-label::before { background-color: var(--bg-elevated) !important; border-color: var(--border-md) !important; }

    /* ─── ALERTS ───────────────────────────────── */
    .alert { border: none; border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 500; }
    .alert-success { background: rgba(34,197,94,.1);   color: #4ade80; }
    .alert-danger  { background: rgba(239,68,68,.1);   color: #f87171; }
    .alert-warning { background: rgba(245,158,11,.1);  color: #fbbf24; }
    .alert-info    { background: rgba(56,189,248,.1);  color: #7dd3fc; }
    .alert .close  { color: var(--tx-muted) !important; opacity: 1; }

    /* ─── PAGINATION ───────────────────────────── */
    .pagination .page-link {
      background: var(--bg-elevated); border-color: var(--border);
      color: var(--tx-secondary); border-radius: var(--radius-sm) !important;
      margin: 0 2px; font-size: 12.5px; padding: 5px 10px;
    }
    .pagination .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; }
    .pagination .page-link:hover { background: var(--bg-card); color: var(--tx-primary); }

    /* ─── MODALS ───────────────────────────────── */
    .modal-content { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); }
    .modal-header { border-bottom: 1px solid var(--border); padding: 16px 20px; }
    .modal-footer { border-top: 1px solid var(--border); padding: 12px 20px; }
    .modal-body { padding: 20px; }
    .modal-title { color: var(--tx-primary); font-weight: 600; font-size: 14px; }
    .modal-backdrop.show { opacity: 0.6; }
    .close { color: var(--tx-muted) !important; opacity: 1 !important; font-size: 20px; }
    .close:hover { color: var(--tx-primary) !important; }

    /* ─── DROPDOWNS ────────────────────────────── */
    .dropdown-menu { background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 6px; }
    .dropdown-item { color: var(--tx-secondary); border-radius: var(--radius-sm); font-size: 13px; padding: 8px 12px; }
    .dropdown-item:hover { background: var(--bg-hover); color: var(--tx-primary); }

    /* ─── TABS ──────────────────────────────────── */
    .nav-tabs { border-bottom: 1px solid var(--border); }
    .nav-tabs .nav-link { color: var(--tx-secondary); border: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; font-size: 13px; padding: 8px 16px; }
    .nav-tabs .nav-link:hover { background: var(--bg-hover); color: var(--tx-primary); }
    .nav-tabs .nav-link.active { background: var(--bg-card); color: var(--accent); border-bottom: 2px solid var(--accent); font-weight: 600; }

    /* ─── MISC ──────────────────────────────────── */
    .main-footer { display: none; }
    .content-header { display: none; }
    .elevation-4 { box-shadow: none !important; }
    .wrapper { background: transparent; }
    .text-muted { color: var(--tx-muted) !important; }
    hr { border-color: var(--border); }
    .border-bottom { border-bottom-color: var(--border) !important; }
    .bg-white { background: var(--bg-card) !important; }
    .bg-light { background: var(--bg-elevated) !important; }
    .shadow-sm { box-shadow: none !important; }

    .toast { border-radius: var(--radius-sm) !important; }

    /* ─── RESPONSIVE ────────────────────────────── */
    @media (max-width: 768px) {
      :root { --sidebar-w: 0px; }
      .main-sidebar { transform: translateX(-240px); width: 240px !important; }
      .main-sidebar.open { transform: translateX(0); box-shadow: 24px 0 60px rgba(0,0,0,.6); }
      .topbar-toggle { display: flex !important; }
      .content-wrapper { margin-left: 0 !important; padding: 16px 14px 60px; }
      .main-header { left: 0; padding: 0 14px; }
      .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 999; }
      .sidebar-overlay.show { display: block; }
    }

    @media (min-width: 769px) {
      .topbar-toggle { display: none !important; }
      .sidebar-overlay { display: none !important; }

      body.sidebar-collapsed .main-sidebar { width: 52px !important; }
      body.sidebar-collapsed .brand-text-main,
      body.sidebar-collapsed .brand-text-sub,
      body.sidebar-collapsed .sidebar-user-name,
      body.sidebar-collapsed .sidebar-user-role,
      body.sidebar-collapsed .nav-sidebar .nav-link p,
      body.sidebar-collapsed .nav-section-label,
      body.sidebar-collapsed .btn-logout span { display: none !important; }
      body.sidebar-collapsed .content-wrapper,
      body.sidebar-collapsed .main-header { margin-left: 52px !important; left: 52px; }
      body.sidebar-collapsed .nav-sidebar .nav-link { justify-content: center; padding: 9px !important; }
      body.sidebar-collapsed .nav-sidebar .nav-link i { width: auto; font-size: 14px; margin: 0; }
      body.sidebar-collapsed .sidebar { padding: 10px 4px; }
      body.sidebar-collapsed .nav-item { padding: 1px 4px; }
      body.sidebar-collapsed .brand-link { justify-content: center; padding: 0 !important; }
      body.sidebar-collapsed .sidebar-user { justify-content: center; padding: 10px; }
      body.sidebar-collapsed .sidebar-logout { padding: 8px 4px; }
      body.sidebar-collapsed .btn-logout { justify-content: center; padding: 9px; }
    }
  </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ═══ SIDEBAR ═══ -->
<aside class="main-sidebar" id="mainSidebar">
  @php
    $authUser   = auth()->user();
    $userRole   = $authUser && $authUser->role
        ? str_replace(' ', '', strtolower($authUser->role->name)) : '';
    $dashRoute  = $userRole === 'admin' ? 'admin.dashboard' : 'hrd.dashboard';
    $userInit   = strtoupper(substr($authUser->name ?? 'U', 0, 1));
  @endphp

  <a href="{{ route($dashRoute) }}" class="brand-link">
    <div class="brand-icon">VN</div>
    <div>
      <div class="brand-text-main">HRIS System</div>
      <div class="brand-text-sub">Rekrutmen</div>
    </div>
  </a>

  <div class="sidebar-user">
    <div class="sidebar-user-avatar">{{ $userInit }}</div>
    <div style="min-width:0">
      <div class="sidebar-user-name">{{ $authUser->name ?? 'Guest' }}</div>
      <div class="sidebar-user-role">{{ $userRole }}</div>
    </div>
  </div>

  <div class="sidebar">
    <nav>
      <ul class="nav nav-pills nav-sidebar flex-column" style="padding:0">

        <li class="nav-item">
          <a href="{{ route($dashRoute) }}" class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i><p>Dashboard</p>
          </a>
        </li>

        @if(in_array($userRole, ['admin', 'hrd']))
          <div class="nav-section-label">Rekrutmen</div>
          <li class="nav-item">
            <a href="{{ route('jobvacancie.index') }}" class="nav-link {{ request()->is('jobvacancie*') ? 'active' : '' }}">
              <i class="fas fa-briefcase"></i><p>Lowongan Kerja</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jobapplicant.index') }}" class="nav-link {{ request()->is('jobapplicant*') ? 'active' : '' }}">
              <i class="fas fa-user-friends"></i><p>Data Pelamar</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('recruitment-batch.index') }}" class="nav-link {{ request()->is('recruitment-batch*') ? 'active' : '' }}">
              <i class="fas fa-calendar-alt"></i><p>Batch Rekrutmen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jobapplication.manage') }}" class="nav-link {{ request()->is('jobapplication*') ? 'active' : '' }}">
              <i class="fas fa-tasks"></i><p>Proses Seleksi</p>
            </a>
          </li>
        @endif

        @if($userRole === 'admin')
          <div class="nav-section-label">Control Panel</div>
          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
              <i class="fas fa-users-cog"></i><p>Manajemen User</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->is('admin/logs*') ? 'active' : '' }}">
              <i class="fas fa-history"></i><p>Audit Log</p>
            </a>
          </li>

          <div class="nav-section-label">Master Data</div>
          <li class="nav-item">
            <a href="{{ route('selection.index') }}" class="nav-link {{ request()->is('selection*') ? 'active' : '' }}">
              <i class="fas fa-layer-group"></i><p>Tahap Seleksi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('departement.index') }}" class="nav-link {{ request()->is('departement*') ? 'active' : '' }}">
              <i class="fas fa-sitemap"></i><p>Departemen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('position.index') }}" class="nav-link {{ request()->is('position*') ? 'active' : '' }}">
              <i class="fas fa-user-tag"></i><p>Jabatan</p>
            </a>
          </li>
        @endif

      </ul>
    </nav>
  </div>

  <div class="sidebar-logout">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn-logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Keluar</span>
      </button>
    </form>
  </div>
</aside>

<!-- ═══ CONTENT ═══ -->
<div class="content-wrapper">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
      <i class="fas fa-check-circle mr-2" style="font-size:13px"></i>{{ session('success') }}
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4">
      <i class="fas fa-exclamation-circle mr-2" style="font-size:13px"></i>{{ session('error') }}
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
      <i class="fas fa-times-circle mr-2" style="font-size:13px"></i>
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  @endif

  @yield('content')

</div>

<!-- SCRIPTS -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js') }}"></script>

<script>
  function toggleSidebar() {
    if (window.innerWidth <= 768) {
      document.getElementById('mainSidebar').classList.toggle('open');
      document.getElementById('sidebarOverlay').classList.toggle('show');
    } else {
      document.body.classList.toggle('sidebar-collapsed');
      localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
    }
  }
  function closeSidebar() {
    document.getElementById('mainSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
  }
  if (window.innerWidth > 768 && localStorage.getItem('sidebarCollapsed') === 'true') {
    document.body.classList.add('sidebar-collapsed');
  }

  toastr.options = { positionClass:'toast-bottom-right', timeOut:4000, progressBar:true, closeButton:true };
  @if(session('success')) toastr.success('{{ addslashes(session("success")) }}'); @endif
  @if(session('error'))   toastr.error('{{ addslashes(session("error")) }}');   @endif
</script>

@stack('scripts')
</body>
</html>
