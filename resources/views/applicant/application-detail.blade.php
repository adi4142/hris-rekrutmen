{{-- 
    Detail Lamaran
    Menampilkan detail lengkap lamaran
--}}

@extends('layouts.applicant')

@section('title', 'Detail Ldamaran')
@section('page_title', 'Detail Lamaran')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="{{ route('applicant.applications') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Detail Lowongan --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-briefcase"></i> {{ $application->jobVacancie->title ?? 'Posisi tidak tersedia' }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-building text-primary"></i> 
                            <strong>Departemen:</strong> {{ $application->jobVacancie->departement->name ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-user-tag text-info"></i> 
                            <strong>Posisi:</strong> {{ $application->jobVacancie->position->name ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($application->jobVacancie && $application->jobVacancie->description)
                <h5><i class="fas fa-file-alt"></i> Deskripsi Pekerjaan</h5>
                <div class="mb-4">
                    z
                </div>
                @endif
            </div>
        </div>

        {{-- Proses Seleksi & Jadwal --}}
        @php
            $hasBatch = $application->batch && $application->batch->stages->count() > 0;
            $hasSelectionData = $application->selectionApplicant && $application->selectionApplicant->count() > 0;
        @endphp

        @if($hasBatch || $hasSelectionData)
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i> Alur & Jadwal Seleksi 
                    @if($application->batch) 
                        <span class="badge badge-info ml-2">{{ $application->batch->name }}</span>
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        // Grouping logic:
                        // If hasBatch, we use batch stages.
                        // If not, we use selectionApplicant data.
                        $timelineItems = collect();

                        if($hasBatch) {
                            $stages = $application->batch->stages->sortBy('date')->values();
                            foreach($stages as $stage) {
                                // Find matching result
                                $result = $application->selectionApplicant->where('selection_id', $stage->selection_id)->first();
                                $timelineItems->push([
                                    'date' => $stage->date,
                                    'start_time' => $stage->start_time,
                                    'end_time' => $stage->end_time,
                                    'location' => $stage->location,
                                    'title' => $stage->selection->name,
                                    'result' => $result,
                                    'is_batch_stage' => true
                                ]);
                            }
                        } else {
                            foreach($application->selectionApplicant as $sel) {
                                $timelineItems->push([
                                    'date' => $sel->selection_date, // fallback
                                    'title' => $sel->selection->name,
                                    'result' => $sel,
                                    'is_batch_stage' => false
                                ]);
                            }
                        }
                        
                        $groupedItems = $timelineItems->groupBy(function($item) {
                            return $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('Y-m-d') : 'TBA';
                        });
                    @endphp

                    @foreach($groupedItems as $dateStr => $items)
                        <div class="time-label">
                            <span class="{{ $dateStr == 'TBA' ? 'bg-gray' : 'bg-primary' }}">
                                {{ $dateStr == 'TBA' ? 'Jadwal Menyusul' : \Carbon\Carbon::parse($dateStr)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        @foreach($items as $item)
                            @php
                                $res = $item['result'];
                                $status = $res ? $res->status : 'unprocess';
                                
                                $bgClass = 'bg-gray'; $icon = 'fa-clock';
                                if($status == 'passed') { $bgClass = 'bg-success'; $icon = 'fa-check'; }
                                elseif($status == 'failed') { $bgClass = 'bg-danger'; $icon = 'fa-times'; }
                                elseif($status == 'process') { $bgClass = 'bg-primary'; $icon = 'fa-spinner fa-spin'; }
                                elseif($status == 'unprocess') { $bgClass = 'bg-warning'; $icon = 'fa-calendar'; }
                            @endphp

                            <div>
                                <i class="fas {{ $icon }} {{ $bgClass }}"></i>
                                <div class="timeline-item shadow-sm">
                                    <span class="time">
                                        @if(isset($item['start_time']))
                                            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item['start_time'])->format('H:i') }}
                                        @endif
                                    </span>
                                    <h3 class="timeline-header">
                                        <strong>{{ $item['title'] }}</strong>
                                        @if($status == 'passed') <span class="badge badge-success float-right">Lulus</span>
                                        @elseif($status == 'failed') <span class="badge badge-danger float-right">Gagal</span>
                                        @elseif($status == 'process') <span class="badge badge-primary float-right">Proses</span>
                                        @else <span class="badge badge-secondary float-right">Belum Mulai</span>
                                        @endif
                                    </h3>

                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-7">
                                                @if(isset($item['location']) && $item['location'])
                                                    <p class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $item['location'] }}</p>
                                                @endif
                                                
                                                @if($res && $res->notes)
                                                    <div class="mt-2 p-2 bg-light rounded border-left border-info">
                                                        <span class="text-muted font-italic">Catatan:</span><br>
                                                        {{ $res->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-5">
                                                @if($res && $res->aspectScores->count() > 0)
                                                    <h6 class="font-weight-bold mb-2">Penilaian:</h6>
                                                    @foreach($res->aspectScores as $as)
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="text-muted">{{ $as->aspect->name ?? 'Aspek' }}</span>
                                                            <div class="stars-display text-warning">
                                                                @for($i=1; $i<=5; $i++)
                                                                    <i class="{{ $i <= $as->score ? 'fas' : 'far' }} fa-star"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-info shadow-sm">
            <h5><i class="icon fas fa-info"></i> Belum Ada Jadwal Seleksi</h5>
            Saat ini belum ada tahapan seleksi yang dijadwalkan untuk lamaran Anda. Mohon menunggu informasi selanjutnya dari tim HR kami.
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Status Lamaran --}}
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Status Lamaran
                </h3>
            </div>
            <div class="card-body text-center">
                @if($application->status == 'pending' || $application->status == 'applied')
                    <span class="badge badge-warning" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-clock"></i> Review Berkas
                    </span>
                    <p class="mt-3 text-muted">
                        Lamaran Anda sedang dalam proses review oleh tim HRD.
                    </p>
                @elseif($application->status == 'accepted')
                    <span class="badge badge-success" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-check"></i> Diterima
                    </span>
                    <p class="mt-3 text-success">
                        <strong>Selamat!</strong> Lamaran Anda telah diterima.
                    </p>
                @elseif($application->status == 'rejected')
                    <span class="badge badge-danger" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-times"></i> Ditolak
                    </span>
                    <p class="mt-3 text-muted">
                        Mohon maaf, lamaran Anda tidak dapat kami proses lebih lanjut.
                    </p>
                @elseif($application->status == 'process')
                    <span class="badge badge-primary" style="font-size: 1.5rem; padding: 15px 30px;">
                        <i class="fas fa-spinner fa-spin"></i> Dalam Proses
                    </span>
                    <p class="mt-3 text-muted">
                        Lamaran Anda sedang dalam proses seleksi oleh tim HRD.
                    </p>
                @endif
                <hr>
                <p class="mb-1"><strong>Tanggal Melamar:</strong></p>
                <p>{{ $application->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
