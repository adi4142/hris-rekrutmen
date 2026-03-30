@extends('emails.layout')

@section('title', 'Update Status Lamaran')

@section('content')
    <h2>Halo, {{ $application->jobApplicant->name }}</h2>
    <p>Kami ingin menginformasikan status terbaru lamaran Anda untuk posisi <strong style="color: #0d6efd;">{{ $application->jobVacancie->title }}</strong>.</p>

    <div class="highlight-box">
        <table class="info-grid">
            <tr>
                <td class="label">Status Lamaran</td>
                <td class="value">
                    @php
                        $statusColors = [
                            'pending'  => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Review'],
                            'process'  => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Dalam Seleksi'],
                            'offering' => ['bg' => '#f0f9ff', 'text' => '#075985', 'label' => 'Tahap Offering'],
                            'accepted' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Diterima'],
                            'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak'],
                        ];
                        $curr = $statusColors[$application->status] ?? ['bg' => '#f1f5f9', 'text' => '#334155', 'label' => ucfirst($application->status)];
                    @endphp
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; background-color: {{ $curr['bg'] }}; color: {{ $curr['text'] }}; font-weight: bold;">
                        {{ $curr['label'] }}
                    </span>
                </td>
            </tr>
            @if(isset($statusLabel) && $statusLabel != $curr['label'])
            <tr>
                <td class="label">Hasil Tahapan</td>
                <td class="value">
                    <span style="color: #64748b;">{{ $statusLabel }}</span>
                </td>
            </tr>
            @endif
        </table>
        <p style="margin: 0; font-style: italic; color: #475569;">"{{ $customMessage }}"</p>
    </div>

    @if($application->status == 'rejected')
        <p>Mohon maaf, lamaran Anda belum dapat kami lanjutkan ke tahap selanjutnya.</p>
    @elseif($application->status == 'process')
        <p>Silakan pantau email secara berkala untuk informasi jadwal seleksi selanjutnya.</p>
    @endif

@endsection
