@extends('layouts.admin')

@section('title', 'Proses Seleksi')
@section('page_title', 'Proses Seleksi Pelamar')

@section('content')
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
                        @forelse($jobApplications as $app)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $app->jobApplicant->name }}</strong><br>
                                <small class="text-muted">{{ $app->jobApplicant->email }}</small>
                            </td>
                            <td>
                                {{ $app->jobVacancie->title }}<br>
                                <span class="badge badge-info">{{ $app->status }}</span>
                            </td>
                            <td>
                                @if($app->selectionApplicant->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($app->selectionApplicant as $selection)
                                            <li class="mb-2 p-2" style="background-color: #f4f6f9; border-radius: 5px;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $selection->selection->name ?? 'Tahapan Dihapus' }}</strong>
                                                        <br>
                                                        <small>Score: {{ $selection->score }} | Note: {{ $selection->notes }}</small>
                                                    </div>
                                                    <div>
                                                        @if($selection->status == 'passed')
                                                            <span class="badge badge-success">Lolos</span>
                                                        @elseif($selection->status == 'failed')
                                                            <span class="badge badge-danger">Gagal</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                        
                                                        <a href="{{ route('selectionapplicant.edit', $selection->selection_applicant_id) }}" class="ml-2 text-warning" title="Edit Nilai">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                         <form action="{{ route('selectionapplicant.destroy', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0 ml-1" onclick="return confirm('Hapus tahapan ini?')" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <small class="text-muted text-center d-block">Belum ada tahapan seleksi</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('selectionapplicant.create', ['application_id' => $app->application_id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Tahapan
                                </a>
                            </td>
                        </tr>
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
@endsection