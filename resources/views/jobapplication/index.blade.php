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
                                    <span class="text-danger">Data Pelamar Tidak Ditemukan</span>
                                @endif
                            </td>
                            <td>
                                @if($app->jobVacancie)
                                    <span class="font-weight-bold text-primary">Melamar Sebagai : {{ $app->jobVacancie->title }}</span><br>
                                    <small>
                                        Departemen : {{ optional($app->jobVacancie->departement)->name ?? '-' }}
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
                                @if($app->jobApplicant)
                                    @php
                                        $docs = [
                                            'cv_file' => ['icon' => 'fa-file-pdf', 'title' => 'CV'],
                                            'cover_letter' => ['icon' => 'fa-envelope-open-text', 'title' => 'Surat Lamaran'],
                                            'portfolio' => ['icon' => 'fa-briefcase', 'title' => 'Portofolio'],
                                            'last_diploma' => ['icon' => 'fa-graduation-cap', 'title' => 'Ijazah'],
                                            'transcript' => ['icon' => 'fa-file-invoice', 'title' => 'Transkrip'],
                                            'supporting_certificates' => ['icon' => 'fa-certificate', 'title' => 'Sertifikat'],
                                            'work_experience' => ['icon' => 'fa-user-tie', 'title' => 'Pengalaman']
                                        ];
                                    @endphp
                                    <div class="d-flex flex-wrap mb-2" style="gap: 5px;">
                                        @foreach($docs as $field => $data)
                                            @if($app->jobApplicant->$field)
                                                <a href="{{ asset('storage/' . $app->jobApplicant->$field) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="{{ $data['title'] }}">
                                                    <i class="fas {{ $data['icon'] }}"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" title="Update Status">
                                        <i class="fas fa-edit mr-1"></i> Status
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check mr-2 text-success"></i> Administrasi OK
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="process">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-tasks mr-2 text-info"></i> Seleksi
                                            </button>
                                        </form>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-user-check mr-2 text-success"></i> Terima
                                            </button>
                                        </form>

                                        <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-times mr-2 text-danger"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <form action="{{ route('jobapplication.destroy', $app->application_id) }}" method="POST" class="ml-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lamaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Hapus Permanen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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