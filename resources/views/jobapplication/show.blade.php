@extends('layouts.admin')

@section('title', 'Detail Lamaran')
@section('page_title', 'Proses Seleksi Lamaran')

@section('content')
<div class="row">
    <div class="col-md-4">
        {{-- Info Pelamar --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Profile Pelamar</h3>
            </div>
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ $jobapplication->jobApplicant->name }}</h3>
                <p class="text-muted text-center">{{ $jobapplication->jobVacancie->title }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $jobapplication->jobApplicant->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>No HP</b> <a class="float-right">{{ $jobapplication->jobApplicant->phone }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Tanggal Lamar</b> <a class="float-right">{{ $jobapplication->created_at->format('d-m-Y') }}</a>
                    </li>
            </div>
        </div>

        {{-- Dokumen --}}
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Dokumen Pelamar</h3>
            </div>
            <div class="card-body p-0">
                @php
                    $allDocs = [];

                    // Additional Docs
                    if($jobapplication->documents && is_array($jobapplication->documents)) {
                        foreach($jobapplication->documents as $key => $doc) {
                            $docPath = is_array($doc) ? ($doc['path'] ?? '') : $doc;
                            $docName = is_array($doc) ? ($doc['name'] ?? $key) : $key;
                            $isWajib = is_array($doc) && isset($doc['type']) && $doc['type'] == 'required';
                            
                            $allDocs[] = [
                                'name' => $docName,
                                'path' => asset('storage/' . $docPath),
                                'icon' => 'fas fa-file-alt text-secondary',
                                'badge' => $isWajib ? 'Wajib' : 'Tambahan',
                                'badge_class' => $isWajib ? 'badge-primary' : 'badge-light border'
                            ];
                        }
                    }
                @endphp

                @if(count($allDocs) > 0)
                    <div class="list-group list-group-flush" id="document-list-main">
                        @foreach($allDocs as $index => $doc)
                            <a href="javascript:void(0)" 
                               class="list-group-item list-group-item-action d-flex align-items-center btn-view-doc" 
                               data-index="{{ $index }}"
                               data-name="{{ $doc['name'] }}"
                               data-path="{{ $doc['path'] }}">
                                <div class="mr-3">
                                    <i class="{{ $doc['icon'] }} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-sm font-weight-bold text-dark">{{ $doc['name'] }}</div>
                                    <span class="badge {{ $doc['badge_class'] }}" style="font-size: 10px;">{{ $doc['badge'] }}</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted sm"></i>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0">Tidak ada dokumen terlampir</p>
                    </div>
                @endif
            </div>
            @if(count($allDocs) > 0)
            <div class="card-footer p-2 text-center">
                <button type="button" class="btn btn-sm btn-info btn-block btn-view-doc" data-index="0">
                    <i class="fas fa-expand mr-1"></i> Buka Peninjau Dokumen
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Accept Application --}}
<div class="modal fade" id="modalAcceptApp" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">Terima Pelamar</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('jobapplication.updateStatus', $jobapplication->application_id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="accepted">
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin <strong>MENERIMA</strong> pelamar ini untuk bekerja?</p>
                    <p class="text-muted small">Status lamaran akan berubah menjadi 'Accepted'.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Terima Pelamar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Document Viewer --}}
<div class="modal fade" id="modalDocViewer" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-file-alt mr-2"></i> 
                    Peninjau Dokumen: <span id="current-doc-title" class="font-weight-normal"></span>
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0 d-flex flex-column flex-md-row" style="background: #e4e6e9;">
                {{-- Sidebar List --}}
                <div class="doc-sidebar bg-white border-right" style="width: 280px; overflow-y: auto;">
                    <div class="p-3 bg-light border-bottom">
                        <small class="text-uppercase font-weight-bold text-muted">Daftar Berkas</small>
                    </div>
                    <div class="list-group list-group-flush" id="doc-list-modal">
                        @foreach($allDocs as $index => $doc)
                            <a href="javascript:void(0)" 
                               class="list-group-item list-group-item-action btn-switch-doc p-3" 
                               data-index="{{ $index }}"
                               data-name="{{ $doc['name'] }}"
                               data-path="{{ $doc['path'] }}">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $doc['icon'] }} mr-2"></i>
                                    <div class="text-truncate">
                                        <div class="text-xs font-weight-bold mb-0 text-dark">{{ $doc['name'] }}</div>
                                        <span class="badge {{ $doc['badge_class'] }}" style="font-size: 9px;">{{ $doc['badge'] }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Preview Area --}}
                <div class="doc-preview flex-grow-1 position-relative d-flex flex-column">
                    <div id="doc-loading" class="position-absolute w-100 h-100 d-none align-items-center justify-content-center bg-white" style="z-index: 10;">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Memuat dokumen...</p>
                        </div>
                    </div>
                    <iframe id="doc-iframe" src="" frameborder="0" style="width: 100%; height: 100%;" loading="lazy"></iframe>
                    
                    <div id="doc-placeholder" class="h-100 d-none align-items-center justify-content-center flex-column text-muted p-5 text-center">
                        <i class="fas fa-file-pdf fa-4x mb-3 opacity-25"></i>
                        <h5>Pilih dokumen dari daftar di samping</h5>
                        <p>Preview hanya mendukung format PDF dan Gambar. Format lain akan otomatis terunduh.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-1 bg-light justify-content-between">
                <small class="text-muted"><i class="fas fa-info-circle"></i> Klik dokumen di sebelah kiri untuk mengganti tampilan.</small>
                <div class="btn-group">
                    <a href="#" id="btn-download-doc" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-download mr-1"></i> Unduh
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-switch-doc.active {
        background-color: #f0f7ff !important;
        border-left: 4px solid #007bff !important;
        color: #0056b3 !important;
    }
    .btn-switch-doc:hover {
        background-color: #fafafa;
    }
    @media (max-width: 768px) {
        .doc-sidebar { width: 100% !important; max-height: 150px; }
    }
</style>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Start Process Modal
        $('.btn-start-process').on('click', function() {
            var id = $(this).data('id');
            var actionUrl = "{{ route('jobapplication.updateSelection', ':id') }}";
            actionUrl = actionUrl.replace(':id', id);
            $('#formStartProcess').attr('action', actionUrl);
        });

        // Handle Input Result Modal
        $('.btn-input-result').on('click', function() {
            var id = $(this).data('id');
            var score = $(this).data('score');
            var notes = $(this).data('notes');
            
            var actionUrl = "{{ route('jobapplication.updateSelection', ':id') }}";
            actionUrl = actionUrl.replace(':id', id);

            $('#formInputResult').attr('action', actionUrl);
            $('#resultScore').val(score);
            $('#resultNotes').val(notes);
        });

        // DOCUMENT VIEWER LOGIC
        const $modal = $('#modalDocViewer');
        const $iframe = $('#doc-iframe');
        const $loading = $('#doc-loading');
        const $title = $('#current-doc-title');
        const $downloadBtn = $('#btn-download-doc');

        function loadDocument(index) {
            const $item = $(`.btn-switch-doc[data-index="${index}"]`);
            const path = $item.data('path');
            const name = $item.data('name');

            $('.btn-switch-doc').removeClass('active');
            $item.addClass('active');

            $loading.removeClass('d-none').addClass('d-flex');
            $title.text(name);
            $downloadBtn.attr('href', path);

            // Handle Preview (Simple check for PDF vs Others)
            $iframe.attr('src', path);
            
            $iframe.on('load', function() {
                $loading.removeClass('d-flex').addClass('d-none');
            });
        }

        $('.btn-view-doc').on('click', function() {
            const index = $(this).data('index');
            $modal.modal('show');
            loadDocument(index);
        });

        $('.btn-switch-doc').on('click', function() {
            const index = $(this).data('index');
            loadDocument(index);
        });

        // Add to Date logic
        $('.btn-add-to-date').on('click', function() {
            const date = $(this).data('date');
            const $modal = $('#modal-create');
            $modal.find('input[name="selection_date"]').val(date);
            $modal.find('.modal-title').html('<i class="fas fa-calendar-plus mr-2"></i>Tambah Tahapan untuk ' + date);
        });

        // Reset modal title when regular create is clicked
        $('[data-target="#modal-create"]').not('.btn-add-to-date').on('click', function() {
            const $modal = $('#modal-create');
            $modal.find('.modal-title').html('<i class="fas fa-calendar-plus mr-2"></i>Tambah Jadwal Baru');
            $('#modal_batch_stage_id').val('');
        });

        // Update batch_stage_id hidden input when selection changes
        $('select[name="selection_id"]').on('change', function() {
            var batchStageId = $(this).find(':selected').data('batch-stage-id');
            $('#modal_batch_stage_id').val(batchStageId || '');
        });
    });
</script>
@endpush
