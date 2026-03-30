@extends('emails.layout')

@section('title', 'Reset Password - ' . config('app.name'))

@section('content')
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #0d6efd;">Permintaan Reset Password</h2>
    </div>

    <p>Halo <strong>{{ $userName }}</strong>,</p>
    <p>Kami otomatis mengirimkan email ini karena menerima permintaan untuk mereset password akun HRIS Anda. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan melanjutkan proses reset password:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $verifyUrl }}" class="btn btn-success" style="padding: 14px 28px; font-size: 16px;">
            <i class="fas fa-key"></i> Verifikasi Email & Reset Password
        </a>
    </div>

    <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; font-size: 13px; color: #856404; margin-bottom: 20px;">
        <strong><i class="fas fa-exclamation-triangle"></i> Penting:</strong> Link verifikasi reset password ini hanya berlaku selama <strong>1 jam</strong>. Jika Anda tidak merasa meminta reset password, silakan abaikan dan hapus email ini.
    </div>

    <p style="font-size: 14px; color: #475569;">Jika tombol di atas tidak berfungsi, salin dan tempel alamat link berikut ke browser Anda:</p>
    <div style="background-color: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 12px; word-break: break-all; color: #0d6efd;">
        {{ $verifyUrl }}
    </div>

@endsection
