@extends('layouts.admin')

@section('title', 'Proses Seleksi')
@section('page_title', 'Proses Seleksi Pelamar')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon fas fa-check"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="icon fas fa-ban"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Pelamar dalam Proses Seleksi</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Identitas Pelamar</th>
                            <th>Posisi Dilamar</th>
                            <th>Riwayat Tahapan Seleksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobApplications->groupBy('job_applicant_id') as $applicantId => $apps)
                            @php 
                                $firstApp = $apps->first(); 
                                $applicant = $firstApp->jobApplicant;
                            @endphp
                            
                            {{-- Baris pertama untuk pelamar ini --}}
                            <tr>
                                <td rowspan="{{ $apps->count() }}" style="background-color: #fff; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $apps->count() }}" style="background-color: #fff; vertical-align: middle;">
                                    <strong>{{ $applicant->name }}</strong><br>
                                    <small class="text-muted">{{ $applicant->email }}</small>
                                    <div class="mt-2">
                                        @php
                                            $allDocs = [];
                                            // Dokumen dasar (dari profil)
                                            $baseDocs = [
                                               ["title" => "CV", "url" => $applicant->cv_file ? asset("storage/" . $applicant->cv_file) : null, "icon" => "fa-file-pdf"],
                                               ["title" => "Surat Lamaran", "url" => $applicant->cover_letter ? asset("storage/" . $applicant->cover_letter) : null, "icon" => "fa-envelope-open-text"],
                                               ["title" => "Portofolio", "url" => $applicant->portfolio ? asset("storage/" . $applicant->portfolio) : null, "icon" => "fa-briefcase"],
                                               ["title" => "Ijazah", "url" => $applicant->last_diploma ? asset("storage/" . $applicant->last_diploma) : null, "icon" => "fa-graduation-cap"],
                                               ["title" => "Transkrip", "url" => $applicant->transcript ? asset("storage/" . $applicant->transcript) : null, "icon" => "fa-file-invoice"],
                                               ["title" => "Sertifikat", "url" => $applicant->supporting_certificates ? asset("storage/" . $applicant->supporting_certificates) : null, "icon" => "fa-certificate"],
                                               ["title" => "Pengalaman Kerja", "url" => $applicant->work_experience ? asset("storage/" . $applicant->work_experience) : null, "icon" => "fa-user-tie"]
                                            ];
                                            
                                            // Filter dokumen yang ada URL-nya
                                            foreach($baseDocs as $doc) {
                                                if($doc['url']) $allDocs[] = $doc;
                                            }
                                        @endphp
                                        @if(count($allDocs) > 0)
                                            <button type="button" class="btn btn-info btn-xs btn-view-docs" 
                                                data-name="{{ $applicant->name }}"
                                                data-docs='@json($allDocs)'>
                                                <i class="fas fa-file-alt mr-1"></i> {{ count($allDocs) }} Dokumen Profil
                                            </button>
                                        @else
                                            <span class="text-muted small">Tanpa Dokumen Profil</span>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- Render data seleksi untuk lamaran pertama --}}
                                @include('selectionapplicant.partials.selection_row_columns', ['app' => $firstApp])
                            </tr>

                            {{-- Baris untuk lamaran-lamaran berikutnya dari pelamar yang sama --}}
                            @foreach($apps->slice(1) as $app)
                                <tr>
                                    @include('selectionapplicant.partials.selection_row_columns', ['app' => $app])
                                </tr>
                            @endforeach
                            
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada pelamar dalam proses seleksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dokumen -->
<div class="modal fade" id="modalDocs" tabindex="-1" role="dialog" aria-labelledby="modalDocsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title font-weight-bold" id="modalDocsLabel"><i class="fas fa-file-alt mr-2"></i> Dokumen Pelamar: <span id="applicantName"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="docsList" class="row">
                    <!-- Dokumen akan dimuat di sini via JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-view-docs').on('click', function() {
        const name = $(this).data('name');
        const docs = $(this).data('docs');
        
        $('#applicantName').text(name);
        let docsHtml = '';
        
        docs.forEach(doc => {
            if (doc.url) {
                docsHtml += `
                    <div class="col-md-6 mb-3">
                        <div class="info-box shadow-sm">
                            <span class="info-box-icon bg-info"><i class="fas ${doc.icon}"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text font-weight-bold">${doc.title}</span>
                                <div class="mt-2">
                                    <a href="${doc.url}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                    <a href="${doc.url}" download class="btn btn-sm btn-outline-info ml-1">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        
        $('#docsList').html(docsHtml);
        $('#modalDocs').modal('show');
    });
});
</script>
@endpush
