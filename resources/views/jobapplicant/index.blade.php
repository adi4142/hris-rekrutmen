@extends('layouts.admin')

@section('title', 'Job Applicant')
@section('page_title', 'Manajemen Pendaftar')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-users mr-2 text-primary"></i> Manajemen Pendaftar</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <form action="{{ route('jobapplicant.index') }}" method="GET" class="flex-grow-1 mr-2">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pelamar..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        @if(request('search'))
                                        <a href="{{ route('jobapplicant.index') }}" class="btn btn-danger border-0">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        @endif
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Info Diri</th>
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
                            <div class="btn-group" role="group">
                                @if($jobApplicant->user_id)
                                    {{-- Status akun aktif dihilangkan sesuai permintaan user --}}
                                @endif
                                
                                <button type="button" class="btn btn-info btn-sm btn-view-profile mr-1" 
                                    data-id="{{ $jobApplicant->job_applicant_id }}"
                                    id="modalProfileDetail"
                                    title="Lihat Profil">
                                    <i class="fas fa-user text-white"></i>
                                </button>

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
            </div>
            @if($jobApplicants->hasPages())
            <div class="card-footer bg-white">
                {{ $jobApplicants->appends(['search' => request('search')])->links() }}
            </div>
            @endif
        </div>
    </div>
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
@endsection

@push('scripts')
<script>
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
