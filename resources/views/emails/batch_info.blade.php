@extends('emails.layout')

@section('title', 'Jadwal Rekrutmen: ' . $batch->name)

@section('content')
    <h2>Halo, {{ $application->jobApplicant->name }}!</h2>
    <p>Anda telah dijadwalkan untuk mengikuti tahapan seleksi dalam <strong>Batch: {{ $batch->name }}</strong> untuk posisi <strong style="color: #0d6efd;">{{ $application->jobVacancie->title }}</strong>.</p>

    <div class="highlight-box">
        <h4 style="margin-top: 0; color: #0d6efd;">Detail Batch</h4>
        <table class="info-grid" style="margin-top: 10px;">
            <tr>
                <td class="label">Periode Mulai</td>
                <td class="value">{{ \Carbon\Carbon::parse($batch->date)->translatedFormat('d F Y') }}</td>
            </tr>
            @if($batch->description)
            <tr>
                <td class="label">Keterangan</td>
                <td class="value">{{ $batch->description }}</td>
            </tr>
            @endif
        </table>
    </div>

    <h4 style="color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Rincian Tahapan Seleksi</h4>
    <div style="margin-top: 20px;">
        @foreach($batch->stages->sortBy('date')->groupBy('date') as $date => $stages)
            <div style="background-color: #f8fafc; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
                <h5 style="margin: 0; color: #1e293b; font-size: 15px;">
                    <i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                </h5>
            </div>
            
            <div style="margin-bottom: 30px; padding-left: 10px;">
                @foreach($stages->sortBy('start_time') as $stage)
                    <div style="margin-bottom: 20px; border-left: 3px solid #0d6efd; padding-left: 15px; position: relative;">
                        <div style="font-weight: 700; color: #0d6efd; font-size: 15px; margin-bottom: 5px;">
                            {{ $stage->selection->name }} 
                            <span style="font-weight: 400; font-size: 13px; color: #64748b; margin-left: 8px;">
                                ({{ \Carbon\Carbon::parse($stage->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($stage->end_time)->format('H:i') }})
                            </span>
                        </div>
                        <table class="info-grid" style="width: 100%;">
                            <tr>
                                @if($stage->location)
                                <td class="label" style="width: 80px;">Lokasi</td>
                                <td class="value">{{ $stage->location }}</td>
                                @else
                                <td class="label" style="width: 80px;">Link Zoom</td>
                                <td class="value"><a href="{{ $stage->zoom_link }}">{{ $stage->zoom_link }}</a></td>
                                @endif
                            </tr>
                            @if($stage->description)
                            <tr>
                                <td class="label">Catatan</td>
                                <td class="value" style="font-weight: 400; font-size: 12px; color: #475569;">{{ $stage->description }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <p style="margin-top: 30px; background-color: #f8fafc; padding: 15px; border-radius: 6px; font-size: 14px; color: #475569;">
        Mohon hadir tepat waktu dan persiapkan diri Anda dengan baik. Jika ada pertanyaan, silakan hubungi tim HR kami.
    </p>
@endsection
