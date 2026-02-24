<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Akun</title>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; }
        .header { text-align: center; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px; }
        .code-box { background-color: #fff; border: 2px dashed #ffc107; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #d9534f; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
        .warning { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>HRIS System - Verifikasi Email</h2>
        </div>
        <p>Halo <strong>{{ $userName }}</strong>,</p>
        <p>Terima kasih telah mendaftar sebagai <strong>{{ ucfirst($roleName) }}</strong> di sistem HRIS.</p>
        <p>Untuk alasan keamanan, akses ke dashboard {{ $roleName }} memerlukan verifikasi email tambahan. Silakan masukkan kode di bawah ini pada halaman verifikasi:</p>
        
        <div class="code-box">
            {{ $verificationCode }}
        </div>

        <div class="warning">
            <strong>Peringatan:</strong> Jangan bagikan kode ini kepada siapapun. Kode ini bersifat rahasia dan hanya digunakan untuk mengaktivasi hak akses {{ $roleName }} Anda.
        </div>

        <p>Jika Anda tidak merasa melakukan pendaftaran ini, silakan abaikan email ini.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} HRIS Absensi & Rekrutmen. All rights reserved.
        </div>
    </div>
</body>
</html>
