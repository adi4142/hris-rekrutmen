<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - HRIS System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .body {
            padding: 30px;
        }
        .body p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #28a745, #218838);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .btn-container {
            text-align: center;
            margin: 25px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 HRIS System</h1>
            <p>Permintaan Reset Password</p>
        </div>
        <div class="body">
            <p>Halo <strong>{{ $userName }}</strong>,</p>
            <p>Kami menerima permintaan untuk mereset password akun HRIS Anda. Klik tombol di bawah ini untuk memverifikasi email dan melanjutkan proses reset password:</p>
            
            <div class="btn-container">
                <a href="{{ $verifyUrl }}" class="btn">✅ Verifikasi Email & Reset Password</a>
            </div>

            <div class="warning">
                <strong>⚠️ Penting:</strong> Link ini hanya berlaku selama <strong>1 jam</strong>. 
                Jika Anda tidak meminta reset password, abaikan email ini.
            </div>

            <p style="margin-top: 20px; font-size: 13px; color: #888;">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
                <a href="{{ $verifyUrl }}" style="word-break: break-all; color: #007bff;">{{ $verifyUrl }}</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} HRIS System. Semua hak dilindungi.</p>
            <p>Email ini dikirim secara otomatis, mohon jangan membalas.</p>
        </div>
    </div>
</body>
</html>
