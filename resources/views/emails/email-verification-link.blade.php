<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Akun</title>
    <style>
        body { font-family: 'Outfit', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { width: 100%; background-color: #f4f7f6; padding: 40px 0; }
        .container { width: 90%; max-width: 600px; margin: 0 auto; padding: 40px; border-radius: 12px; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { color: #2bb1ff; font-size: 28px; font-weight: 700; text-decoration: none; }
        .content { margin-bottom: 30px; }
        .btn-container { text-align: center; margin: 35px 0; }
        .btn { background-color: #2bb1ff; color: #ffffff !important; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: background-color 0.3s; }
        .btn:hover { background-color: #1750de; }
        .footer { font-size: 13px; color: #888; margin-top: 40px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .link-text { word-break: break-all; color: #2bb1ff; font-size: 12px; margin-top: 15px; display: block; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <a href="#" class="logo">HRIS System</a>
            </div>
            <div class="content">
                <p>Halo <strong>{{ $userName }}</strong>,</p>
                <p>Terima kasih telah mendaftar di <strong>HRIS System</strong> sebagai <strong>{{ $roleName }}</strong>.</p>
                <p>Satu langkah lagi untuk mengaktifkan akun Anda. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
                
                <div class="btn-container">
                    <a href="{{ $verifyUrl }}" class="btn">Verifikasi Email Saya</a>
                </div>

                <p>Atau Anda juga dapat menyalin dan menempelkan tautan berikut ke browser Anda:</p>
                <span class="link-text">{{ $verifyUrl }}</span>
            </div>

            <p>Jika Anda tidak merasa melakukan pendaftaran ini, Anda dapat mengabaikan email ini dengan aman.</p>
            
            <div class="footer">
                &copy; {{ date('Y') }} HRIS System. Solusi Manajemen SDM Terpadu.<br>
                Email ini dikirim secara otomatis, mohon tidak membalas.
            </div>
        </div>
    </div>
</body>
</html>
