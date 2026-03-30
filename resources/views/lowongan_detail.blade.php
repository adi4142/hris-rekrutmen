<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('AdminLTE/dist/img/vneu.avif') }}" />
    <title>{{ $vacancy->title }} | Detail Lowongan</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2bb1ffff;
            --primary-hover: #1750deff;
            --dark: #111827;
            --light: #f9fafb;
            --white: #ffffff;
            --border: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--light);
            color: #374151;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 24px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .job-detail-card {
            background: var(--white);
            border-radius: 16px;
            padding: 40px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .job-header {
            border-bottom: 1px solid var(--border);
            margin-bottom: 32px;
            padding-bottom: 24px;
        }

        .job-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .job-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .requirement-list, .document-list {
            list-style: none;
        }

        .requirement-list li, .document-list li {
            position: relative;
            padding-left: 24px;
            margin-bottom: 12px;
        }

        .requirement-list li::before, .document-list li::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #10b981;
            font-size: 0.9rem;
        }

        .btn-apply {
            display: block;
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: var(--white);
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.2s;
            margin-top: 40px;
        }

        .btn-apply:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(43, 177, 255, 0.2);
        }

        @media (max-width: 640px) {
            .job-detail-card {
                padding: 24px;
            }
            .job-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('lowongan') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Lowongan
        </a>

        <div class="job-detail-card">
            <header class="job-header">
                <h1 class="job-title">{{ $vacancy->title }}</h1>
                <div class="job-meta">
                    <span><i class="fas fa-building"></i> {{ $vacancy->departement->name ?? 'Semua Departemen' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> Batas: {{ \Carbon\Carbon::parse($vacancy->expired_at)->format('d M Y') }}</span>
                    <span><i class="fas fa-clock"></i> {{ $vacancy->updated_at->diffForHumans() }}</span>
                </div>
            </header>

            @if($vacancy->description)
            <div class="section">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> Deskripsi Pekerjaan</h2>
                <div class="description-content">
                    {!! nl2br(e($vacancy->description)) !!}
                </div>
            </div>
            @endif

            @php
                $requirements = $vacancy->requirements;
                if (!is_array($requirements)) {
                    $requirements = $requirements ? [$requirements] : [];
                }
                $requirements = array_filter($requirements);
            @endphp

            @if(count($requirements) > 0)
            <div class="section">
                <h2 class="section-title"><i class="fas fa-tasks"></i> Kualifikasi / Persyaratan</h2>
                <ul class="requirement-list">
                    @foreach($requirements as $req)
                        <li>{{ $req }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
                $documents = $vacancy->required_documents ?: [];
            @endphp

            @if(count($documents) > 0)
            <div class="section">
                <h2 class="section-title"><i class="fas fa-file-alt"></i> Dokumen yang Dibutuhkan</h2>
                <ul class="document-list">
                    @foreach($documents as $doc)
                        <li>{{ $doc }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Jadwal Seleksi akan ditampilkan di Dashboard Pelamar setelah lolos screening berkas --}}


            @php
                $isExpired = \Carbon\Carbon::parse($vacancy->expired_at)->isPast();
            @endphp

            @if($isExpired)
                <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 20px; border-radius: 12px; text-align: center; margin-top: 40px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle mr-2"></i> Pendaftaran Lowongan Ini Sudah Ditutup
                    <p style="font-size: 0.9rem; font-weight: 400; margin-top: 4px; color: #b91c1c;">Batas pendaftaran adalah {{ \Carbon\Carbon::parse($vacancy->expired_at)->format('d F Y') }}</p>
                </div>
            @else
                <a href="{{ route('jobapplicant.create', ['vacancies_id' => $vacancy->vacancies_id]) }}" class="btn-apply">
                    Lamar Pekerjaan Ini Sekarang
                </a>
            @endif
        </div>
    </div>
</body>
</html>
