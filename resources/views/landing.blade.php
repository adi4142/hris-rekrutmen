<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Karir PT Vneu Teknologi Indonesia</title>
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
      --shadow-sm: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
      --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.05);
      --shadow-lg: 0 10px 40px rgba(0,0,0,.1), 0 4px 12px rgba(0,0,0,.06);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--gray-800);
      background: var(--white);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    /* ── NAVBAR ─────────────────────────────────── */
    .navbar {
      position: sticky; top: 0; z-index: 100;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--gray-200);
      padding: 0;
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
      background: var(--blue-600);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 800; color: white;
      letter-spacing: -0.5px;
    }

    .nav-brand-text {
      font-size: 15px; font-weight: 700;
      color: var(--gray-900); letter-spacing: -0.3px;
    }

    .nav-links {
      display: flex; align-items: center; gap: 4px;
      list-style: none; margin-left: 8px;
    }

    .nav-links a {
      display: block; padding: 6px 12px;
      font-size: 14px; font-weight: 500;
      color: var(--gray-600, #4b5563);
      text-decoration: none; border-radius: var(--radius-sm);
      transition: all 0.15s;
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
    .btn-ghost {
      background: transparent; color: var(--gray-700);
      border: 1px solid var(--gray-200);
    }
    .btn-ghost:hover { background: var(--gray-100); border-color: var(--gray-300); }
    .btn-primary {
      background: var(--blue-600); color: white;
      box-shadow: 0 1px 4px rgba(37,99,235,.25);
    }
    .btn-primary:hover { background: var(--blue-700); box-shadow: 0 4px 12px rgba(37,99,235,.35); transform: translateY(-1px); }
    .btn-primary-outline {
      background: transparent; color: var(--blue-600);
      border: 1.5px solid var(--blue-200);
    }
    .btn-primary-outline:hover { background: var(--blue-50); }
    .btn-lg { padding: 13px 28px; font-size: 15px; border-radius: var(--radius-md); }

    /* hamburger */
    .hamburger {
      display: none; flex-direction: column; gap: 5px;
      background: none; border: none; cursor: pointer; padding: 4px;
    }
    .hamburger span { display: block; width: 22px; height: 2px; background: var(--gray-700); border-radius: 2px; transition: 0.3s; }

    /* mobile nav */
    .mobile-nav {
      display: none; flex-direction: column;
      background: white; border-top: 1px solid var(--gray-200);
      padding: 12px 16px 20px;
    }
    .mobile-nav.open { display: flex; }
    .mobile-nav a {
      padding: 10px 12px; font-size: 15px; font-weight: 500;
      color: var(--gray-700); text-decoration: none; border-radius: var(--radius-sm);
      transition: 0.15s;
    }
    .mobile-nav a:hover { background: var(--gray-100); }
    .mobile-nav .divider { height: 1px; background: var(--gray-200); margin: 8px 0; }

    @media (max-width: 768px) {
      .nav-links, .nav-right { display: none; }
      .hamburger { display: flex; margin-left: auto; }
    }

    /* ── HERO ────────────────────────────────────── */
    .hero {
      background: linear-gradient(160deg, var(--blue-50) 0%, var(--white) 60%);
      padding: 96px 24px 80px;
      position: relative; overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute; top: -120px; right: -120px;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(96,165,250,0.12) 0%, transparent 70%);
      pointer-events: none;
    }

    .hero::after {
      content: '';
      position: absolute; bottom: -80px; left: -80px;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(37,99,235,0.07) 0%, transparent 70%);
      pointer-events: none;
    }

    .hero-inner {
      max-width: 1200px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 64px; align-items: center;
      position: relative; z-index: 1;
    }

    .hero-badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: var(--blue-50); border: 1px solid var(--blue-100);
      color: var(--blue-700); border-radius: 100px;
      padding: 5px 14px 5px 8px;
      font-size: 13px; font-weight: 600; margin-bottom: 24px;
    }

    .hero-badge-dot {
      width: 20px; height: 20px;
      background: var(--blue-100); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 10px;
    }

    .hero h1 {
      font-size: clamp(32px, 5vw, 52px);
      font-weight: 800; line-height: 1.15;
      letter-spacing: -1.5px; color: var(--gray-900);
      margin-bottom: 20px;
    }

    .hero h1 .accent { color: var(--blue-600); }

    .hero-desc {
      font-size: 17px; color: var(--gray-500);
      line-height: 1.7; margin-bottom: 36px; max-width: 500px;
    }

    .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

    .hero-stats {
      display: flex; gap: 32px; margin-top: 48px; padding-top: 32px;
      border-top: 1px solid var(--gray-200);
    }

    .hero-stat-value {
      font-size: 28px; font-weight: 800; color: var(--blue-700); letter-spacing: -0.5px;
    }

    .hero-stat-label { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

    .hero-visual {
      position: relative;
    }

    .hero-img {
      width: 100%; border-radius: var(--radius-xl);
      box-shadow: var(--shadow-lg);
      object-fit: cover; aspect-ratio: 4/3;
      display: block;
    }

    .hero-float-card {
      position: absolute;
      background: white; border-radius: var(--radius-md);
      box-shadow: var(--shadow-md);
      padding: 12px 16px;
      border: 1px solid var(--gray-200);
      display: flex; align-items: center; gap: 10px;
    }

    .hero-float-card.card-1 { top: -20px; right: -24px; }
    .hero-float-card.card-2 { bottom: 20px; left: -24px; }

    .float-icon {
      width: 36px; height: 36px; border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; flex-shrink: 0;
    }

    .float-icon.blue { background: var(--blue-100); color: var(--blue-600); }
    .float-icon.green { background: #dcfce7; color: #16a34a; }

    .float-label { font-size: 12px; color: var(--gray-500); }
    .float-value { font-size: 14px; font-weight: 700; color: var(--gray-900); }

    @media (max-width: 900px) {
      .hero-inner { grid-template-columns: 1fr; gap: 40px; }
      .hero-visual { display: none; }
    }

    /* ── WHY US ──────────────────────────────────── */
    .why-section {
      padding: 96px 24px;
      background: var(--white);
    }

    .section-inner { max-width: 1200px; margin: 0 auto; }

    .section-label {
      display: inline-block;
      font-size: 12px; font-weight: 700; letter-spacing: 1.2px;
      text-transform: uppercase; color: var(--blue-600);
      background: var(--blue-50); border-radius: 100px;
      padding: 5px 14px; margin-bottom: 16px;
    }

    .section-title {
      font-size: clamp(26px, 4vw, 40px);
      font-weight: 800; letter-spacing: -1px; color: var(--gray-900);
      margin-bottom: 14px;
    }

    .section-desc { font-size: 16px; color: var(--gray-500); max-width: 520px; line-height: 1.7; }

    .why-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 24px; margin-top: 56px;
    }

    @media (max-width: 768px) { .why-grid { grid-template-columns: 1fr; } }
    @media (max-width: 900px) and (min-width: 769px) { .why-grid { grid-template-columns: 1fr 1fr; } }

    .why-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      padding: 28px;
      transition: all 0.2s;
    }

    .why-card:hover {
      border-color: var(--blue-200);
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    .why-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; margin-bottom: 20px;
    }

    .why-icon.i1 { background: var(--blue-50); color: var(--blue-600); }
    .why-icon.i2 { background: #f0fdf4; color: #16a34a; }
    .why-icon.i3 { background: #fef3c7; color: #d97706; }

    .why-card h3 {
      font-size: 17px; font-weight: 700; color: var(--gray-900);
      margin-bottom: 10px; letter-spacing: -0.3px;
    }

    .why-card p { font-size: 14px; color: var(--gray-500); line-height: 1.7; }

    /* ── CTA ─────────────────────────────────────── */
    .cta-section {
      padding: 80px 24px;
      background: var(--gray-50);
      border-top: 1px solid var(--gray-200);
      border-bottom: 1px solid var(--gray-200);
    }

    .cta-box {
      max-width: 1200px; margin: 0 auto;
      background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-900) 100%);
      border-radius: var(--radius-xl);
      padding: 64px;
      text-align: center;
      position: relative; overflow: hidden;
    }

    .cta-box::before {
      content: '';
      position: absolute; top: -60px; right: -60px;
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    }

    .cta-box h2 {
      font-size: clamp(24px, 4vw, 40px);
      font-weight: 800; color: white; letter-spacing: -1px;
      margin-bottom: 14px; position: relative;
    }

    .cta-box p {
      font-size: 16px; color: rgba(255,255,255,0.7);
      max-width: 500px; margin: 0 auto 36px; line-height: 1.7;
      position: relative;
    }

    .btn-white {
      background: white; color: var(--blue-700); font-weight: 700;
      padding: 14px 32px; border-radius: var(--radius-md); font-size: 15px;
      text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      transition: all 0.18s; position: relative;
    }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }

    /* ── FOOTER ──────────────────────────────────── */
    .footer {
      background: var(--gray-900);
      padding: 56px 24px 32px;
    }

    .footer-inner {
      max-width: 1200px; margin: 0 auto;
    }

    .footer-grid {
      display: grid; grid-template-columns: 2fr 1fr 1.5fr;
      gap: 48px; padding-bottom: 40px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr; gap: 32px; } }

    .footer-brand-icon {
      width: 38px; height: 38px;
      background: var(--blue-600); border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 800; color: white;
      margin-bottom: 14px;
    }

    .footer-brand-name { font-size: 16px; font-weight: 700; color: white; margin-bottom: 10px; }
    .footer-brand-desc { font-size: 14px; color: rgba(255,255,255,0.45); line-height: 1.7; }

    .footer-col h4 {
      font-size: 12px; font-weight: 700; letter-spacing: 1px;
      text-transform: uppercase; color: rgba(255,255,255,0.4);
      margin-bottom: 16px;
    }

    .footer-col ul { list-style: none; }
    .footer-col ul li { margin-bottom: 10px; }
    .footer-col ul li a {
      font-size: 14px; color: rgba(255,255,255,0.6);
      text-decoration: none; transition: color 0.15s;
    }
    .footer-col ul li a:hover { color: white; }

    .footer-contact-item {
      display: flex; align-items: flex-start; gap: 10px;
      margin-bottom: 12px; font-size: 14px; color: rgba(255,255,255,0.6);
    }
    .footer-contact-item i { color: var(--blue-400); margin-top: 2px; flex-shrink: 0; font-size: 13px; }

    .footer-bottom {
      padding-top: 24px;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }

    .footer-copy { font-size: 13px; color: rgba(255,255,255,0.35); }
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
      <li><a href="#beranda" class="active">Beranda</a></li>
      <li><a href="#tentang">Tentang</a></li>
      <li><a href="{{ route('lowongan') }}">Lowongan</a></li>
    </ul>

    <div class="nav-right">
      <a href="{{ route('login') }}" class="btn btn-ghost">
        <i class="fas fa-sign-in-alt" style="font-size:13px"></i> Login HR
      </a>
      <a href="{{ route('lowongan') }}" class="btn btn-primary">
        Lihat Lowongan <i class="fas fa-arrow-right" style="font-size:12px"></i>
      </a>
    </div>

    <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="mobile-nav" id="mobileNav">
    <a href="#beranda" onclick="toggleMenu()">Beranda</a>
    <a href="#tentang" onclick="toggleMenu()">Tentang</a>
    <a href="{{ route('lowongan') }}">Lowongan</a>
    <div class="divider"></div>
    <a href="{{ route('login') }}" class="btn btn-ghost" style="text-align:center;justify-content:center">Login HR</a>
    <a href="{{ route('lowongan') }}" class="btn btn-primary" style="text-align:center;justify-content:center;margin-top:6px">Lihat Lowongan</a>
  </div>
</nav>

<!-- ════ HERO ════ -->
<section id="beranda" class="hero">
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-badge">
        <span class="hero-badge-dot">✦</span>
        Kami sedang aktif merekrut
      </div>

      <h1>Wujudkan Karir <span class="accent">Terbaik</span> Anda Bersama Kami</h1>

      <p class="hero-desc">
        Bergabunglah dengan tim inovatif di PT Vneu Teknologi Indonesia. Temukan peluang yang sesuai dengan keahlian dan passion Anda, dan jadilah bagian dari perubahan.
      </p>

      <div class="hero-actions">
        <a href="{{ route('lowongan') }}" class="btn btn-primary btn-lg">
          Cari Lowongan <i class="fas fa-arrow-right" style="font-size:13px"></i>
        </a>
        <a href="#tentang" class="btn btn-ghost btn-lg">Pelajari Lebih Lanjut</a>
      </div>

      <div class="hero-stats">
        <div>
          <div class="hero-stat-value">{{ \App\JobVacancie::where('status','open')->count() }}+</div>
          <div class="hero-stat-label">Posisi Dibuka</div>
        </div>
        <div>
          <div class="hero-stat-value">100%</div>
          <div class="hero-stat-label">Proses Transparan</div>
        </div>
        <div>
          <div class="hero-stat-value">Fast</div>
          <div class="hero-stat-label">Respons Cepat</div>
        </div>
      </div>
    </div>

    <div class="hero-visual">
      <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80"
           alt="Tim Vneu" class="hero-img">

      <div class="hero-float-card card-1">
        <div class="float-icon blue"><i class="fas fa-briefcase"></i></div>
        <div>
          <div class="float-label">Lowongan aktif</div>
          <div class="float-value">{{ \App\JobVacancie::where('status','open')->count() }} posisi terbuka</div>
        </div>
      </div>

      <div class="hero-float-card card-2">
        <div class="float-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
          <div class="float-label">Proses seleksi</div>
          <div class="float-value">Terstruktur & Adil</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════ WHY US ════ -->
<section id="tentang" class="why-section">
  <div class="section-inner">
    <div class="section-label">Mengapa Vneu?</div>
    <h2 class="section-title">Lebih dari Sekadar Pekerjaan</h2>
    <p class="section-desc">Kami percaya bahwa lingkungan yang tepat adalah kunci untuk menghasilkan karya terbaik.</p>

    <div class="why-grid">
      <div class="why-card">
        <div class="why-icon i1"><i class="fas fa-rocket"></i></div>
        <h3>Pertumbuhan Karir</h3>
        <p>Jalur karir yang jelas, program mentorship, dan dukungan pengembangan skill berkelanjutan untuk mencapai potensi maksimal Anda.</p>
      </div>

      <div class="why-card">
        <div class="why-icon i2"><i class="fas fa-users"></i></div>
        <h3>Lingkungan Suportif</h3>
        <p>Budaya kerja inklusif yang menghargai setiap ide. Kami percaya kolaborasi adalah kunci kesuksesan bersama.</p>
      </div>

      <div class="why-card">
        <div class="why-icon i3"><i class="fas fa-balance-scale"></i></div>
        <h3>Work-Life Balance</h3>
        <p>Waktu kerja yang fleksibel dan dukungan keseimbangan kehidupan kerja untuk menjaga kesejahteraan tim kami.</p>
      </div>
    </div>
  </div>
</section>

<!-- ════ CTA ════ -->
<section class="cta-section">
  <div class="cta-box">
    <h2>Siap Memulai Perjalanan Karir Anda?</h2>
    <p>Temukan peran yang tepat dan jadilah bagian dari tim kami. Masa depan karir Anda dimulai di sini.</p>
    <a href="{{ route('lowongan') }}" class="btn-white">
      Lihat Semua Lowongan <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</section>

<!-- ════ FOOTER ════ -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-brand-icon">VN</div>
        <div class="footer-brand-name">Vneu Teknologi Indonesia</div>
        <p class="footer-brand-desc">Membangun masa depan melalui inovasi dan talenta-talenta luar biasa yang berdedikasi tinggi.</p>
      </div>

      <div class="footer-col">
        <h4>Rekrutmen</h4>
        <ul>
          <li><a href="{{ route('lowongan') }}">Lowongan Tersedia</a></li>
          <li><a href="{{ route('login') }}">Login HR</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Kontak</h4>
        <div class="footer-contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <span>Jl. Kebayoran Lama No. 123, Jakarta Selatan</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-envelope"></i>
          <span>recruitment@vneu.co.id</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-phone"></i>
          <span>(021) 30015000</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span class="footer-copy">&copy; {{ date('Y') }} PT Vneu Teknologi Indonesia. All rights reserved.</span>
    </div>
  </div>
</footer>

<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  function toggleMenu() {
    document.getElementById('mobileNav').classList.toggle('open');
  }

  // Smooth active link highlight on scroll
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 80) current = s.id;
    });
    navLinks.forEach(a => {
      a.classList.remove('active');
      if (a.getAttribute('href') === '#' + current) a.classList.add('active');
    });
  });
</script>
</body>
</html>
