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
                <div class="card-tools">
                    <form action="{{ route('jobapplication.sendEmailUpdate', $jobapplication->application_id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-tool" title="Kirim Update Email ke Pelamar" onclick="return confirm('Kirim email update status lengkap ke pelamar?')">
                            <i class="fas fa-envelope"></i> Kirim Email
                        </button>
                    </form>
                </div>
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
                    <li class="list-group-item">
                        <b>Status Saat Ini</b> 
                        <span class="float-right badge badge-info">
                            @if($jobapplication->status == 'pending') Baru
                            @elseif($jobapplication->status == 'applied') Review Berkas
                            @elseif($jobapplication->status == 'process') Seleksi
                            @elseif($jobapplication->status == 'accepted') Diterima
                            @elseif($jobapplication->status == 'rejected') Ditolak @endif
                        </span>
                    </li>
                </ul>

                <div class="text-center">
                    {{-- TAHAP 1: PENDING (Baru Masuk) --}}
                    @if($jobapplication->status == 'pending')
                        <div class="alert alert-info border shadow-sm">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Lamaran Baru Telah Diterima</strong><br>
                            Silakan mulai melakukan review pada berkas pelamar ini.
                        </div>
                        <form action="{{ route('jobapplication.updateStatus', $jobapplication->application_id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="applied">
                            <button type="submit" class="btn btn-primary btn-block btn-lg" onclick="return confirm('Mulai review berkas pelamar ini?')">
                                <i class="fas fa-search-plus mr-2"></i> Mulai Proses Review
                            </button>
                        </form>

                    {{-- TAHAP 2: APPLIED (Sedang Direview Berkasnya) --}}
                    @elseif($jobapplication->status == 'applied')
                        <div class="alert alert-warning border shadow-sm">
                            <i class="fas fa-file-alt mr-1"></i> <strong>Pengecekan Berkas & Kelengkapan</strong><br>
                            Tentukan apakah pelamar ini layak lanjut ke tahapan seleksi teknis/interview.
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <form action="{{ route('jobapplication.updateStatus', $jobapplication->application_id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="process">
                                    <button type="submit" class="btn btn-success btn-block btn-lg" onclick="return confirm('Luluskan seleksi berkas dan lanjut ke tahapan seleksi?')">
                                        <i class="fas fa-check-double mr-2"></i> Lulus Seleksi Berkas
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger btn-block btn-lg" data-toggle="modal" data-target="#modalRejectApp">
                                    <i class="fas fa-times-circle mr-2"></i> Tolak Lamaran Di Sini
                                </button>
                            </div>
                        </div>

                    {{-- TAHAP 3: PROCESS (Tahapan Seleksi: Interview, Psikotes, dll) --}}
                    @elseif($jobapplication->status == 'process')
                        @php
                            $totalStages = $jobapplication->selectionApplicant->count();
                            $incompleteStages = $jobapplication->selectionApplicant->whereIn('status', ['unprocess', 'process'])->count();
                        @endphp

                        <div class="alert alert-primary border shadow-sm">
                            <i class="fas fa-tasks mr-1"></i> <strong>Tahapan Seleksi Berjalan</strong><br>
                            @if($totalStages == 0)
                                <span class="text-danger font-weight-bold">Belum ada jadwal seleksi!</span><br>
                                <small>Tambahkan jadwal interview atau test di panel sebelah kanan.</small>
                            @else
                                Kemajuan: {{ $totalStages - $incompleteStages }} dari {{ $totalStages }} tahapan selesai.
                            @endif
                        </div>

                        @if($totalStages > 0 && $incompleteStages == 0)
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <button type="button" class="btn btn-success btn-block btn-lg" data-toggle="modal" data-target="#modalAcceptApp">
                                        <i class="fas fa-user-check mr-2"></i> Terima & Luluskan Final
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-danger btn-block btn-lg" data-toggle="modal" data-target="#modalRejectApp">
                                        <i class="fas fa-user-times mr-2"></i> Tolak Pelamar
                                    </button>
                                </div>
                            </div>
                        @else
                            @if($totalStages > 0)
                                <div class="alert alert-light border border-info text-left small">
                                    <i class="fas fa-exclamation-triangle text-info mr-1"></i> Keputusan akhir baru dapat diambil setelah <strong>seluruh tahapan seleksi</strong> selesai dinilai.
                                </div>
                            @endif
                        @endif

                    {{-- HASIL FINAL --}}
                    @elseif($jobapplication->status == 'accepted')
                        <div class="alert alert-success shadow">
                            <h4 class="mb-2"><i class="icon fas fa-check-circle"></i> STATUS: DITERIMA</h4>
                            Pelamar ini telah dinyatakan <strong>LULUS</strong> seleksi dan diterima bekerja.
                        </div>
                    @elseif($jobapplication->status == 'rejected')
                        <div class="alert alert-danger shadow">
                            <h4 class="mb-2"><i class="icon fas fa-times-circle"></i> STATUS: DITOLAK</h4>
                            Lamaran ini telah dinyatakan <strong>TIDAK LULUS</strong>.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Dokumen --}}
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Dokumen Pelamar</h3>
            </div>
            <div class="card-body">
                <h6 class="text-primary font-weight-bold">Dokumen Profil Utama:</h6>
                @php
                    $docs = [
                        ['name' => 'CV (Curriculum Vitae)', 'file' => $jobapplication->jobApplicant->cv_file],
                        ['name' => 'Surat Lamaran', 'file' => $jobapplication->jobApplicant->cover_letter],
                        ['name' => 'Ijazah Terakhir', 'file' => $jobapplication->jobApplicant->last_diploma],
                        ['name' => 'Transkrip Nilai', 'file' => $jobapplication->jobApplicant->transcript],
                        ['name' => 'Portofolio', 'file' => $jobapplication->jobApplicant->portfolio],
                        ['name' => 'Sertifikat Pendukung', 'file' => $jobapplication->jobApplicant->supporting_certificates],
                    ];
                @endphp

                <ul class="list-group list-group-flush mb-3">
                    @php $hasMainDocs = false; @endphp
                    @foreach($docs as $doc)
                        @if($doc['file'])
                            @php $hasMainDocs = true; @endphp
                            <li class="list-group-item p-2">
                                <a href="{{ asset('storage/' . $doc['file']) }}" target="_blank" class="text-dark">
                                    <i class="far fa-file-pdf text-danger mr-2"></i> {{ $doc['name'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                    @if(!$hasMainDocs)
                        <li class="list-group-item p-2 text-muted small">Tidak ada dokumen profil utama</li>
                    @endif
                </ul>

                <h6 class="text-primary font-weight-bold">Dokumen Tambahan (Lamaran Ini):</h6>
                <ul class="list-group list-group-flush">
                    @if($jobapplication->documents && is_array($jobapplication->documents))
                        @foreach($jobapplication->documents as $doc)
                            <li class="list-group-item p-2">
                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="text-dark">
                                    <i class="fas fa-file-alt text-info mr-2"></i> {{ $doc['name'] }}
                                    @if(isset($doc['type']) && $doc['type'] == 'required')
                                        <span class="badge badge-secondary small ml-1">Wajib</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item p-2 text-muted small">Tidak ada dokumen tambahan untuk lamaran ini</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Tahapan Seleksi --}}
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Tahapan Seleksi</a></li>
                    @if(in_array($jobapplication->status, ['process', 'pending', 'applied']))
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Tambah Tahapan</a></li>
                    @endif
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        @if($jobapplication->selectionApplicant->count() > 0)
                            <div class="timeline timeline-inverse">
                                @foreach($jobapplication->selectionApplicant as $selection)
                                    @php
                                        $bgClass = 'bg-gray';
                                        if($selection->status == 'passed') $bgClass = 'bg-success';
                                        elseif($selection->status == 'failed') $bgClass = 'bg-danger';
                                        elseif($selection->status == 'process') $bgClass = 'bg-primary';
                                        elseif($selection->status == 'unprocess') $bgClass = 'bg-warning';
                                    @endphp
                                    
                                    <!-- timeline time label -->
                                    <div class="time-label">
                                        <span class="{{ $bgClass }}">
                                            {{ $selection->selection_date ? \Carbon\Carbon::parse($selection->selection_date)->format('d M Y') : 'TBA' }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <i class="fas fa-clipboard-list {{ $bgClass }}"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> {{ $selection->updated_at->diffForHumans() }}</span>

                                            <h3 class="timeline-header">
                                                <a href="#">{{ $selection->selection->name }}</a> 
                                                @if($selection->status == 'unprocess')
                                                    - <span class="text-warning">Belum Diproses</span>
                                                @elseif($selection->status == 'process')
                                                    - <span class="text-primary">Sedang Diproses</span>
                                                @elseif($selection->status == 'passed')
                                                    - <span class="text-success">Lolos</span>
                                                @elseif($selection->status == 'failed')
                                                    - <span class="text-danger">Gagal</span>
                                                @endif
                                            </h3>

                                            <div class="timeline-body">
                                                @if($selection->status == 'passed' || $selection->status == 'failed')
                                                    <p><strong>Nilai:</strong> {{ $selection->score }}</p>
                                                    <p><strong>Catatan:</strong> {{ $selection->notes }}</p>
                                                @elseif($selection->status == 'process')
                                                    <div class="alert alert-light">
                                                        <small><i class="fas fa-info-circle"></i> Tahapan ini sedang berlangsung. Silakan input hasil penilaian jika sudah selesai.</small>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="timeline-footer">
                                                @if($selection->status == 'unprocess')
                                                    @php
                                                        $isLocked = $selection->selection_date && \Carbon\Carbon::parse($selection->selection_date)->isFuture();
                                                        $TBA = $selection->selection_date == null;
                                                    @endphp

                                                    <!-- Tombol Mulai Proses -->
                                                    @if($isLocked)
                                                        <button class="btn btn-secondary btn-sm disabled" title="Belum waktunya pelakasaan" disabled>
                                                            <i class="fas fa-lock mr-1"></i> Belum Waktunya
                                                        </button>
                                                        <small class="text-muted d-block mt-1">
                                                            <i class="far fa-clock"></i> Jadwal: {{ \Carbon\Carbon::parse($selection->selection_date)->format('d M Y') }}
                                                        </small>
                                                    @elseif($TBA)
                                                    <form action="{{ route('jobapplication.updateSelection', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-link btn-sm text-danger float-right" data-id="{{$selection->applicant_id}}" data-target="data-toggle="modal" data-target="#modalStartProcess">"></button>
                                                    </form>
                                                        <button class="btn btn-secondary btn-sm disabled" title="Belum ada jadwal" disabled>
                                                            <i class="fas fa-lock mr-1"></i> Belum ada jadwal
                                                        </button>
                                                        <small class="text-muted d-block mt-1">
                                                            <i class="far fa-clock"></i> Jadwal: {{ \Carbon\Carbon::parse($selection->selection_date)->format('d M Y') }}
                                                        </small>
                                                    @else
                                                        <button class="btn btn-warning btn-sm btn-start-process" 
                                                            data-id="{{ $selection->selection_applicant_id }}"
                                                            data-toggle="modal" data-target="#modalStartProcess">
                                                            <i class="fas fa-play mr-1"></i> Mulai Proses
                                                        </button>
                                                    @endif
                                                    
                                                    <form action="{{ route('jobapplication.deleteSelection', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-sm text-danger float-right" onclick="return confirm('Hapus tahapan ini?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                @elseif($selection->status == 'process')
                                                    <!-- Tombol Input Hasil -->
                                                    <button class="btn btn-info btn-sm btn-input-result" 
                                                        data-id="{{ $selection->selection_applicant_id }}"
                                                        data-score="{{ $selection->score }}"
                                                        data-notes="{{ $selection->notes }}"
                                                        data-toggle="modal" data-target="#modalInputResult">
                                                        <i class="fas fa-pencil-alt mr-1"></i> Input Hasil
                                                    </button>
                                                    
                                                    <form action="{{ route('jobapplication.deleteSelection', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-sm text-danger float-right" onclick="return confirm('Hapus tahapan ini?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Belum ada tahapan seleksi. Silakan tambah tahapan baru.
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal" action="{{ route('jobapplication.addSelection', $jobapplication->application_id) }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Tahapan Seleksi</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="selection_id" required>
                                        <option value="">-- Pilih Tahapan --</option>
                                        @foreach($selections as $sel)
                                            <option value="{{ $sel->selection_id }}">{{ $sel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Tanggal Rencana</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="selection_date" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Meluncurkan Tahapan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

{{-- Modal Reject Application --}}
<div class="modal fade" id="modalRejectApp" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Tolak Pelamar</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('jobapplication.updateStatus', $jobapplication->application_id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin <strong>MENOLAK</strong> lamaran ini?</p>
                    <p class="text-muted small">Status lamaran akan berubah menjadi 'Rejected'.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Lamaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Start Process (Update status to process + set date) --}}
<div class="modal fade" id="modalStartProcess" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Mulai Proses Seleksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formStartProcess" action="" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="process">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Konfirmasi Tanggal Pelaksanaan</label>
                        <input type="date" name="selection_date" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Mulai Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Input Result (Pass/Fail) --}}
<div class="modal fade" id="modalInputResult" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Input Hasil Seleksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formInputResult" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nilai (Score)</label>
                        <input type="number" name="score" id="resultScore" class="form-control" placeholder="0 - 100" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="notes" id="resultNotes" class="form-control" rows="3" placeholder="Catatan pewawancara/penguji..."></textarea>
                    </div>
                    <hr>
                    <p class="text-center font-weight-bold mb-3">Tentukan Hasil Akhir:</p>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" name="status" value="passed" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle"></i> LULUS
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="status" value="failed" class="btn btn-danger btn-block">
                                <i class="fas fa-times-circle"></i> GAGAL
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
    });
</script>
@endpush
