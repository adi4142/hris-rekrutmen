@extends('emails.layout')

@section('title', 'Update Tahapan Seleksi - ' . $jobTitle)

@section('content')
    <h2 style="color: #0d6efd;">Pembaruan Status Seleksi</h2>
    <p>Halo <strong>{{ $applicantName }}</strong>,</p>
    <p>Berikut adalah informasi terbaru mengenai proses seleksi lamaran Anda untuk posisi <strong style="color: #0d6efd;">{{ $jobTitle }}</strong>:</p>
    
    <div class="highlight-box">
        <h4 style="margin-top: 0; color: #1e293b;">Detail Tahapan</h4>
        <table class="info-grid">
            <tr>
                <td class="label">Tahapan Seleksi</td>
                <td class="value">{{ $selectionStage }}</td>
            </tr>
            <tr>
                <td class="label">Status Tahapan</td>
                <td class="value">
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; background-color: {{ $status == 'passed' ? '#dcfce7' : ($status == 'failed' ? '#fee2e2' : '#f1f5f9') }}; color: {{ $status == 'passed' ? '#166534' : ($status == 'failed' ? '#991b1b' : '#334155') }};">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @if($selectionDate)
            <tr>
                <td class="label">Tanggal Seleksi</td>
                <td class="value">{{ \Carbon\Carbon::parse($selectionDate)->translatedFormat('d F Y') }}</td>
            </tr>
            @endif
        </table>
        
        @if($notes && $notes != '-')
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ced4da;">
                <p style="margin: 0; font-size: 13px; color: #64748b;"><strong>Catatan:</strong></p>
                <p style="margin: 5px 0 0; font-style: italic; font-size: 14px;">"{{ $notes }}"</p>
            </div>
        @endif
    </div>
@endsection
