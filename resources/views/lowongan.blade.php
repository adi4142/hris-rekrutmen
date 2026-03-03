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
            max-width: 1200px;
            margin: 0 auto;
        }
        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }
        .job-card {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .job-card:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transform: translateY(-4px);
        }
        .job-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .job-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            flex: 1;
        }
        .job-card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        .job-card-badge {
            background: #f3f4f6;
            color: #4b5563;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .job-card-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .job-card-date {
            color: #9ca3af;
            font-size: 0.875rem;
        }
        .job-card-applied {
            color: var(--primary);
            font-size: 1rem;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: var(--white);
            padding: 40px 8%;
            margin-top: 80px;
        }
        .footer-bottom {
            text-align: center;
            color: #9ca3af;
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
                <a href="{{ route('lowongan.detail', $vacancy->vacancies_id) }}" class="job-card">
                    <div>
                        <div class="job-card-header">
                            <h3 class="job-card-title">{{ $vacancy->title ?? 'Staff' }}</h3>
                            <span style="color: var(--primary); font-weight: 600; font-size: 0.875rem;">Open</span>
                        </div>
                        
                        <div class="job-card-badges">
                            @php
                                $reqs = json_decode($vacancy->requirements, true);
                            @endphp
                            @if(is_array($reqs))
                                @foreach(array_slice($reqs, 0, 4) as $req)
                                    <span class="job-card-badge">{{ Str::limit($req, 20) }}</span>
                                @endforeach
                                @if(count($reqs) > 4)
                                    <span class="job-card-badge">+{{ count($reqs) - 4 }}</span>
                                @endif
                            @else
                                <span class="job-card-badge">{{ Str::limit($vacancy->requirements, 20) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="job-card-footer">
                        <span class="job-card-date">
                            <i class="far fa-calendar-alt"></i> {{ $vacancy->created_at ? $vacancy->created_at->diffForHumans() : 'Baru' }}
                        </span>
                        <i class="far fa-bookmark job-card-applied"></i>
                    </div>
                </a>
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