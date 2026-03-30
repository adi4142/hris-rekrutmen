@extends('layouts.admin')

@section('title', 'Proses Lamaran')
@section('page_title', 'Proses Seleksi & Batching')

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- FILTER LOWONGAN --}}
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body">
                <form action="{{ route('jobapplication.manage') }}" method="GET" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-4 form-group mb-0">
                            <label class="small font-weight-bold">PILIH LOWONGAN KERJA</label>
                            <select name="vacancy_id" class="form-control select2" onchange="this.form.submit()">
                                @foreach($vacancies as $v)
                                    <option value="{{ $v->vacancies_id }}" {{ $selectedVacancyId == $v->vacancies_id ? 'selected' : '' }}>
                                        [{{ $v->departement->name ?? '-' }}] {{ $v->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group mb-0">
                            <label class="small font-weight-bold">CARI PELAMAR</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    @if(request('search'))
                                    <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}" class="btn btn-danger border-0">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex justify-content-end align-items-center">
                            @include('jobapplication.partials._phase_tabs')
                        </div>
                    </div>
                    <input type="hidden" name="phase" value="{{ $phase }}">
                </form>
            </div>
        </div>

        @php $selectedVacancy = $vacancies->where('vacancies_id', $selectedVacancyId)->first(); @endphp

        <div class="card shadow-sm" data-card-id="manage-applications-card">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    @if($phase == 'review') <i class="fas fa-file-alt mr-2 text-info"></i> TAHAP 1: REVIEW BERKAS
                    @elseif($phase == 'selection') <i class="fas fa-user-clock mr-2 text-warning"></i> TAHAP 2: PROSES SELEKSI (BATCH)
                    @elseif($phase == 'offering') <i class="fas fa-handshake mr-2 text-success"></i> TAHAP 3: FINAL OFFERING
                    @else <i class="fas fa-check-double mr-2 text-secondary"></i> PELAMAR SELESAI
                    @endif
                </h3>
                <div class="card-tools">
                    <span class="badge ">Total: {{ $applications->total() }} Pelamar</span>
                </div>
            </div>

            <div class="card-body p-0">
                @if($phase == 'review')
                    @include('jobapplication.partials._phase_review')
                @elseif($phase == 'selection')
                    @include('jobapplication.partials._phase_selection')
                @elseif($phase == 'offering')
                    @include('jobapplication.partials._phase_offering')
                @else
                    @include('jobapplication.partials._phase_final')
                @endif
            </div>

            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} applications
                    </div>
                    <div>
                        {{ $applications->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('jobapplication.partials._modals')

@push('scripts')
<script src="{{ asset('js/jobapplication/manage.js') }}"></script>
@endpush

<style>
    /* Premium Phase Stepper */
    .phase-container {
        display: flex;
        gap: 8px;
    }
    .phase-item {
        display: flex;
        align-items: center;
        padding: 6px 16px;
        background: var(--bg-card);
        border: 1px solid #dee2e6;
        border-radius: 50px;
        color: #495057;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .phase-item:hover {
        background: #f1f3f5;
        border-color: #ced4da;
        color: #007bff;
        transform: translateY(-1px);
        transform: scale(1.2);
    }
    .phase-item.active {
        background: #007bff;
        border-color: #007bff;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.25);
    }
    .phase-item.active.final {
        background: #6c757d;
        border-color: #6c757d;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.25);
    }
    .phase-number {
        width: 20px;
        height: 20px;
        background: rgba(0,0,0,0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-size: 0.75rem;
        transition: all 0.3s;
    }
    .phase-item.active .phase-number {
        background: rgba(255,255,255,0.25);
    }
    .phase-label {
        white-space: nowrap;
    }

    /* Common styles */
    .btn-group-toggle .btn { margin-right: 5px; border-radius: 20px !important; }
    .btn-group-toggle .btn.active { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    /* Star Rating CSS */
    .star-rating i {
        font-size: 1.25rem;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 2px;
    }
    .star-rating i:hover,
    .star-rating i.checked {
        color: #ffc107;
        text-shadow: 0 0 5px rgba(255,193,7,0.3);
    }
    .star-rating i:active {
        transform: scale(1.2);
    }
</style>
@endsection
