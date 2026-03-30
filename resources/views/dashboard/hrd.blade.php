@extends('layouts.admin')

@section('title', 'Dashboard HRD')
@section('page_title', 'Dashboard HRD')
@section('page_subtitle', 'Ringkasan rekrutmen yang kamu kelola')

@section('page_actions')
  <a href="{{ route('jobapplication.manage') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-tasks" style="font-size:11px"></i> Kelola Seleksi
  </a>
@endsection

@push('styles')
<style>
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px; margin-bottom: 20px;
  }
  @media(max-width:900px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:480px){ .stat-grid { grid-template-columns: 1fr 1fr; } }

  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 18px;
    position: relative; overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .stat-card:hover { transform: translateY(-2px); border-color: var(--accent); }

  .stat-icon {
    width: 34px; height: 34px;
    background: var(--accent-dim);
    border: 1px solid var(--border);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: var(--accent);
    margin-bottom: 14px;
  }

  .stat-value { font-size: 28px; font-weight: 800; color: var(--accent); line-height: 1; letter-spacing: -0.5px; font-feature-settings: "tnum"; }
  .stat-label { font-size: 12px; color: var(--tx-muted); margin-top: 6px; font-weight: 500; }
  .stat-sub   { font-size: 11px; color: var(--tx-muted); margin-top: 8px; display: flex; align-items: center; gap: 5px; }

  .bottom-grid {
    display: grid; grid-template-columns: 1fr 300px; gap: 14px;
  }
  @media(max-width:1000px){ .bottom-grid { grid-template-columns: 1fr; } }

  .pipeline-bar {
    display: flex; height: 8px; border-radius: 4px; overflow: hidden; gap: 2px;
    margin: 10px 0 8px;
  }
  .pipeline-bar div { border-radius: 4px; transition: width 0.8s cubic-bezier(.4,0,.2,1); }

  .pipe-legend { display: flex; gap: 18px; flex-wrap: wrap; margin-top: 4px; }
  .pipe-leg-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--tx-secondary); }
  .pipe-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

  .quick-actions { display: flex; flex-direction: column; gap: 6px; }
  .quick-action-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--tx-secondary); font-size: 13px; font-weight: 500;
    text-decoration: none; transition: all 0.15s;
  }
  .quick-action-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-dim); text-decoration: none; }
  .quick-action-btn i { width: 14px; text-align: center; font-size: 12px; color: var(--tx-muted); }
  .quick-action-btn .qa-arr { margin-left: auto; opacity: 0.3; font-size: 10px; }

  .recent-table td:first-child { color: var(--tx-primary); font-weight: 500; }
</style>
@endpush

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
    <div class="stat-value">{{ $totalInProcess }}</div>
    <div class="stat-label">Sedang Diproses</div>
    <div class="stat-sub"><span style="width:6px;height:6px;background:var(--warning);border-radius:50%;flex-shrink:0"></span> Pending & seleksi</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="fas fa-check-double"></i></div>
    <div class="stat-value">{{ $totalAccepted }}</div>
    <div class="stat-label">Diterima</div>
    <div class="stat-sub"><span style="width:6px;height:6px;background:var(--success);border-radius:50%;flex-shrink:0"></span> Hired & accepted</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
    <div class="stat-value">{{ $totalRejected }}</div>
    <div class="stat-label">Ditolak</div>
    <div class="stat-sub"><span style="width:6px;height:6px;background:var(--danger);border-radius:50%;flex-shrink:0"></span> Tidak lolos</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
    <div class="stat-value">{{ $totalActiveVacancies }}</div>
    <div class="stat-label">Lowongan Aktif</div>
    <div class="stat-sub"><span style="width:6px;height:6px;background:var(--info);border-radius:50%;flex-shrink:0"></span> Dari {{ $totalVacancies }} total</div>
  </div>
</div>

{{-- Pipeline --}}
@php
  $total = max($totalInProcess + $totalAccepted + $totalRejected, 1);
  $wp = round(($totalInProcess/$total)*100);
  $wa = round(($totalAccepted/$total)*100);
  $wr = round(($totalRejected/$total)*100);
@endphp
<div class="card mb-4">
  <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
    <span class="card-title">Pipeline Rekrutmen</span>
    <span style="font-size:12px;color:var(--tx-muted)">{{ $totalApplications }} total lamaran · {{ $totalApplicants }} pelamar unik</span>
  </div>
  <div class="card-body">
    <div class="pipeline-bar">
      <div style="width:{{ $wp }}%;background:var(--warning)" title="Proses: {{ $totalInProcess }}"></div>
      <div style="width:{{ $wa }}%;background:var(--success)" title="Diterima: {{ $totalAccepted }}"></div>
      <div style="width:{{ $wr }}%;background:var(--danger)"  title="Ditolak: {{ $totalRejected }}"></div>
    </div>
    <div class="pipe-legend">
      <div class="pipe-leg-item"><div class="pipe-dot" style="background:var(--warning)"></div>Diproses <strong style="color:var(--tx-primary)">{{ $totalInProcess }}</strong></div>
      <div class="pipe-leg-item"><div class="pipe-dot" style="background:var(--success)"></div>Diterima <strong style="color:var(--tx-primary)">{{ $totalAccepted }}</strong></div>
      <div class="pipe-leg-item"><div class="pipe-dot" style="background:var(--danger)"></div>Ditolak <strong style="color:var(--tx-primary)">{{ $totalRejected }}</strong></div>
    </div>
  </div>
</div>

{{-- Bottom Grid --}}
<div class="bottom-grid">

  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
      <span class="card-title">Lamaran Terbaru</span>
      <a href="{{ route('jobapplication.index') }}" class="btn btn-outline-primary btn-sm">Semua</a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0 recent-table">
          <thead>
            <tr><th>Pelamar</th><th>Posisi</th><th>Status</th><th>Tanggal</th></tr>
          </thead>
          <tbody>
            @forelse($recentApplications as $app)
            <tr>
              <td>{{ $app->jobApplicant->name ?? '-' }}</td>
              <td style="color:var(--tx-muted);font-size:12px">{{ $app->jobVacancie->title ?? '-' }}</td>
              <td>
                @php $s = $app->status; @endphp
                @if(in_array($s,['pending','applied']))    <span class="badge badge-warning">Pending</span>
                @elseif(in_array($s,['accepted','hired'])) <span class="badge badge-success">Diterima</span>
                @elseif($s=='rejected')                    <span class="badge badge-danger">Ditolak</span>
                @elseif($s=='process')                     <span class="badge badge-secondary">Seleksi</span>
                @elseif(in_array($s,['offering','offering_sent'])) <span class="badge badge-info">Offering</span>
                @else <span class="badge badge-secondary">{{ ucfirst($s) }}</span>
                @endif
              </td>
              <td style="font-size:11.5px;color:var(--tx-muted)">{{ $app->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" style="text-align:center;padding:32px 0;color:var(--tx-muted);font-size:13px">
                <i class="fas fa-inbox mb-2 d-block" style="font-size:20px;opacity:.3"></i>
                Belum ada lamaran
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><span class="card-title">Aksi Cepat</span></div>
    <div class="card-body">
      <div class="quick-actions">
        <a href="{{ route('jobvacancie.create') }}" class="quick-action-btn">
          <i class="fas fa-plus"></i> Buat Lowongan <i class="fas fa-chevron-right qa-arr"></i>
        </a>
        <a href="{{ route('jobapplication.manage') }}" class="quick-action-btn">
          <i class="fas fa-tasks"></i> Review Lamaran <i class="fas fa-chevron-right qa-arr"></i>
        </a>
        <a href="{{ route('recruitment-batch.index') }}" class="quick-action-btn">
          <i class="fas fa-calendar-plus"></i> Jadwalkan Batch <i class="fas fa-chevron-right qa-arr"></i>
        </a>
        <a href="{{ route('jobapplicant.index') }}" class="quick-action-btn">
          <i class="fas fa-user-friends"></i> Data Pelamar <i class="fas fa-chevron-right qa-arr"></i>
        </a>
        <a href="{{ route('jobvacancie.index') }}" class="quick-action-btn">
          <i class="fas fa-briefcase"></i> Manajemen Lowongan <i class="fas fa-chevron-right qa-arr"></i>
        </a>
      </div>
    </div>
  </div>

</div>
@endsection
