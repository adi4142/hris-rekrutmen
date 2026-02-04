@extends('layouts.admin')

@section('title', 'Daftar Lamaran')
@section('page_title', 'Daftar Lamaran Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Lamaran Kerja</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Identitas Pelamar</th>
                            <th>Ringkasan Lowongan</th>
                            <th>Info Lamaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobapplications as $app)
                        <tr>
                            <td>
                                @if($app->jobApplicant)
                                    <span class="font-weight-bold" style="font-size: 1.1em;">{{ $app->jobApplicant->name }}</span><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope mr-1"></i> {{ $app->jobApplicant->email }}<br>
                                        <i class="fas fa-phone mr-1"></i> {{ $app->jobApplicant->phone }}<br>
                                        @php
                                            $age = $app->jobApplicant->date_of_birth ? \Carbon\Carbon::parse($app->jobApplicant->date_of_birth)->age : '-';
                                        @endphp
                                        <i class="fas fa-user mr-1"></i> {{ $app->jobApplicant->gender }} | {{ $age }} Tahun
                                    </small>
                                @else
                                    <span class="text-danger">Data Pelamar Terhapus</span>
                                @endif
                            </td>
                            <td>
                                @if($app->jobVacancie)
                                    <span class="font-weight-bold text-primary">{{ $app->jobVacancie->title }}</span><br>
                                    <small>
                                        {{ optional($app->jobVacancie->departement)->name ?? '-' }} <br>
                                        {{ optional($app->jobVacancie->position)->name ?? '-' }}
                                    </small>
                                @else
                                    <span class="text-muted">Lowongan Terhapus</span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $app->created_at->translatedFormat('d F Y') }}<br>
                                    <i class="far fa-clock mr-1"></i> {{ $app->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    $statusLabel = $app->status;
                                    
                                    switch($app->status) {
                                        case 'applied': $badgeClass = 'secondary'; $statusLabel = 'Baru Daftar'; break;
                                        case 'procces': $badgeClass = 'warning'; $statusLabel = 'Proses'; break;
                                        case 'process': $badgeClass = 'info'; $statusLabel = 'Seleksi'; break;
                                        case 'rejected': $badgeClass = 'danger'; $statusLabel = 'Ditolak'; break;
                                        case 'accepted': $badgeClass = 'success'; $statusLabel = 'Diterima'; break;
                                    }
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog"></i> Aksi
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($app->jobApplicant && $app->jobApplicant->cv_file)
                                            <a class="dropdown-item" href="{{ asset('storage/' . $app->jobApplicant->cv_file) }}" target="_blank">
                                                <i class="fas fa-file-download mr-2 text-primary"></i> Download CV
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        
                                        <h6 class="dropdown-header">Update Status</h6>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check mr-2 text-success"></i> Lolos Administrasi
                                            </button>
                                        </form>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="process">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-arrow-right mr-2 text-info"></i> Proses Seleksi
                                            </button>
                                        </form>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-user-check mr-2 text-success"></i> Terima
                                            </button>
                                        </form>

                                        <div class="dropdown-divider"></div>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-times mr-2 text-danger"></i> Tolak Lamaran
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data lamaran masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
