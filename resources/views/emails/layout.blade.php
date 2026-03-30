<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notifikasi Rekrutmen')</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            max-width: 100%;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f1f5f9;
            padding: 20px 0;
        }
        .content {
            max-width: 600px;
            width: 95%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #0d6efd;
            letter-spacing: -1px;
        }
        .header p {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
        }
        .body {
            padding: 40px 30px;
            color: #334155;
            line-height: 1.8;
            font-size: 15px;
        }
        .body h2 {
            margin-top: 0;
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .highlight-box {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin: 25px 0;
            border: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background-color: #0d6efd;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            margin: 10px 4px;
            text-align: center;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .info-grid td.label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 35%;
        }
        .info-grid td.value {
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
            width: 65%;
            padding-left: 15px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        
        @media only screen and (max-width: 500px) {
            .header { padding: 30px 20px; }
            .body { padding: 30px 20px; }
            .header h1 { font-size: 22px; }
            .info-grid td { display: block; width: 100% !important; padding: 8px 0; border: none; }
            .info-grid td.value { padding-left: 0; font-size: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
            .info-grid td.label { padding-bottom: 0px; }
            .btn { width: 100%; box-sizing: border-box; margin: 5px 0; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @php
            $companyName = 'PT Vneu Teknologi Indonesia';
            $companyTagline = 'Tetap Semangat, Sukses 👍';
        @endphp
        <div class="content">
            <div class="header">
                <h1>{{ $companyName }}</h1>
                <p>{{ $companyTagline }}</p>
            </div>
            <div class="body">
                @yield('content')
                <p style="margin-top: 30px;">Terima kasih,<br><strong>Tim Rekrutmen PT Vneu Teknologi</strong></p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.<br>
            </div>
        </div>
    </div>
</body>
</html>
