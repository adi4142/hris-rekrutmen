@extends('layouts.admin')
@section('title', 'Managemen Batch')
@section('page_title', 'Managemen Batch Seleksi')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar { box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; border: 1px solid #ddd !important; }
    .stage-time-input { background-color: #f8f9fa !important; border: 0 !important; font-weight: 600; }
    .overlap-warning { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
</style>
@endpush

@section('content')
{{-- Validation Errors Display --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5 class="mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Perhatian:</h5>
    <ul class="mb-0 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i> Di sini Anda dapat membuat jadwal Batch seleksi untuk setiap lowongan yang aktif. Satu lowongan dapat memiliki beberapa batch (misal Batch 1 Senin, Batch 2 Selasa).
        </div>
    </div>
</div>

@foreach($vacancies as $vacancy)
@php $cardId = "vacancy-".$vacancy->vacancies_id; @endphp
<div class="card card-outline card-primary collapsed-card mb-4" data-card-id="{{ $cardId }}" id="card-{{ $cardId }}">
    <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
        <h3 class="card-title font-weight-bold mt-1">
            <i class="fas fa-briefcase mr-2 text-primary"></i> {{ $vacancy->title }} 
            <small class="text-muted ml-2">({{ $vacancy->departement->name }})</small>
            <span class="badge badge-info ml-2">{{ count($vacancy->batches) }} Batch</span>
            <span class="badge badge-light ml-1"><i class="fas fa-users mr-1"></i>{{ $vacancy->job_applications_count }} Pelamar</span>
            <span class="badge badge-warning ml-1 text-dark">Batas: {{ \Carbon\Carbon::parse($vacancy->expired_at)->format('d M Y') }}</span>
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div class="card-body p-0" id="body-{{ $cardId }}" style="display: none;">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">Daftar Batch Seleksi</h6>
            <div>
                <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddBatch-{{ $vacancy->vacancies_id }}">
                    <i class="fas fa-plus mr-1"></i> Buat Batch Baru
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 220px">Nama Batch</th>
                        <th style="width: 180px">Periode</th>
                        <th>Tahapan Seleksi</th>
                        <th style="width: 120px">Status</th>
                        <th style="width: 150px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacancy->batches as $batch)
                    <tr>
                        <td class="align-middle">
                            <div class="font-weight-bold">{{ $batch->name }}</div>
                            <div class="d-flex flex-wrap mt-1">
                                <span class="badge badge-pill badge-light border mr-1 mb-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-user-friends mr-1 text-primary"></i>{{ $batch->applications_count }} / {{ $batch->quota ?? '∞' }} Pelamar
                                </span>
                            </div>
                        </td>
                        <td class="align-middle">
                            @php
                                $stageDates = $batch->stages->pluck('date')->filter()->sort();
                                $minDate = $stageDates->first();
                                $maxDate = $stageDates->last();
                            @endphp
                            @if($minDate && $maxDate && $minDate !== $maxDate)
                                <span class="text-nowrap small">
                                    <i class="far fa-calendar-alt text-primary mr-1"></i>
                                    {{ \Carbon\Carbon::parse($minDate)->format('d M') }} &ndash; {{ \Carbon\Carbon::parse($maxDate)->format('d M Y') }}
                                </span>
                            @elseif($minDate)
                                <span class="text-nowrap small">
                                    <i class="far fa-calendar-alt text-primary mr-1"></i>
                                    {{ \Carbon\Carbon::parse($minDate)->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($batch->date)->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @php
                                $groupedStages = $batch->stages->groupBy('date')->sortKeys();
                            @endphp
                            
                            @foreach($groupedStages as $date => $stages)
                                <div class="mb-3">
                                    <div class="badge badge-light border mb-2 text-dark font-weight-bold small">
                                        <i class="far fa-calendar-alt text-primary mr-1"></i> 
                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                    </div>
                                    <ul class="list-unstyled mb-0" style="position: relative; padding-left: 20px; border-left: 2px solid #e9ecef; margin-left: 10px;">
                                        @foreach($stages->sortBy('start_time') as $stage)
                                        <li class="mb-2" style="position: relative;">
                                            <span class="bg-primary shadow-sm" style="position: absolute; left: -26px; top: 4px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white;"></span>
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong class="text-dark small" style="font-weight: 600;">{{ $stage->selection->name }}</strong>
                                                        @if($stage->room_url)
                                                            <a href="{{ $stage->room_url }}" target="_blank" class="ml-1 text-info small" title="Link Meeting">
                                                                <i class="fas fa-video"></i> Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <span class="text-muted" style="font-size: 0.7rem;">
                                                        <i class="far fa-clock text-info"></i> {{ \Carbon\Carbon::parse($stage->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($stage->end_time)->format('H:i') }}
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-wrap mt-1">
                                                    @if($stage->location)
                                                        <span class="text-muted mr-2" style="font-size: 0.65rem;">
                                                            <i class="fas fa-map-marker-alt text-danger"></i> {{ $stage->location }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </td>
                        <td class="align-middle">
                            @php $autoStatus = $batch->computed_status; @endphp
                            @if($autoStatus == 'active')
                                <span class="badge badge-success px-2 py-1 small mb-1"><i class="fas fa-play-circle mr-1"></i>Active</span>
                            @elseif($autoStatus == 'closed')
                                <span class="badge badge-danger px-2 py-1 small mb-1"><i class="fas fa-lock mr-1"></i>Closed</span>
                            @elseif($autoStatus == 'draft')
                                <span class="badge badge-warning px-2 py-1 text-white small mb-1"><i class="fas fa-pencil-alt mr-1"></i>Draft</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1 small mb-1">{{ ucfirst($autoStatus) }}</span>
                            @endif
                            
                            @if($batch->description)
                                <div class="small text-muted font-italic mt-1" style="font-size: 0.7rem; max-width: 120px;">
                                    <i class="fas fa-sticky-note mr-1"></i> Catatan tersedia
                                </div>
                            @endif
                        </td>
                        <td class="align-middle text-center" style="white-space: nowrap;">
                            <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                <button type="button" class="btn btn-xs btn-warning text-white" data-toggle="modal" data-target="#modalEditBatch-{{ $batch->id }}" title="Edit Batch">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('recruitment-batch.destroy', $batch->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus batch ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus Batch">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times mb-2 fa-2x opacity-50"></i><br>
                            Belum ada batch seleksi untuk lowongan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach


{{-- MODALS CREATE & EDIT BATCH --}}
@foreach($vacancies as $vacancy)
    <!-- Modal Add Batch -->
    <div class="modal fade" id="modalAddBatch-{{ $vacancy->vacancies_id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('recruitment-batch.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vacancies_id" value="{{ $vacancy->vacancies_id }}">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Buat Jadwal Batch - {{ $vacancy->title }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <label>Nama Batch <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Batch 1 (Area Surabaya)" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Kapasitas (Quota)</label>
                                <input type="number" name="quota" class="form-control" placeholder="Tanpa Batas">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Status Awal <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="draft">📝 Draft</option>
                                    <option value="active" selected>▶ Active</option>
                                    <option value="closed">🔒 Closed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan / Instruksi Batch <small class="text-muted">(Opsional)</small></label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Tulis instruksi umum untuk batch ini..."></textarea>
                        </div>
                        
                        <div id="date-groups-container-add-{{ $vacancy->vacancies_id }}" class="mt-3">
                            {{-- Date Groups will be added here --}}
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-block mt-3 btn-add-date-group" data-target="#date-groups-container-add-{{ $vacancy->vacancies_id }}">
                            <i class="fas fa-calendar-plus mr-1"></i> Tambah Grup Tanggal Seleksi
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">Simpan Jadwal Batch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($vacancy->batches as $batch)
    <!-- Modal Edit Batch -->
    <div class="modal fade" id="modalEditBatch-{{ $batch->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('recruitment-batch.update', $batch->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title font-weight-bold">Edit Jadwal Batch - {{ $vacancy->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <label>Nama Batch <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $batch->name }}" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Kapasitas (Quota)</label>
                                <input type="number" name="quota" class="form-control" value="{{ $batch->quota }}" placeholder="Tanpa Batas">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Status Batch <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="draft" {{ $batch->status == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="active" {{ $batch->status == 'active' ? 'selected' : '' }}>▶ Active</option>
                                    <option value="closed" {{ $batch->status == 'closed' ? 'selected' : '' }}>🔒 Closed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan / Instruksi Batch <small class="text-muted">(Opsional)</small></label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Tulis instruksi umum untuk batch ini...">{{ $batch->description }}</textarea>
                        </div>

                        <div id="date-groups-container-edit-{{ $batch->id }}" class="mt-3">
                            @php
                                $groupedStages = $batch->stages->groupBy(function($s) { 
                                    return \Carbon\Carbon::parse($s->date)->format('Y-m-d'); 
                                });
                            @endphp

                            @foreach($groupedStages as $date => $stages)
                            <div class="card card-outline card-secondary date-group mb-3">
                                <div class="card-header p-2 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-day mr-2 text-primary"></i>
                                        <input type="text" class="form-control form-control-sm group-date-input" value="{{ $date }}" style="width: 150px;" onchange="updateStageDates(this)" placeholder="Pilih Tanggal">
                                    </div>
                                    <button type="button" class="btn btn-tool text-danger remove-date-group"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body p-2 text-sm">
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="small text-muted text-uppercase">
                                                <th style="width: 40%">Tahapan Seleksi</th>
                                                <th style="width: 25%">Waktu (Mulai - Selesai)</th>
                                                <th style="width: 30%">Lokasi / Link Room</th>
                                                <th style="width: 5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="stages-tbody">
                                            @foreach($stages as $stage)
                                            <tr class="stage-row border-bottom">
                                                <td>
                                                    <input type="hidden" name="stages[{{ $loop->parent->index . $loop->index }}][date]" class="stage-date-hidden" value="{{ $date }}">
                                                    <select name="stages[{{ $loop->parent->index . $loop->index }}][selection_id]" class="form-control form-control-sm border-0 bg-light" required>
                                                        @foreach($selections as $sel)
                                                            <option value="{{ $sel->selection_id }}" {{ $stage->selection_id == $sel->selection_id ? 'selected' : '' }}>{{ $sel->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" name="stages[{{ $loop->parent->index . $loop->index }}][start_time]" class="form-control form-control-sm border-0 bg-light px-1 stage-time-input" value="{{ $stage->start_time }}" placeholder="08:00">
                                                        <span class="mx-1">-</span>
                                                        <input type="text" name="stages[{{ $loop->parent->index . $loop->index }}][end_time]" class="form-control form-control-sm border-0 bg-light px-1 stage-time-input" value="{{ $stage->end_time }}" placeholder="09:00">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="stages[{{ $loop->parent->index . $loop->index }}][location]" class="form-control form-control-sm border-0 bg-light mb-1" value="{{ $stage->location }}" placeholder="Lokasi/Kantor">
                                                    <input type="text" name="stages[{{ $loop->parent->index . $loop->index }}][room_url]" class="form-control form-control-sm border-0 bg-light" value="{{ $stage->room_url }}" placeholder="Link Meeting (Zoom/GMail)">
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-link text-danger p-0 remove-stage-row"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-xs btn-link text-primary btn-add-nested-stage"><i class="fas fa-plus-circle mr-1"></i> Tambah Tahapan</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-block mt-3 btn-add-date-group" data-target="#date-groups-container-edit-{{ $batch->id }}">
                            <i class="fas fa-calendar-plus mr-1"></i> Tambah Grup Tanggal Seleksi
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-dark">Perbarui Jadwal Batch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endforeach

<template id="date-group-template">
    <div class="card card-outline card-secondary date-group mb-3 shadow-sm border-primary">
        <div class="card-header p-2 d-flex justify-content-between align-items-center bg-light">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-day mr-2 text-primary"></i>
                <input type="text" class="form-control form-control-sm group-date-input" style="width: 155px;" onchange="updateStageDates(this)" placeholder="Pilih Tanggal" required>
                <small class="ml-2 text-muted italic">Pilih tanggal untuk grup ini</small>
            </div>
            <button type="button" class="btn btn-tool text-danger remove-date-group" title="Hapus grup tanggal"><i class="fas fa-trash"></i></button>
        </div>
        <div class="card-body p-2">
            <table class="table table-sm table-borderless mb-0">
                <thead>
                    <tr class="small text-muted text-uppercase">
                        <th style="width: 40%">Tahapan Seleksi</th>
                        <th style="width: 25%">Waktu</th>
                        <th style="width: 30%">Lokasi / Link Room</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody class="stages-tbody">
                    {{-- Nested Stage Rows --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-xs btn-link text-primary font-weight-bold btn-add-nested-stage">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Tahapan
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<template id="nested-stage-row-template">
    <tr class="stage-row border-bottom animate__animated animate__fadeIn">
        <td>
            <input type="hidden" name="stages[INDEX][date]" class="stage-date-hidden">
            <select name="stages[INDEX][selection_id]" class="form-control form-control-sm border-0 bg-light" required>
                <option value="">-- Pilih --</option>
                @foreach($selections as $sel)
                    <option value="{{ $sel->selection_id }}">{{ $sel->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <input type="text" name="stages[INDEX][start_time]" class="form-control form-control-sm border-0 bg-light px-1 stage-time-input" placeholder="08:00">
                <span class="mx-1">-</span>
                <input type="text" name="stages[INDEX][end_time]" class="form-control form-control-sm border-0 bg-light px-1 stage-time-input" placeholder="09:00">
            </div>
        </td>
        <td>
            <input type="text" name="stages[INDEX][location]" class="form-control form-control-sm border-0 bg-light mb-1" placeholder="Lokasi/Kantor">
            <input type="text" name="stages[INDEX][room_url]" class="form-control form-control-sm border-0 bg-light" placeholder="Link Meeting (Zoom/GMail)">
        </td>
        <td class="text-right">
            <button type="button" class="btn btn-link text-danger p-0 remove-stage-row"><i class="fas fa-times"></i></button>
        </td>
    </tr>
</template>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let globalIndex = 2000;
    const dateGroupTemplate = document.getElementById('date-group-template').innerHTML;
    const stageRowTemplate = document.getElementById('nested-stage-row-template').innerHTML;

    function initFlatpickr(container) {
        container.find('.group-date-input').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true,
            minDate: "today"
        });

        container.find('.stage-time-input').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            allowInput: true
        });
    }

    // Tambah Grup Tanggal
    $(document).on('click', '.btn-add-date-group', function() {
        const container = $($(this).data('target'));
        const newGroup = $(dateGroupTemplate);
        container.append(newGroup);
        
        initFlatpickr(newGroup);

        // Auto add one stage
        addStageToGroup(newGroup);
    });

    // Tambah Tahapan di dalam Grup
    $(document).on('click', '.btn-add-nested-stage', function() {
        const group = $(this).closest('.date-group');
        addStageToGroup(group);
    });

    function addStageToGroup(group) {
        const tbody = group.find('.stages-tbody');
        const dateValue = group.find('.group-date-input').val();
        
        let rowHtml = stageRowTemplate.replace(/INDEX/g, globalIndex++);
        const newRow = $(rowHtml);
        
        // Set date
        newRow.find('.stage-date-hidden').val(dateValue);
        tbody.append(newRow);

        initFlatpickr(newRow);
        filterSelectionOptions(group.closest('form'));
    }

    // Update hidden date when group date changes
    window.updateStageDates = function(input) {
        const date = input.value;
        const group = $(input).closest('.date-group');
        group.find('.stage-date-hidden').val(date);
        validateSchedule();
    };

    // Validasi Tunggal & Menyeluruh
    function validateSchedule(form = null) {
        let isAllValid = true;
        let errorMessage = "";
        
        const targetGroups = form ? form.find('.date-group') : $('.date-group');
        let selectionIds = [];

        targetGroups.each(function() {
            const group = $(this);
            const date = group.find('.group-date-input').val();
            if (!date) return;

            let lastEndTime = null;
            group.find('.stage-row').each(function(index) {
                const row = $(this);
                const selectionId = row.find('select[name*="[selection_id]"]').val();
                const startTime = row.find('input[name*="[start_time]"]').val();
                const endTime = row.find('input[name*="[end_time]"]').val();
                const stageName = row.find('select option:checked').text() || "Tahapan " + (index + 1);
                
                row.removeClass('table-danger');
                row.find('.validation-error').remove();
                
                // 1. Validasi Duplikasi Tahapan
                if (selectionId) {
                    if (selectionIds.includes(selectionId)) {
                        row.addClass('table-danger');
                        row.find('td:first').append('<div class="validation-error text-danger small font-weight-bold animate__animated animate__headShake"><i class="fas fa-exclamation-circle"></i> Tahapan duplikat!</div>');
                        if (!errorMessage) errorMessage = "Terdapat tahapan seleksi yang sama/duplikat.";
                        isAllValid = false;
                    }
                    selectionIds.push(selectionId);
                }

                // 2. Validasi logis dalam 1 baris (start >= end)
                if (startTime && endTime && startTime >= endTime) {
                    row.addClass('table-danger');
                    row.find('td:first').append('<div class="validation-error text-danger small font-weight-bold animate__animated animate__headShake"><i class="fas fa-exclamation-circle"></i> Jam tidak logis!</div>');
                    if (!errorMessage) errorMessage = `Kesalahan waktu pada ${stageName}.`;
                    isAllValid = false;
                }
                // 3. Validasi bentrok antar baris (start < last end)
                else if (startTime && lastEndTime && startTime < lastEndTime) {
                    row.addClass('table-danger');
                    row.find('td:first').append('<div class="validation-error text-danger small font-italic"><i class="fas fa-exclamation-triangle"></i> Jam tabrakan!</div>');
                    if (!errorMessage) errorMessage = "Jadwal bentrok ditemukan.";
                    isAllValid = false;
                }
                
                if (endTime) lastEndTime = endTime;
            });
        });
        
        return { isValid: isAllValid, message: errorMessage };
    }

    function filterSelectionOptions(form) {
        const selects = form.find('select[name*="[selection_id]"]');
        const selectedValues = [];
        
        // ambil semua nilai option yang sudah dipilih
        selects.each(function() {
            const val = $(this).val();
            if (val) selectedValues.push(val);
        });

        // loop setiap select dan sembunyikan option yang sudah dipilih di select lain
        selects.each(function() {
            const currentSelect = $(this);
            const currentVal = currentSelect.val();
            
            currentSelect.find('option').each(function() {
                const option = $(this);
                const optionVal = option.val();
                
                if (!optionVal) return; // lewati placeholder
                
                // jika nilai option ada di select lain maka sembunyikan
                if (selectedValues.includes(optionVal) && optionVal !== currentVal) {
                    option.attr('disabled', 'disabled').hide();
                } else {
                    option.removeAttr('disabled').show();
                }
            });
        });
    }

    $(document).on('change', 'select[name*="[selection_id]"]', function() {
        const form = $(this).closest('form');
        filterSelectionOptions(form);
        validateSchedule();
    });

    $(document).on('change', '.stage-time-input', function() {
        validateSchedule();
    });

    // Hapus Grup
    $(document).on('click', '.remove-date-group', function() {
        if(confirm('Hapus seluruh grup tanggal ini?')) {
            const form = $(this).closest('form');
            $(this).closest('.date-group').remove();
            filterSelectionOptions(form);
            validateSchedule();
        }
    });

    // Hapus Tahapan
    $(document).on('click', '.remove-stage-row', function() {
        const tbody = $(this).closest('tbody');
        if (tbody.find('tr').length > 1) {
            const form = $(this).closest('form');
            $(this).closest('tr').remove();
            filterSelectionOptions(form);
            validateSchedule();
        } else {
            alert('Grup harus memiliki minimal satu tahapan.');
        }
    });

    // Validasi sebelum submit
    $(document).on('submit', 'form', function(e) {
        const result = validateSchedule($(this));
        if (!result.isValid) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error(result.message);
            } else {
                alert(result.message);
            }
            return false;
        }
    });

    // Auto-init for Add Modal
    $(document).on('shown.bs.modal', function (e) {
        const modalId = e.target.id;
        const $modal = $(e.target);
        
        // Init existing flatpickrs in edit modal
        initFlatpickr($modal);

        if(modalId.includes('modalAddBatch-')) {
            const container = $(`#date-groups-container-add-${modalId.split('-')[1]}`);
            if(container.children().length === 0) {
                $('.btn-add-date-group[data-target="#' + container.attr('id') + '"]').click();
            }
        }
        filterSelectionOptions($modal.find('form').length ? $modal.find('form') : $modal);
        validateSchedule();
    });
</script>
@endpush

@endsection
