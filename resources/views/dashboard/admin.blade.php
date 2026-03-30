@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Monitoring sistem dan seluruh aktivitas')

@section('page_actions')
  <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-users-cog" style="font-size:11px"></i> Kelola User
  </a>
@endsection

@push('styles')
<style>
  .sa-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 14px; margin-bottom: 20px;
  }
  @media(max-width:900px){ .sa-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:480px){ .sa-grid { grid-template-columns: 1fr 1fr; } }

  .sa-stat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 18px;
    position: relative; overflow: hidden;
    transition: transform 0.2s, border-color 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .sa-stat:hover { transform: translateY(-2px); border-color: var(--accent); }

  .sa-stat-bg {
    position: absolute; right: 14px; bottom: 8px;
    font-size: 38px; opacity: 0.08; pointer-events: none;
    color: var(--accent);
  }

  .sa-stat-label { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--tx-muted); margin-bottom: 8px; }
  .sa-stat-value { font-size: 32px; font-weight: 800; color: var(--accent); line-height: 1; letter-spacing: -0.5px; font-feature-settings: "tnum"; }
  .sa-stat-sub   { font-size: 11.5px; color: var(--tx-muted); margin-top: 7px; }

  .main-grid {
    display: grid; grid-template-columns: 1fr 340px; gap: 14px;
  }
  @media(max-width:1100px){ .main-grid { grid-template-columns: 1fr; } }

  .log-list { display: flex; flex-direction: column; }
  .log-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 0; border-bottom: 1px solid var(--border);
  }
  .log-item:last-child { border-bottom: none; padding-bottom: 0; }
  .log-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--accent); flex-shrink: 0; margin-top: 5px;
  }
  .log-user { font-size: 12.5px; font-weight: 600; color: var(--tx-primary); }
  .log-activity { font-size: 12px; color: var(--tx-secondary); margin-top: 1px; }
  .log-meta { font-size: 11px; color: var(--tx-muted); margin-top: 2px; }
  .log-time { margin-left: auto; flex-shrink: 0; font-size: 11px; color: var(--tx-muted); white-space: nowrap; }
</style>
@endpush

@section('content')

<div class="sa-grid">
  <div class="sa-stat">
    <i class="fas fa-users sa-stat-bg"></i>
    <div class="sa-stat-label">Total User</div>
    <div class="sa-stat-value">{{ $stats['total_users'] }}</div>
    <div class="sa-stat-sub">HRD & Admin</div>
  </div>
  <div class="sa-stat">
    <i class="fas fa-briefcase sa-stat-bg"></i>
    <div class="sa-stat-label">Lowongan</div>
    <div class="sa-stat-value">{{ $stats['total_vacancies'] }}</div>
    <div class="sa-stat-sub">Semua posisi</div>
  </div>
  <div class="sa-stat">
    <i class="fas fa-user-tie sa-stat-bg"></i>
    <div class="sa-stat-label">Pelamar</div>
    <div class="sa-stat-value">{{ $stats['total_applicants'] }}</div>
    <div class="sa-stat-sub">Role pelamar / tamu</div>
  </div>
  <div class="sa-stat">
    <i class="fas fa-file-alt sa-stat-bg"></i>
    <div class="sa-stat-label">Lamaran</div>
    <div class="sa-stat-value">{{ $stats['total_applications'] }}</div>
    <div class="sa-stat-sub">Total semua lamaran</div>
  </div>
</div>

<div class="main-grid">

  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
      <span class="card-title">Tren Lamaran Bulanan</span>
      <span style="font-size:11.5px;color:var(--tx-muted)">{{ date('Y') }}</span>
    </div>
    <div class="card-body">
      <div style="position:relative;height:200px">
        <canvas id="appChart"></canvas>
      </div>
      @if($applicationStats->isEmpty())
        <p style="text-align:center;color:var(--tx-muted);font-size:13px;margin-top:12px">Belum ada data</p>
      @endif
    </div>
  </div>

  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
      <span class="card-title">Aktivitas Terakhir</span>
      <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary btn-sm" style="font-size:11px;padding:3px 9px">Semua</a>
    </div>
    <div class="card-body" style="padding:0 18px !important">
      <div class="log-list">
        @forelse($recentLogs as $log)
        <div class="log-item">
          <div class="log-dot"></div>
          <div style="flex:1;min-width:0">
            <div class="log-user">{{ $log->user->name ?? 'System' }}</div>
            <div class="log-activity">{{ Str::limit($log->activity, 55) }}</div>
            <div class="log-meta"><i class="fas fa-tag mr-1" style="font-size:9px"></i>{{ $log->module }}</div>
          </div>
          <div class="log-time">{{ $log->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <div style="text-align:center;padding:32px 0;color:var(--tx-muted);font-size:13px">
          <i class="fas fa-history mb-2 d-block" style="font-size:20px;opacity:.3"></i>
          Belum ada aktivitas
        </div>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function(){
  var raw = @json($applicationStats);
  var months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  var labels = [], data = [];
  var map = {};
  raw.forEach(function(r){ map[r.month] = r.count; });
  for(var i=1;i<=12;i++){ labels.push(months[i-1]); data.push(map[i]||0); }

  var ctx = document.getElementById('appChart');
  if(!ctx) return;

  Chart.defaults.color = '#64748b';

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Lamaran',
        data: data,
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 1,
        borderRadius: 6,
        borderSkipped: false,
        hoverBackgroundColor: 'rgba(59, 130, 246, 0.9)',
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#1e293b',
          bodyColor: '#475569',
          borderColor: '#e2e8f0',
          borderWidth: 1, padding: 10,
          callbacks: { label: function(c){ return ' '+c.parsed.y+' lamaran'; } }
        }
      },
      scales: {
        x: {
          grid: { color: '#f1f5f9' },
          ticks: { color: '#64748b', font: { size: 11 } },
          border: { color: '#e2e8f0' }
        },
        y: {
          beginAtZero: true,
          grid: { color: '#f1f5f9' },
          ticks: { color: '#64748b', font: { size: 11 }, stepSize: 1 },
          border: { color: '#e2e8f0' }
        }
      }
    }
  });
})();
</script>
@endpush
