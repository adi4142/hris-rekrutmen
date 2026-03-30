@extends('emails.layout')

@section('title', 'Akun Sistem Baru Dibuat')

@section('content')
    <h2 style="color: #0d6efd;">Akses Sistem HRIS Anda</h2>
    <p>Halo <strong>{{ $userName }}</strong>,</p>
    <p>Akun Anda untuk sistem HRIS telah berhasil dibuat oleh Super Admin. Anda kini dapat mengakses sistem dengan menggunakan detail login berikut:</p>
    
    <div class="highlight-box">
        <h4 style="margin-top: 0; color: #1e293b;">Kredensial Login</h4>
        <table class="info-grid">
            <tr>
                <td class="label">Email</td>
                <td class="value">{{ $email }}</td>
            </tr>
            <tr>
                <td class="label">Password Sementara</td>
                <td class="value"><code style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-family: monospace;">{{ $password }}</code></td>
            </tr>
        </table>
        <p style="margin-bottom: 0; font-size: 13px; color: #dc3545; font-weight: bold;">
            <i class="fas fa-exclamation-triangle"></i> PENTING: Demi keamanan data perusahaan, Anda wajib segera mengganti password sementara ini saat pertama kali login.
        </p>
    </div>
@endsection
