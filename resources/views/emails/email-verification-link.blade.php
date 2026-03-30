@extends('emails.layout')

@section('title', 'Verifikasi Akun - ' . config('app.name'))

@section('content')
    <h2 style="color: #0d6efd;">Verifikasi Email</h2>
    <p>Halo <strong>{{ $userName }}</strong>,</p>
    <p>Terima kasih telah mendaftar di <strong>HRIS System</strong> sebagai <strong>{{ $roleName }}</strong>.</p>
    <p>Satu langkah lagi untuk mengaktifkan akun Anda. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $verifyUrl }}" class="btn" style="padding: 14px 28px; font-size: 16px;">
            <i class="fas fa-check-circle"></i> Verifikasi Email Saya
        </a>
    </div>

    <p style="font-size: 14px; color: #475569;">Atau Anda juga dapat menyalin dan menempelkan tautan berikut ke browser Anda:</p>
    <div style="background-color: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 12px; word-break: break-all; color: #0d6efd;">
        {{ $verifyUrl }}
    </div>

    <p style="margin-top: 30px; font-size: 13px; color: #64748b;">Jika Anda tidak merasa melakukan pendaftaran ini, Anda dapat mengabaikan email ini dengan aman.</p>
    
@endsection
