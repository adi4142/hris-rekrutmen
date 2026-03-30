<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lowongan Pekerjaan | PT Vneu Teknologi Indonesia</title>
  <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --blue-50:  #eff6ff;
      --blue-100: #dbeafe;
      --blue-200: #bfdbfe;
      --blue-400: #60a5fa;
      --blue-500: #3b82f6;
      --blue-600: #2563eb;
      --blue-700: #1d4ed8;
      --blue-800: #1e40af;
      --blue-900: #1e3a8a;
      --gray-50:  #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-700: #374151;
      --gray-800: #1f2937;
      --gray-900: #111827;
      --white: #ffffff;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --radius-xl: 24px;
      --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
      --shadow-md: 0 4px 16px rgba(0,0,0,.08);
      --shadow-lg: 0 10px 40px rgba(0,0,0,.1);
    }

    html { scroll-behavior: smooth; }
    body {
      font-family: 'Inter', sans-serif;
      color: var(--gray-800); background: var(--white);
      line-height: 1.6; -webkit-font-smoothing: antialiased;
    }

    /* ── NAVBAR ─── */
    .navbar {
      position: sticky; top: 0; z-index: 100;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--gray-200);
    }

    .nav-inner {
      max-width: 1200px; margin: 0 auto;
      padding: 0 24px;
      display: flex; align-items: center;
      height: 64px; gap: 32px;
    }

    .nav-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0;
    }

    .nav-brand-icon {
      width: 36px; height: 36px;
      background: var(--blue-600); border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 800; color: white;
    }

    .nav-brand-text { font-size: 15px; font-weight: 700; color: var(--gray-900); letter-spacing: -0.3px; }

    .nav-links { display: flex; align-items: center; gap: 4px; list-style: none; margin-left: 8px; }
    .nav-links a {
      display: block; padding: 6px 12px; font-size: 14px; font-weight: 500;
      color: var(--gray-600, #4b5563); text-decoration: none;
      border-radius: var(--radius-sm); transition: all 0.15s;
    }
    .nav-links a:hover { background: var(--gray-100); color: var(--gray-900); }
    .nav-links a.active { color: var(--blue-600); background: var(--blue-50); }

    .nav-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

    .btn {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 9px 18px; border-radius: var(--radius-sm);
      font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif;
      text-decoration: none; cursor: pointer; border: none;
      transition: all 0.18s; white-space: nowrap;
    }
    .btn-ghost { background: transparent; color: var(--gray-700); border: 1px solid var(--gray-200); }
    .btn-ghost:hover { background: var(--gray-100); }
    .btn-primary { background: var(--blue-600); color: white; box-shadow: 0 1px 4px rgba(37,99,235,.25); }
    .btn-primary:hover { background: var(--blue-700); transform: translateY(-1px); }

    .hamburger {
      display: none; flex-direction: column; gap: 5px;
      background: none; border: none; cursor: pointer; padding: 4px;
    }
    .hamburger span { display: block; width: 22px; height: 2px; background: var(--gray-700); border-radius: 2px; }

    .mobile-nav {
      display: none; flex-direction: column;
      background: white; border-top: 1px solid var(--gray-200);
      padding: 12px 16px 20px;
    }
    .mobile-nav.open { display: flex; }
    .mobile-nav a {
      padding: 10px 12px; font-size: 15px; font-weight: 500;
      color: var(--gray-700); text-decoration: none; border-radius: var(--radius-sm);
    }
    .mobile-nav a:hover { background: var(--gray-100); }
    .mobile-nav .divider { height: 1px; background: var(--gray-200); margin: 8px 0; }

    @media (max-width: 768px) {
      .nav-links, .nav-right { display: none; }
      .hamburger { display: flex; margin-left: auto; }
    }

    /* ── PAGE HEADER ─── */
    .page-header {
      background: linear-gradient(160deg, var(--blue-50) 0%, var(--white) 70%);
      padding: 64px 24px 56px;
      border-bottom: 1px solid var(--gray-200);
      position: relative; overflow: hidden;
    }

    .page-header::after {
      content: '';
      position: absolute; top: -80px; right: -80px;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(96,165,250,0.1) 0%, transparent 70%);
      pointer-events: none;
    }

    .page-header-inner {
      max-width: 1200px; margin: 0 auto;
      position: relative; z-index: 1;
    }

    .page-label {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
      color: var(--blue-600); background: var(--blue-50);
      border: 1px solid var(--blue-100); border-radius: 100px;
      padding: 5px 14px; margin-bottom: 16px;
    }

    .page-header h1 {
      font-size: clamp(26px, 4vw, 44px);
      font-weight: 800; letter-spacing: -1px; color: var(--gray-900);
      margin-bottom: 12px;
    }

    .page-header h1 span { color: var(--blue-600); }

    .page-header-desc { font-size: 16px; color: var(--gray-500); max-width: 560px; line-height: 1.7; }

    /* ── FILTER BAR ─── */
    .filter-bar {
      background: white;
      border-bottom: 1px solid var(--gray-200);
      padding: 16px 24px;
      position: sticky; top: 64px; z-index: 90;
    }

    .filter-inner {
      max-width: 1200px; margin: 0 auto;
      display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    }

    .search-wrap {
      flex: 1; min-width: 220px;
      position: relative;
    }
    .search-wrap i {
      position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
      color: var(--gray-400); font-size: 14px;
    }
    .search-wrap input {
      width: 100%; padding: 9px 12px 9px 36px;
      border: 1px solid var(--gray-200); border-radius: var(--radius-sm);
      font-size: 14px; font-family: 'Inter', sans-serif; color: var(--gray-800);
      outline: none; transition: border-color 0.15s;
    }
    .search-wrap input:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }

    .count-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue-50); border: 1px solid var(--blue-100);
      color: var(--blue-700); border-radius: 100px;
      padding: 6px 14px; font-size: 13px; font-weight: 600;
      white-space: nowrap;
    }

    /* ── JOB GRID ─── */
    .jobs-section {
      padding: 40px 24px 80px;
      background: var(--gray-50);
      min-height: 400px;
    }

    .jobs-inner { max-width: 1200px; margin: 0 auto; }

    .jobs-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    @media (max-width: 900px) { .jobs-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .jobs-grid { grid-template-columns: 1fr; } }

    /* ── JOB CARD ─── */
    .job-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      padding: 24px;
      display: flex; flex-direction: column;
      transition: all 0.2s; position: relative;
      overflow: hidden;
    }

    .job-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: var(--blue-500);
      transform: scaleX(0); transform-origin: left;
      transition: transform 0.2s;
    }

    .job-card:hover {
      border-color: var(--blue-200);
      box-shadow: var(--shadow-md);
      transform: translateY(-3px);
    }
    .job-card:hover::before { transform: scaleX(1); }

    .job-card-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 16px;
    }

    .job-icon {
      width: 44px; height: 44px;
      background: var(--blue-50); border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      color: var(--blue-600); font-size: 18px;
      border: 1px solid var(--blue-100);
    }

    .job-badge-open {
      display: inline-flex; align-items: center; gap: 5px;
      background: #dcfce7; color: #15803d;
      border-radius: 100px; padding: 3px 10px;
      font-size: 11px; font-weight: 600;
    }

    .job-badge-open::before {
      content: '';
      width: 6px; height: 6px; border-radius: 50%;
      background: #22c55e; flex-shrink: 0;
    }

    .job-title {
      font-size: 16px; font-weight: 700; color: var(--gray-900);
      margin-bottom: 6px; letter-spacing: -0.3px; line-height: 1.3;
    }

    .job-dept {
      display: flex; align-items: center; gap: 6px;
      font-size: 13px; color: var(--gray-500); margin-bottom: 16px;
    }
    .job-dept i { font-size: 12px; color: var(--gray-400); }

    .job-tags {
      display: flex; flex-wrap: wrap; gap: 6px;
      margin-bottom: 20px; flex: 1;
    }

    .job-tag {
      display: inline-block;
      background: var(--gray-100); color: var(--gray-600);
      border-radius: 6px; padding: 3px 9px;
      font-size: 12px; font-weight: 500;
      border: 1px solid var(--gray-200);
    }

    .job-tag-more {
      background: var(--blue-50); color: var(--blue-600);
      border-color: var(--blue-100);
    }

    .job-card-footer {
      display: flex; align-items: center; justify-content: space-between;
      padding-top: 16px;
      border-top: 1px solid var(--gray-100);
      margin-top: auto;
    }

    .job-time { font-size: 12px; color: var(--gray-400); display: flex; align-items: center; gap: 5px; }

    .btn-detail {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: var(--radius-sm);
      font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif;
      background: var(--blue-600); color: white;
      text-decoration: none; transition: all 0.18s; border: none; cursor: pointer;
    }
    .btn-detail:hover { background: var(--blue-700); transform: translateY(-1px); }
    .btn-detail i { font-size: 11px; }

    /* ── EMPTY STATE ─── */
    .empty-state {
      text-align: center; padding: 80px 24px;
      grid-column: 1 / -1;
    }

    .empty-icon {
      width: 72px; height: 72px;
      background: var(--blue-50); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 28px; color: var(--blue-400);
      margin: 0 auto 20px;
    }

    .empty-state h3 { font-size: 20px; font-weight: 700; color: var(--gray-800); margin-bottom: 10px; }
    .empty-state p { font-size: 15px; color: var(--gray-500); margin-bottom: 24px; }

    /* ── FOOTER ─── */
    .footer {
      background: var(--gray-900);
      padding: 40px 24px 28px;
    }

    .footer-inner { max-width: 1200px; margin: 0 auto; }

    .footer-row {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 16px;
      padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.08);
      margin-bottom: 20px;
    }

    .footer-brand { display: flex; align-items: center; gap: 10px; }
    .footer-brand-icon {
      width: 32px; height: 32px;
      background: var(--blue-600); border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: white;
    }
    .footer-brand-name { font-size: 14px; font-weight: 700; color: white; }

    .footer-links { display: flex; gap: 20px; flex-wrap: wrap; }
    .footer-links a { font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.15s; }
    .footer-links a:hover { color: white; }

    .footer-copy { font-size: 13px; color: rgba(255,255,255,0.3); }

    /* Alert */
    .flash-success, .flash-error {
      padding: 14px 24px;
      display: flex; align-items: center; gap: 10px;
      font-size: 14px; font-weight: 500;
    }
    .flash-success { background: #dcfce7; color: #15803d; border-bottom: 1px solid #bbf7d0; }
    .flash-error   { background: #fee2e2; color: #b91c1c; border-bottom: 1px solid #fecaca; }
  </style>
</head>
<body>

<!-- ════ NAVBAR ════ -->
<nav class="navbar">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-brand">
      <div class="nav-brand-icon">VN</div>
      <span class="nav-brand-text">Vneu Teknologi</span>
    </a>

    <ul class="nav-links">
      <li><a href="{{ route('home') }}">Beranda</a></li>
      <li><a href="{{ route('home') }}#tentang">Tentang</a></li>
      <li><a href="{{ route('lowongan') }}" class="active">Lowongan</a></li>
    </ul>

    <div class="nav-right">
      <a href="{{ route('login') }}" class="btn btn-ghost">
        <i class="fas fa-sign-in-alt" style="font-size:13px"></i> Login HR
      </a>
    </div>

    <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="mobile-nav" id="mobileNav">
    <a href="{{ route('home') }}">Beranda</a>
    <a href="{{ route('home') }}#tentang">Tentang</a>
    <a href="{{ route('lowongan') }}" style="color:var(--blue-600);font-weight:600">Lowongan</a>
    <div class="divider"></div>
    <a href="{{ route('login') }}" class="btn btn-ghost" style="text-align:center;justify-content:center">Login HR</a>
  </div>
</nav>

<!-- ════ FLASH MESSAGES ════ -->
@if(session('success'))
  <div class="flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
@endif

<!-- ════ PAGE HEADER ════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div class="page-label">
      <i class="fas fa-briefcase" style="font-size:11px"></i> Karir & Lowongan
    </div>
    <h1>Temukan Peran <span>Terbaik</span> Anda</h1>
    <p class="page-header-desc">Bergabunglah dengan tim inovatif PT Vneu. Kami membuka kesempatan untuk talenta-talenta berbakat yang ingin berkembang.</p>
  </div>
</div>

<!-- ════ FILTER BAR ════ -->
<div class="filter-bar">
  <div class="filter-inner">
    <div class="search-wrap">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" placeholder="Cari posisi atau departemen..." oninput="filterJobs()">
    </div>
    <div class="count-badge">
      <i class="fas fa-list-ul" style="font-size:11px"></i>
      <span id="jobCount">{{ count($jobVacancies) }}</span> posisi tersedia
    </div>
  </div>
</div>

<!-- ════ JOB GRID ════ -->
<section class="jobs-section">
  <div class="jobs-inner">
    <div class="jobs-grid" id="jobsGrid">

      @forelse($jobVacancies as $vacancy)
        <div class="job-card" data-search="{{ strtolower($vacancy->title . ' ' . ($vacancy->departement->name ?? '')) }}">
          <div class="job-card-top">
            <div class="job-icon"><i class="fas fa-briefcase"></i></div>
            <span class="job-badge-open">Dibuka</span>
          </div>

          <div class="job-title">{{ $vacancy->title ?? 'Posisi Staff' }}</div>

          <div class="job-dept">
            <i class="fas fa-building"></i>
            {{ $vacancy->departement->name ?? 'Departemen Umum' }}
          </div>

          <div class="job-tags">
            @php $reqs = $vacancy->requirements; @endphp
            @if(is_array($reqs) && count($reqs))
              @foreach(array_slice($reqs, 0, 3) as $req)
                <span class="job-tag">{{ Str::limit($req, 35) }}</span>
              @endforeach
              @if(count($reqs) > 3)
                <span class="job-tag job-tag-more">+{{ count($reqs) - 3 }} lainnya</span>
              @endif
            @elseif($reqs)
              <span class="job-tag">{{ Str::limit($reqs, 40) }}</span>
            @else
              <span class="job-tag" style="color:var(--gray-400)">Lihat detail posisi</span>
            @endif
          </div>

          <div class="job-card-footer">
            <span class="job-time">
              <i class="far fa-clock"></i>
              {{ $vacancy->created_at ? $vacancy->created_at->diffForHumans() : 'Baru ditambahkan' }}
            </span>
            <a href="{{ route('lowongan.detail', $vacancy->vacancies_id) }}" class="btn-detail">
              Detail <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @empty
        <div class="empty-state">
          <div class="empty-icon"><i class="fas fa-search"></i></div>
          <h3>Belum Ada Lowongan</h3>
          <p>Kami sedang menyiapkan peluang karir baru. Silakan kembali lagi nanti.</p>
          <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-home" style="font-size:13px"></i> Kembali ke Beranda
          </a>
        </div>
      @endforelse

      <!-- Empty search state (hidden by default) -->
      <div class="empty-state" id="noResult" style="display:none">
        <div class="empty-icon"><i class="fas fa-filter"></i></div>
        <h3>Tidak Ditemukan</h3>
        <p>Coba kata kunci lain atau hapus filter pencarian.</p>
      </div>

    </div>
  </div>
</section>

<!-- ════ FOOTER ════ -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-row">
      <div class="footer-brand">
        <div class="footer-brand-icon">VN</div>
        <span class="footer-brand-name">Vneu Teknologi Indonesia</span>
      </div>
      <div class="footer-links">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('lowongan') }}">Lowongan</a>
        <a href="{{ route('login') }}">Login HR</a>
      </div>
    </div>
    <span class="footer-copy">&copy; {{ date('Y') }} PT Vneu Teknologi Indonesia. All rights reserved.</span>
  </div>
</footer>

<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  function toggleMenu() {
    document.getElementById('mobileNav').classList.toggle('open');
  }

  function filterJobs() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.job-card[data-search]');
    let visible = 0;

    cards.forEach(card => {
      const match = !q || card.dataset.search.includes(q);
      card.style.display = match ? '' : 'none';
      if (match) visible++;
    });

    document.getElementById('jobCount').textContent = visible;
    document.getElementById('noResult').style.display = visible === 0 ? 'block' : 'none';
  }
</script>
</body>
</html>
