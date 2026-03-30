@extends('emails.layout')

@section('title', 'Offering Letter - ' . $application->jobVacancie->title)

@section('content')
    <h2 style="text-align: center; color: #198754;">Selamat, {{ $application->jobApplicant->name }}!</h2>
    <p style="text-align: center;">Kami dengan senang hati menyampaikan penawaran kerja (Offering Letter) untuk bergabung dengan tim kami.</p>
    <p style="text-align: center; color: #64748b;">Detail rincian penawaran dapat Anda lihat pada <strong>dokumen lampiran (PDF)</strong> email ini.</p>

    <div style="margin: 30px 0; padding: 25px; background-color: #ffffff; border: 2px dashed #e2e8f0; border-radius: 12px; text-align: center;">
        <h4 style="margin-top: 0; color: #1e293b;">Tanggapan Anda</h4>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 25px;">Mohon berikan keputusan Anda terkait penawaran ini melalui tombol di bawah:</p>
        
        <div style="text-align: center; margin: 0 auto; max-width: 100%;">
            <a href="{{ $acceptUrl }}" target="_blank" class="btn" style="background-color: #198754; border: 1px solid #198754;">Terima Penawaran</a>
            <a href="{{ $negotiateUrl }}" target="_blank" class="btn" style="background-color: #f59e0b; border: 1px solid #f59e0b;">Negosiasi Gaji</a>
            <a href="{{ $rejectUrl }}" target="_blank" class="btn" style="background-color: #dc3545; border: 1px solid #dc3545;">Tolak Penawaran</a>
        </div>
    </div>

    <p style="font-size: 14px; text-align: center; color: #475569;">
        Kami berharap Anda dapat bergabung dengan tim kami. Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim HR kami.
    </p>
@endsection
