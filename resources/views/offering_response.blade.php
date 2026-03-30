<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Offering - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --white: #ffffff;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                        var(--slate-50);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: var(--slate-800);
        }
        .card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            padding: 48px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            border: 1px solid var(--white);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--success));
        }
        .icon {
            width: 80px;
            height: 80px;
            background: var(--slate-100);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 32px;
            transition: transform 0.3s ease;
        }
        .card:hover .icon {
            transform: scale(1.05) rotate(5deg);
        }
        .icon.success { color: var(--success); background: #ecfdf5; }
        .icon.error { color: var(--danger); background: #fef2f2; }
        .icon.negotiate { color: var(--warning); background: #fffbeb; }
        .icon.info { color: var(--info); background: #eff6ff; }
        
        h1 {
            color: var(--slate-800);
            margin-bottom: 12px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        p {
            color: var(--slate-500);
            line-height: 1.6;
            margin-bottom: 32px;
            font-size: 16px;
        }
        .offering-summary {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 32px;
            text-align: left;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-item:last-child { margin-bottom: 0; }
        .summary-label { color: var(--slate-500); }
        .summary-value { color: var(--slate-800); font-weight: 600; }

        .form-group {
            text-align: left;
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--slate-700);
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--slate-200);
            border-radius: 14px;
            font-size: 16px;
            transition: all 0.2s;
            box-sizing: border-box;
            background: var(--slate-50);
            font-family: inherit;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        textarea.form-control {
            min-height: 120px;
        }
        .btn {
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            width: 100%;
            gap: 10px;
        }
        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2);
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid var(--slate-200);
            color: var(--slate-500);
            margin-top: 16px;
        }
        .btn-outline:hover {
            background: var(--slate-50);
            color: var(--slate-800);
            border-color: var(--slate-300);
            box-shadow: none;
            transform: none;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($type == 'negotiate')
            <div class="icon negotiate">
                <i class="fas fa-comments"></i>
            </div>
            <h1>Negosiasi Offering</h1>
            <p>Anda sedang mengajukan peninjauan kembali atas penawaran untuk posisi <strong>{{ $application->jobVacancie->title }}</strong>.</p>
            
            <div class="offering-summary">
                <div class="summary-item">
                    <span class="summary-label">Penawaran Gaji Saat Ini</span>
                    <span class="summary-value">Rp {{ number_format($application->offering_salary, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Tanggal Mulai</span>
                    <span class="summary-value">{{ \Carbon\Carbon::parse($application->offering_start_date)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <form action="{{ route('offering.negotiate', $application->application_id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Ekspektasi Gaji Anda (IDR)</label>
                    <input type="number" name="expected_salary" class="form-control" placeholder="Contoh: 8500000" required>
                    <small style="color: var(--slate-400); font-size: 12px; margin-top: 4px; display: block;">Masukkan nominal bersih yang Anda harapkan.</small>
                </div>
                <div class="form-group">
                    <label>Alasan atau Pertimbangan</label>
                    <textarea name="negotiation_reason" class="form-control" placeholder="Tuliskan alasan negosiasi atau pengalaman tambahan yang menjadi pertimbangan Anda..." required></textarea>
                </div>
                <button type="submit" class="btn">
                    <i class="fas fa-paper-plane"></i> Kirim Permintaan Negosiasi
                </button>
            </form>
            <a href="{{ url('/') }}" class="btn btn-outline">Kembali</a>
        @else
            <div class="icon {{ $type }}">
                <i class="fas {{ $type == 'success' ? 'fa-check-double' : ($type == 'error' ? 'fa-times-circle' : ($type == 'info' ? 'fa-info-circle' : 'fa-check-circle')) }}"></i>
            </div>
            <h1>{{ $type == 'success' ? 'Tanggapan Terkirim' : ($type == 'info' ? 'Status Penawaran' : 'Tanggapan Diterima') }}</h1>
            <p style="margin-bottom: 24px;">{{ $message }}</p>

            @if(isset($application) && $application->status == 'hired')
                <div class="offering-summary" style="background: #f0fdf4; border-color: #bcf0da;">
                    <div class="summary-item">
                        <span class="summary-label">Status Akhir</span>
                        <span class="summary-value" style="color: var(--success);">Bergabung (Hired)</span>
                    </div>
                </div>
            @endif
            
            <a href="{{ url('/') }}" class="btn btn-outline">Selesai</a>
        @endif
    </div>
</body>
</html>
