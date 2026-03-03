@extends('layouts.admin')

@section('title', 'Job Applicant')
@section('page_title', 'Manajemen Pendaftar')

@section('content')

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Info Diri</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobApplicants as $jobApplicant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $jobApplicant->name }}</strong><br>
                            <small class="text-muted">{{ $jobApplicant->email }}</small>
                        </td>
                        <td>
                            <i class="fas fa-phone mr-1"></i> {{ $jobApplicant->phone }}
                        </td>
                        <td>
                            <small>
                                <strong>Lahir:</strong> {{ $jobApplicant->date_of_birth }}<br>
                                <strong>Gender:</strong> {{ ucfirst($jobApplicant->gender) }}
                            </small>
                        </td>
                        <td>
                            @php
                                $docsData = [
                                    ["title" => "CV", "url" => $jobApplicant->cv_file ? asset("storage/" . $jobApplicant->cv_file) : null, "icon" => "fa-file-pdf"],
                                    ["title" => "Surat Lamaran", "url" => $jobApplicant->cover_letter ? asset("storage/" . $jobApplicant->cover_letter) : null, "icon" => "fa-envelope-open-text"],
                                    ["title" => "Portofolio", "url" => $jobApplicant->portfolio ? asset("storage/" . $jobApplicant->portfolio) : null, "icon" => "fa-briefcase"],
                                    ["title" => "Ijazah", "url" => $jobApplicant->last_diploma ? asset("storage/" . $jobApplicant->last_diploma) : null, "icon" => "fa-graduation-cap"],
                                    ["title" => "Transkrip", "url" => $jobApplicant->transcript ? asset("storage/" . $jobApplicant->transcript) : null, "icon" => "fa-file-invoice"],
                                    ["title" => "Sertifikat", "url" => $jobApplicant->supporting_certificates ? asset("storage/" . $jobApplicant->supporting_certificates) : null, "icon" => "fa-certificate"],
                                    ["title" => "Pengalaman Kerja", "url" => $jobApplicant->work_experience ? asset("storage/" . $jobApplicant->work_experience) : null, "icon" => "fa-user-tie"]
                                ];
                                
                                $docsCount = 0;
                                foreach($docsData as $doc) {
                                    if($doc['url']) $docsCount++;
                                }
                            @endphp
                            
                            @if($docsCount > 0)
                                <button type="button" class="btn btn-info btn-sm btn-view-docs" 
                                    data-name="{{ $jobApplicant->name }}"
                                    data-docs='@json($docsData)'>
                                    <i class="fas fa-file-alt mr-1"></i> {{ $docsCount }} Dokumen
                                </button>
                            @else
                                <span class="text-muted small">Tidak ada dokumen</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if(!$jobApplicant->user_id)
                                <form action="{{ route('jobapplicant.create-account', $jobApplicant->job_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Buat Akun" onclick="return confirm('Buat akun untuk pelamar ini?')">
                                        <i class="fas fa-user-plus"></i> Buat Akun
                                    </button>
                                </form>
                                @else
                                <span class="badge badge-success"><i class="fas fa-check"></i> Akun Aktif</span>
                                @endif
                                
                                <a href="{{ route('jobapplicant.edit', $jobApplicant->job_applicant_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jobapplicant.destroy', $jobApplicant->job_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pendaftar ini? Semua data lamaran terkait juga akan berpengaruh.')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<!-- Modal Dokumen -->
<div class="modal fade" id="modalDocs" tabindex="-1" role="dialog" aria-labelledby="modalDocsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalDocsLabel font-weight-bold"><i class="fas fa-file-alt mr-2"></i> Dokumen Pelamar: <span id="applicantName"></span></h5>
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
