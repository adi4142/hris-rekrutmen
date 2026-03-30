@extends('emails.layout')

@section('title', 'Verifikasi Akun - ' . config('app.name'))

@section('content')
    <h2 style="color: #0d6efd;">Verifikasi Email</h2>
    <p>Halo <strong>{{ $userName }}</strong>,</p>
    <p>Terima kasih telah mendaftar sebagai <strong>{{ ucfirst($roleName) }}</strong> di sistem HRIS. Untuk alasan keamanan, akses ke dashboard {{ $roleName }} memerlukan verifikasi email tambahan.</p>
    
    <p>Silakan masukkan kode berikut pada halaman verifikasi:</p>
    
    <div style="background-color: #f1f5f9; border: 2px dashed #0d6efd; padding: 20px; text-align: center; margin: 30px 0; border-radius: 8px;">
        <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1e293b; font-family: monospace;">{{ $verificationCode }}</span>
    </div>

    <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; font-size: 13px; color: #856404; margin-bottom: 20px;">
        <strong><i class="fas fa-exclamation-triangle"></i> Peringatan:</strong> Jangan bagikan kode ini kepada siapapun. Kode ini bersifat rahasia dan hanya digunakan untuk mengaktivasi hak akses {{ $roleName }} Anda.
    </div>

    <p style="font-size: 13px; color: #64748b;">Jika Anda tidak merasa melakukan pendaftaran ini, silakan abaikan email ini.</p>

@endsection
