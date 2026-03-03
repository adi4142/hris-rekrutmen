<!DOCTYPE html>
<html>
<head>
    <title>Akun Baru Dibuat</title>
    <style>
        body { font-family: 'Outfit', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { width: 100%; background-color: #f4f7f6; padding: 40px 0; }
        .container { width: 90%; max-width: 600px; margin: 0 auto; padding: 40px; border-radius: 12px; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { color: #2bb1ff; font-size: 28px; font-weight: 700; text-decoration: none; }
        .content { margin-bottom: 30px; }
        .credential-box { background-color: #f8f9fa; border: 1px solid #e9ecef; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .credential-item { margin-bottom: 10px; }
        .credential-label { font-weight: bold; color: #666; width: 100px; display: inline-block; }
        .btn-container { text-align: center; margin: 35px 0; }
        .btn { background-color: #2bb1ff; color: #ffffff !important; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: background-color 0.3s; }
        .footer { font-size: 13px; color: #888; margin-top: 40px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .warning { color: #d9534f; font-weight: bold; margin-top: 20px; }
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
                <p>Akun Anda untuk sistem HRIS telah berhasil dibuat oleh Super Admin. Berikut adalah detail login Anda:</p>
                
                <div class="credential-box">
                    <div class="credential-item">
                        <span class="credential-label">Email:</span>
                        <strong>{{ $email }}</strong>
                    </div>
                    <div class="credential-item">
                        <span class="credential-label">Password:</span>
                        <strong>{{ $password }}</strong>
                    </div>
                </div>

                <div class="btn-container">
                    <a href="{{ $loginUrl }}" class="btn">Login ke Sistem</a>
                </div>

                <p class="warning">PENTING: Demi keamanan, Anda akan diminta untuk segera mengganti password sementara ini saat pertama kali login.</p>
            </div>
            
            <div class="footer">
                &copy; {{ date('Y') }} HRIS System. Solusi Manajemen SDM Terpadu.<br>
                Email ini dikirim secara otomatis, mohon tidak membalas.
            </div>
        </div>
    </div>
</body>
</html>
