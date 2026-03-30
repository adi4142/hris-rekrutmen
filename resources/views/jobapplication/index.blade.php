@extends('layouts.admin')
@section('title', 'Daftar Pelamar')
@section('page_title', 'Data Lamaran')

@section('content')
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif
            <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Pelamar</th>
                            <th>Kontak</th>
                            <th>Total Lamaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $loop->iteration + ($applicants->currentPage() - 1) * $applicants->perPage() }}</td>
                            <td>
                                <strong>{{ $applicant->name }}</strong><br>
                                <span class="badge badge-light">{{ $applicant->gender }}</span>
                                <small class="text-muted">{{ $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->age . ' Tahun' : '-' }}</small>
                            </td>
                            <td>
                                <i class="fas fa-envelope mr-1 text-muted"></i> {{ $applicant->email }}<br>
                                <i class="fas fa-phone mr-1 text-muted"></i> {{ $applicant->phone }}
                            </td>
                            <td>
                                <span class="badge badge-info text-md">{{ $applicant->applications_count }} Lamaran</span>
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    <button type="button" class="btn btn-info btn-sm btn-view-profile" 
                                        data-id="{{ $applicant->job_applicant_id }}"
                                        title="Lihat Profil">
                                        <i class="fas fa-user text-white"></i> Profil
                                    </button>
                                    <a href="{{ route('jobapplication.applicant', $applicant->job_applicant_id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Lamaran
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Belum ada data pelamar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $applicants->links() }}
            </div>

{{-- MODAL DETAIL PROFIL --}}
<div class="modal fade" id="modalProfileDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-circle mr-2"></i> Detail Profil Pelamar</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-4 bg-light text-center p-4 border-right">
                        <img id="p-photo" src="" class="img-thumbnail rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 id="p-name" class="font-weight-bold mb-1"></h4>
                        <p id="p-email" class="text-muted small mb-3"></p>
                        <div class="badge badge-primary px-3 py-2">PELAMAR</div>
                    </div>
                    <div class="col-md-8 p-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3 font-weight-bold">Informasi Personal</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                                <div id="p-phone" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Jenis Kelamin</label>
                                <div id="p-gender" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Tanggal Lahir</label>
                                <div id="p-dob" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label class="small text-muted mb-1 d-block">Alamat Lengkap</label>
                                <div id="p-address" class="text-dark"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-view-profile').on('click', function() {
        const id = $(this).data('id');
        
        // Reset and show loading
        $('#p-name, #p-email, #p-phone, #p-address, #p-dob, #p-gender').text('...');
        $('#p-photo').attr('src', '{{ asset("img/user2-160x160.jpg") }}');
        $('#modalProfileDetail').modal('show');

        $.ajax({
            url: `/jobapplicant/${id}/profile-ajax`,
            method: 'GET',
            success: function(data) {
                $('#p-name').text(data.name);
                $('#p-email').text(data.email);
                $('#p-phone').text(data.phone);
                $('#p-address').text(data.address);
                $('#p-dob').text(data.date_of_birth);
                $('#p-gender').text(data.gender);
                $('#p-photo').attr('src', data.photo);
            },
            error: function() {
                alert('Gagal mengambil data profil.');
                $('#modalProfileDetail').modal('hide');
            }
        });
    });
});
</script>
@endpush
@endsection
