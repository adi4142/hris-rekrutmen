@extends('layouts.admin')

@section('title', 'Detail Lamaran Pelamar')
@section('page_title', 'Daftar Lamaran: ' . $applicant->name)

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ $applicant->photo ? asset('storage/' . $applicant->photo) : asset('dist/img/default-user.png') }}"
                        alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $applicant->name }}</h3>
                <p class="text-muted text-center">{{ $applicant->email }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>No HP</b> <a class="float-right">{{ $applicant->phone }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gender</b> <a class="float-right">{{ $applicant->gender }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Usia</b> <a class="float-right">{{ $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->age . ' Tahun' : '-' }}</a>
                    </li>
                </ul>

                @php
                    $cvUrl = $applicant->cv_file ? asset("storage/" . $applicant->cv_file) : null;
                @endphp
                @if($cvUrl)
                    <a href="{{ $cvUrl }}" target="_blank" class="btn btn-primary btn-block"><b>Lihat CV</b></a>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <h3 class="card-title">Riwayat Lamaran</h3>
                <div class="card-tools">
                   <a href="{{ route('jobapplication.index') }}" class="btn btn-tool" title="Kembali"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane">
                        @forelse($applicant->applications as $app)
                            <div class="post">
                                <div class="user-block">
                                    <span class="username">
                                        <a href="#">{{ $app->jobVacancie->title }}</a>
                                        <a href="#" class="float-right btn-tool"><i class="fas fa-clock"></i> {{ $app->created_at->diffForHumans() }}</a>
                                    </span>
                                    <span class="description">
                                        Departemen: {{ optional($app->jobVacancie->departement)->name }} - 
                                        Status: 
                                        @php
                                            $badgeClass = 'secondary';
                                            $statusLabel = ucfirst($app->status);
                                            switch($app->status) {
                                                case 'pending': 
                                                    $badgeClass = 'cyan'; 
                                                    $statusLabel = 'Baru';
                                                    break;
                                                case 'applied': 
                                                    $badgeClass = 'info'; 
                                                    $statusLabel = 'Review Berkas';
                                                    break;
                                                case 'process': 
                                                    $badgeClass = 'warning'; 
                                                    $statusLabel = 'Seleksi';
                                                    break;
                                                case 'accepted': 
                                                    $badgeClass = 'success'; 
                                                    $statusLabel = 'Diterima';
                                                    break;
                                                case 'rejected': 
                                                    $badgeClass = 'danger'; 
                                                    $statusLabel = 'Ditolak';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Melamar pada tanggal {{ $app->created_at->format('d M Y H:i') }}.
                                </p>

                                <p>
                                    <a href="{{ route('jobapplication.show', $app->application_id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-tasks mr-1"></i> Proses Seleksi & Detail
                                    </a>
                                </p>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada riwayat lamaran.</p>
                        @endforelse
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
@endsection
