@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Ringkasan data sistem HRIS secara keseluruhan')

@push('styles')
<style>
  .admin-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 992px) { .admin-stat-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 480px)  { .admin-stat-grid { grid-template-columns: 1fr 1fr; } }

  .astat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px 20px;
    transition: transform 0.2s, border-color 0.2s;
    position: relative;
    overflow: hidden;
  }
  .astat:hover { transform: translateY(-2px); }
  .astat-bg-icon {
    position: absolute; right: 12px; bottom: 8px;
    font-size: 42px; opacity: 0.05;
  }
  .astat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
  .astat-icon {
    width: 36px; height: 36px;
    border-radius: 9px; display: flex; align-items: center; justify-content: center;
    font-size: 14px;
  }
  .astat-badge {
    font-size: 10.5px; font-weight: 600; padding: 3px 8px; border-radius: 20px;
    font-family: 'Space Mono', monospace;
  }
  .astat-value { font-size: 32px; font-weight: 700; color: var(--text-primary); line-height: 1; font-feature-settings: "tnum"; }
  .astat-label { font-size: 12.5px; color: var(--text-muted); margin-top: 6px; font-weight: 500; }

  .astat.v1 .astat-icon { background: rgba(99,102,241,0.15); color: #818cf8; }
  .astat.v2 .astat-icon { background: rgba(20,184,166,0.15);  color: #2dd4bf; }
  .astat.v3 .astat-icon { background: rgba(245,158,11,0.15);  color: #fbbf24; }
  .astat.v4 .astat-icon { background: rgba(244,63,94,0.15);   color: #fb7185; }

  .astat.v1 .astat-badge { background: rgba(99,102,241,0.15); color: #818cf8; }
  .astat.v2 .astat-badge { background: rgba(20,184,166,0.15);  color: #2dd4bf; }
  .astat.v3 .astat-badge { background: rgba(245,158,11,0.15);  color: #fbbf24; }
  .astat.v4 .astat-badge { background: rgba(244,63,94,0.15);   color: #fb7185; }

  .admin-grid2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  @media (max-width: 900px) { .admin-grid2 { grid-template-columns: 1fr; } }

  .funnel-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
  }
  .funnel-row:last-child { border-bottom: none; padding-bottom: 0; }
  .funnel-label { font-size: 13px; font-weight: 500; color: var(--text-secondary); min-width: 120px; }
  .funnel-bar-track { flex: 1; background: var(--bg-elevated); border-radius: 4px; height: 8px; overflow: hidden; }
  .funnel-bar-fill { height: 100%; border-radius: 4px; transition: width 1s cubic-bezier(0.4,0,0.2,1); }
  .funnel-count { font-size: 12.5px; font-weight: 700; color: var(--text-primary); min-width: 32px; text-align: right; font-feature-settings: "tnum"; }

  .shortcut-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
  }
  .shortcut-item {
    display: flex; align-items: center; gap: 10px;
    padding: 12px;
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: 9px; text-decoration: none;
    color: var(--text-secondary); font-size: 13px; font-weight: 500;
    transition: all 0.18s;
  }
  .shortcut-item:hover {
    background: var(--accent-dim); border-color: var(--accent-glow);
    color: var(--text-primary); text-decoration: none;
  }
  .shortcut-item i { font-size: 14px; color: var(--accent); width: 16px; text-align: center; }
</style>
@endpush

@section('content')

{{-- STAT CARDS --}}
<div class="admin-stat-grid">
  <div class="astat v1">
    <div class="astat-top">
      <div class="astat-icon"><i class="fas fa-user-friends"></i></div>
      <span class="astat-badge">Total</span>
    </div>
    <div class="astat-value">{{ $totalApplicants }}</div>
    <div class="astat-label">Pelamar Terdaftar</div>
    <i class="fas fa-user-friends astat-bg-icon"></i>
  </div>

  <div class="astat v2">
    <div class="astat-top">
      <div class="astat-icon"><i class="fas fa-briefcase"></i></div>
      <span class="astat-badge">Aktif</span>
    </div>
    <div class="astat-value">{{ $totalActiveVacancies }}</div>
    <div class="astat-label">Lowongan Dibuka</div>
    <i class="fas fa-briefcase astat-bg-icon"></i>
  </div>

  <div class="astat v3">
    <div class="astat-top">
      <div class="astat-icon"><i class="fas fa-file-alt"></i></div>
      <span class="astat-badge">Semua</span>
    </div>
    <div class="astat-value">{{ $totalApplications }}</div>
    <div class="astat-label">Total Lamaran</div>
    <i class="fas fa-file-alt astat-bg-icon"></i>
  </div>

  <div class="astat v4">
    <div class="astat-top">
      <div class="astat-icon"><i class="fas fa-users"></i></div>
      <span class="astat-badge">Sistem</span>
    </div>
    <div class="astat-value">{{ $totalUsers }}</div>
    <div class="astat-label">User Terdaftar</div>
    <i class="fas fa-users astat-bg-icon"></i>
  </div>
</div>

{{-- GRID 2 --}}
<div class="admin-grid2">

  {{-- Funnel Seleksi --}}
  @php
    $funnelTotal = max($totalInProcess + $totalAccepted + $totalRejected, 1);
  @endphp
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-filter mr-2" style="color:#6366f1"></i>Funnel Seleksi</span>
    </div>
    <div class="card-body">
      <div class="funnel-row">
        <span class="funnel-label">Pending / Review</span>
        <div class="funnel-bar-track">
          <div class="funnel-bar-fill" style="width:{{ round(($totalInProcess/$funnelTotal)*100) }}%;background:#6366f1"></div>
        </div>
        <span class="funnel-count">{{ $totalInProcess }}</span>
      </div>
      <div class="funnel-row">
        <span class="funnel-label">Diterima</span>
        <div class="funnel-bar-track">
          <div class="funnel-bar-fill" style="width:{{ round(($totalAccepted/$funnelTotal)*100) }}%;background:#14b8a6"></div>
        </div>
        <span class="funnel-count">{{ $totalAccepted }}</span>
      </div>
      <div class="funnel-row">
        <span class="funnel-label">Ditolak</span>
        <div class="funnel-bar-track">
          <div class="funnel-bar-fill" style="width:{{ round(($totalRejected/$funnelTotal)*100) }}%;background:#f43f5e"></div>
        </div>
        <span class="funnel-count">{{ $totalRejected }}</span>
      </div>
      <div class="funnel-row">
        <span class="funnel-label">Tahap Seleksi</span>
        <div class="funnel-bar-track">
          <div class="funnel-bar-fill" style="width:100%;background:#f59e0b"></div>
        </div>
        <span class="funnel-count">{{ $totalSelections }}</span>
      </div>
    </div>
  </div>

  {{-- Shortcut Menu --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-th mr-2" style="color:#f59e0b"></i>Menu Utama</span>
    </div>
    <div class="card-body">
      <div class="shortcut-grid">
        <a href="{{ route('jobvacancie.index') }}" class="shortcut-item">
          <i class="fas fa-briefcase"></i> Lowongan
        </a>
        <a href="{{ route('jobapplicant.index') }}" class="shortcut-item">
          <i class="fas fa-user-friends"></i> Pelamar
        </a>
        <a href="{{ route('jobapplication.manage') }}" class="shortcut-item">
          <i class="fas fa-tasks"></i> Seleksi
        </a>
        <a href="{{ route('recruitment-batch.index') }}" class="shortcut-item">
          <i class="fas fa-calendar-alt"></i> Batch
        </a>
        <a href="{{ route('departement.index') }}" class="shortcut-item">
          <i class="fas fa-sitemap"></i> Departemen
        </a>
        <a href="{{ route('position.index') }}" class="shortcut-item">
          <i class="fas fa-user-tag"></i> Jabatan
        </a>
      </div>
    </div>
  </div>

</div>
@endsection
