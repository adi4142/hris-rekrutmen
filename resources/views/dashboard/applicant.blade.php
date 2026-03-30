@extends('layouts.applicant')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Pelamar')

@section('content')
<div class="container-fluid">
    @if($latestApplication)
        @php
            $status = $latestApplication->status;
            $bannerData = [
                'pending' => ['color' => 'warning', 'icon' => 'fa-clock', 'title' => 'Review Berkas', 'desc' => 'Lamaran Anda telah kami terima dan sedang dalam antrean pemeriksaan oleh tim Rekrutmen.'],
                'applied' => ['color' => 'warning', 'icon' => 'fa-clock', 'title' => 'Review Berkas', 'desc' => 'Lamaran Anda telah kami terima dan sedang dalam antrean pemeriksaan oleh tim Rekrutmen.'],
                'process' => ['color' => 'info', 'icon' => 'fa-sync-alt fa-spin', 'title' => 'Tahap Seleksi', 'desc' => 'Selamat! Berkas Anda lolos screening. Mohon periksa jadwal seleksi Anda di bawah ini.'],
                'offering' => ['color' => 'primary', 'icon' => 'fa-file-contract', 'title' => 'Penawaran Kerja', 'desc' => 'Kami sangat terkesan! Silakan tinjau rincian penawaran kerja yang kami berikan.'],
                'offering_sent' => ['color' => 'primary', 'icon' => 'fa-file-contract', 'title' => 'Penawaran Kerja', 'desc' => 'Kami telah mengirimkan penawaran kerja (Offering Letter). Silakan tinjau rincian penawaran di bawah ini.'],
                'negotiation_requested' => ['color' => 'warning', 'icon' => 'fa-comments', 'title' => 'Negosiasi Gaji', 'desc' => 'Permintaan negosiasi Anda sedang dalam tinjauan tim HR kami. Mohon tunggu informasi selanjutnya.'],
                'accepted' => ['color' => 'success', 'icon' => 'fa-check-circle', 'title' => 'Bergabung!', 'desc' => 'Selamat datang di tim! Lamaran Anda telah resmi diterima dan proses selanjutnya akan segera dimulai.'],
                'hired' => ['color' => 'success', 'icon' => 'fa-briefcase', 'title' => 'Hired!', 'desc' => 'Selamat! Anda telah resmi bergabung dengan perusahaan kami. Sampai jumpa di hari pertama kerja!'],
                'rejected' => ['color' => 'danger', 'icon' => 'fa-times-circle', 'title' => 'Lamaran Selesai', 'desc' => 'Terima kasih atas minat Anda. Saat ini profil Anda belum sesuai, namun kami simpan untuk peluang mendatang.'],
            ];
            $currentBanner = $bannerData[$status] ?? $bannerData['pending'];
        @endphp

        {{-- Status Callout --}}
        <div class="callout callout-{{ $currentBanner['color'] }}">
            <div class="row">
                <div class="col-md-9">
                    <h5><i class="fas {{ $currentBanner['icon'] }} mr-2"></i> {{ $currentBanner['title'] }}</h5>
                    <p>{{ $currentBanner['desc'] }}</p>
                </div>
                <div class="col-md-3 text-md-right border-left">
                    <span class="text-muted d-block small">Posisi Dilamar:</span>
                    <h6 class="font-weight-bold">{{ $latestApplication->jobVacancie->title }}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Main Timeline Card --}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-route mr-1"></i> Alur Seleksi & Jadwal
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $hasBatch = $latestApplication->batch && $latestApplication->batch->stages->count() > 0;
                            $hasSelectionData = $latestApplication->selectionApplicant && $latestApplication->selectionApplicant->count() > 0;
                        @endphp

                        @if($hasBatch || $hasSelectionData)
                            <div class="timeline">
                                @php
                                    $timelineItems = collect();
                                    if($hasBatch) {
                                        foreach($latestApplication->batch->stages->sortBy('date') as $stage) {
                                            $res = $latestApplication->selectionApplicant->where('selection_id', $stage->selection_id)->first();
                                            $timelineItems->push([
                                                'title' => $stage->selection->name ?? 'Seleksi',
                                                'date' => $stage->date, 'start' => $stage->start_time, 'location' => $stage->location,
                                                'res' => $res, 'status' => $res ? $res->status : 'unprocess'
                                            ]);
                                        }
                                    } else {
                                        foreach($latestApplication->selectionApplicant as $sel) {
                                            $timelineItems->push([
                                                'title' => $sel->selection->name ?? 'Tahapan Seleksi',
                                                'date' => $sel->selection_date, 'res' => $sel, 'status' => $sel->status
                                            ]);
                                        }
                                    }
                                @endphp

                                @foreach($timelineItems as $item)
                                    @php
                                        $iconClass = 'fa-clock bg-gray';
                                        $labelColor = 'bg-gray';
                                        if($item['status'] == 'passed') {
                                            $iconClass = 'fa-check bg-success';
                                            $labelColor = 'bg-success';
                                        } elseif($item['status'] == 'failed') {
                                            $iconClass = 'fa-times bg-danger';
                                            $labelColor = 'bg-danger';
                                        } elseif($item['status'] == 'process') {
                                            $iconClass = 'fa-spinner fa-spin bg-primary';
                                            $labelColor = 'bg-primary';
                                        }
                                    @endphp
                                    
                                    <div class="time-label">
                                        <span class="{{ $labelColor }}">
                                            {{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') : 'Jadwal Segera' }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <i class="fas {{ $iconClass }}"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i> {{ isset($item['start']) ? \Carbon\Carbon::parse($item['start'])->format('H:i') : '' }}
                                            </span>
                                            <h3 class="timeline-header"><strong>{{ $item['title'] }}</strong></h3>
                                            
                                            <div class="timeline-body">
                                                @if(isset($item['location']) && $item['location'])
                                                    <p class="text-sm mb-2"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $item['location'] }}</p>
                                                @endif

                                                @if($item['status'] == 'passed') <span class="badge badge-success">Lulus</span>
                                                @elseif($item['status'] == 'failed') <span class="badge badge-danger">Gagal</span>
                                                @elseif($item['status'] == 'process') <span class="badge badge-primary">Sedang Dinilai</span>
                                                @else <span class="badge badge-secondary">Belum Mulai</span>
                                                @endif

                                                @if($item['res'] && ($item['res']->notes || $item['res']->aspectScores->count() > 0))
                                                    <div class="mt-3 p-2 bg-light rounded shadow-sm border">
                                                        @if($item['res']->notes)
                                                            <p class="mb-2 text-sm"><em>"{{ $item['res']->notes }}"</em></p>
                                                        @endif
                                                        
                                                        @if($item['res']->aspectScores->count() > 0)
                                                            <div class="row">
                                                                @foreach($item['res']->aspectScores as $as)
                                                                    <div class="col-sm-6">
                                                                        <small class="d-block text-muted">{{ $as->aspect->name ?? 'Kriteria' }}</small>
                                                                        <div class="text-warning mb-2">
                                                                            @for($i=1; $i<=5; $i++)
                                                                                <i class="{{ $i <= $as->score ? 'fas' : 'far' }} fa-star fa-xs"></i>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div>
                                    <i class="fas fa-flag-checkered bg-gray"></i>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tahapan seleksi Anda masih disiapkan oleh tim kami.<br>Silakan pantau halaman ini secara berkala.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div> {{-- /.col-lg-8 --}}

            <div class="col-lg-4">
                {{-- Quick Info Card --}}
                <div class="card bg-gradient-dark">
                    <div class="card-header border-bottom-0">
                        <h3 class="card-title">Informasi Lamaran</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-2">
                            <span class="text-xs text-uppercase d-block opacity-7">Departemen</span>
                            <span class="font-weight-medium">{{ $latestApplication->jobVacancie->departement->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-xs text-uppercase d-block opacity-7">Divisi</span>
                            <span class="font-weight-medium">{{ $latestApplication->jobVacancie->division->name ?? '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-xs text-uppercase d-block opacity-7">Diajukan Pada</span>
                            <span class="font-weight-medium">{{ $latestApplication->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                </div>
                {{-- Job Description Card --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt mr-1"></i> Deskripsi Pekerjaan
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-muted">
                            @if($latestApplication->jobVacancie->description)
                                {!! nl2br(e($latestApplication->jobVacancie->description)) !!}
                            @else
                                <p class="font-italic">Detail deskripsi tidak dilampirkan.</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Offering Card --}}
                @if(in_array($status, ['offering', 'offering_sent', 'negotiation_requested', 'hired', 'accepted']) || $latestApplication->offering_salary)
                    <div class="card card-outline card-warning shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title text-bold"><i class="fas fa-award mr-1"></i> Detail Penawaran</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-muted small d-block">Gaji Ditawarkan</span>
                                <h4 class="text-success font-weight-bold">Rp {{ number_format($latestApplication->offering_salary, 0, ',', '.') }}</h4>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <span class="text-muted small d-block">Tanggal Mulai</span>
                                    <p class="font-weight-bold mb-0 text-sm text-primary">{{ $latestApplication->offering_start_date ? \Carbon\Carbon::parse($latestApplication->offering_start_date)->translatedFormat('d F Y') : '-' }}</p>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block">Cuti Tahunan</span>
                                    <p class="font-weight-bold mb-0 text-sm">{{ $latestApplication->offering_leave_quota ?? '12' }} Hari</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="text-muted small d-block">Jam Kerja</span>
                                <p class="font-weight-bold mb-0 text-sm">{{ $latestApplication->offering_working_hours ?? '8 Jam/Hari' }}</p>
                            </div>

                            @if(in_array($status, ['offering', 'offering_sent']))
                                <div class="mt-4">
                                    <div class="btn-group w-100">
                                        <a href="{{ route('offering.respond', [$latestApplication->application_id, 'accept']) }}" 
                                           class="btn btn-success"
                                           onclick="return confirm('Apakah Anda yakin menerima penawaran ini?')">
                                            <i class="fas fa-check-circle mr-1"></i> Terima
                                        </a>
                                        <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#negotiateModal">
                                            <i class="fas fa-comments mr-1"></i> Negosiasi
                                        </button>
                                    </div>
                                    <a href="{{ route('offering.respond', [$latestApplication->application_id, 'reject']) }}" 
                                       class="btn btn-outline-danger btn-block btn-sm mt-2"
                                       onclick="return confirm('Apakah Anda yakin menolak penawaran ini?')">
                                        Tolak & Selesaikan
                                    </a>
                                    <p class="small text-muted mt-2 mb-0 italic text-center">*Link persetujuan resmi juga telah dikirim ke email Anda.</p>
                                </div>
                            @elseif($status == 'negotiation_requested')
                                <div class="mt-3 p-2 bg-light rounded text-center border">
                                    <small class="text-muted font-weight-bold">Status: Menunggu Respon HR</small>
                                    <p class="mb-0 text-sm mt-1">Negosiasi Gaji: <strong>Rp {{ number_format($latestApplication->expected_salary, 0, ',', '.') }}</strong></p>
                                </div>
                            @elseif(in_array($status, ['hired', 'accepted']))
                                <div class="mt-3 alert alert-success py-2 text-center">
                                    <i class="fas fa-check-double mr-1"></i> Penawaran telah Diterima
                                </div>
                            @endif
                            
                            @if($latestApplication->hr_negotiation_note)
                                <div class="mt-3 small p-2 bg-light border-left border-info">
                                    <span class="text-muted d-block small mb-1">Catatan HR:</span>
                                    <em>"{{ $latestApplication->hr_negotiation_note }}"</em>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Document Card --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-folder-open mr-1"></i> Dokumen Lamaran</h3>
                    </div>
                    <div class="card-body p-0">
                        @if($latestApplication->documents && is_array($latestApplication->documents))
                            <ul class="list-group list-group-flush">
                                @foreach($latestApplication->documents as $doc)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="text-truncate" style="max-width: 80%;">
                                            <i class="far fa-file-alt mr-2 text-info"></i>
                                            <span class="text-sm">{{ $doc['name'] }}</span>
                                        </div>
                                        <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="btn btn-xs btn-link p-0">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted p-3">Tidak ada file terlampir.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    @endif
</div>

@if($latestApplication && in_array($latestApplication->status, ['offering', 'offering_sent']))
{{-- MODAL NEGOTIATION --}}
<div class="modal fade" id="negotiateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <form action="{{ route('offering.negotiate', $latestApplication->application_id) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-comments mr-2"></i> Ajukan Negosiasi Gaji</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border small shadow-sm">
                        <i class="fas fa-info-circle text-info mr-1"></i> Penawaran saat ini: <strong>Rp {{ number_format($latestApplication->offering_salary, 0, ',', '.') }}</strong>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Ekspektasi Gaji (Rp)</label>
                        <input type="number" name="expected_salary" class="form-control form-control-lg" placeholder="Contoh: 7500000" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Alasan / Pertimbangan (Opsional)</label>
                        <textarea name="negotiation_reason" class="form-control" rows="3" placeholder="Sebutkan alasan atau prestasi yang mendasari negosiasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-white shadow-sm">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

