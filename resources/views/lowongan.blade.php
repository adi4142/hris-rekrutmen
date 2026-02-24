<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
    <title>Lowongan Pekerjaan | HRIS System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2bb1ffff;
            --primary-hover: #1750deff;
            --secondary: #10b981;
            --dark: #1f2937;
            --light: #f9fafb;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Navbar */ 
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            height: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8%;
            background: var(--glass);
            backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn-auth {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* Header */
        .page-header {
            padding: 150px 8% 50px;
            text-align: center;
            background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .page-header p {
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Job Grid */
        .job-container {
            padding: 50px 8%;
        }

        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .job-card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            transition: 0.3s;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }

        .job-header {
            margin-bottom: 20px;
        }

        .job-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .job-meta {
            display: flex;
            gap: 15px;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .job-meta i {
            color: var(--primary);
            margin-right: 5px;
        }

        .job-description {
            color: #4b5563;
            margin-bottom: 20px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(43, 177, 255, 0.1);
            color: var(--primary);
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: var(--white);
            padding: 60px 8% 30px;
            margin-top: 50px;
        }

        .footer-bottom {
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="logo">
            <i class="fas fa-users-cog"></i> HRIS System
        </a>
        <ul class="nav-links">
            <li><a href="/">Beranda</a></li>
            <li><a href="/#features">Fitur</a></li>
            <li><a href="{{ route('lowongan') }}">Lowongan</a></li>
        </ul>
        <div class="btn-auth">
            @if(auth()->check())
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('dashboard') }}">Dashboard</a>
    @else
        <a href="{{ route('applicant.dashboard') }}">Dashboard</a>
    @endif
@else
    <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
@endif
        </div>
    </nav>

    <header class="page-header">
        <h1>Bergabunglah Bersama Kami</h1>
        <p>Temukan peluang karir terbaik dan jadilah bagian dari tim kami untuk menciptakan inovasi masa depan.</p>
    </header>

    <section class="job-container">
        <div class="job-grid">
            @forelse($jobVacancies as $vacancy)
                <div class="job-card">
                    <div class="job-content">
                        <div class="job-header">
                            <span class="badge">{{ $vacancy->departement->name ?? 'Umum' }}</span>
                            <h3 class="job-title">Dibutuhkan {{ $vacancy->title ?? 'Staff' }}</h3>
                        </div>
                        <p class="job-description">
                            {{ Str::limit($vacancy->description, 100) }}
                        </p>
                        <p class="job-requirements">
                            {{ Str::limit($vacancy->requirements, 100) }}
                        </p>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="{{ route('jobapplicant.create', ['vacancies_id' => $vacancy->vacancies_id]) }}" class="btn btn-primary" style="width: 100%; text-align: center;">
                            Lamar Sekarang <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12" style="text-align: center; grid-column: 1/-1; padding: 50px;">
                    <img src="{{ asset('AdminLTE/dist/img/empty.svg') }}" alt="No Data" style="max-width: 200px; margin-bottom: 20px; opacity: 0.5;">
                    <h3>Belum ada lowongan dibuka</h3>
                    <p class="text-muted">Pantau terus halaman ini untuk informasi terbaru.</p>
                </div>
            @endforelse
        </div>
    </section>

    <footer>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} HRIS System. Seluruh hak cipta dilindungi.
        </div>
    </footer>
</body>
</html>