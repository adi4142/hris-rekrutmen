@extends('layouts.admin')
@section('page_title', 'Daftar Departement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-sitemap mr-2 text-primary"></i> Departemen</h3>
                    </div>
                    <div class="col-md-6 text-right d-flex justify-content-end align-items-center" style="gap:8px">
                        <form action="{{ route('departement.index') }}" method="GET">
                            <div class="input-group input-group-sm" style="width: 220px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari departemen..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    @if(request('search'))
                                    <a href="{{ route('departement.index') }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                    @endif
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Departement</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departement as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            <td style="white-space: nowrap;">
                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-edit-{{ $v->departement_id }}"><i class="fas fa-edit"></i> Edit</button>
                    <form action="{{ route('departement.destroy', $v->departement_id) }}" method="POST" class="m-0 p-0">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                </div>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="modal-edit-{{ $v->departement_id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Departement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('departement.update', $v->departement_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama Departement</label>
                                <input type="text" name="name" class="form-control" value="{{ $v->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" class="form-control">{{ $v->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Edit Modal -->
        @endforeach
    </tbody>
</table>
                </div>
            </div>
            <div class="card-footer clearfix">
                @if($departement->hasPages())
                    {{ $departement->appends(request()->input())->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Departement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('departement.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Departement</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Create Modal -->
@endsection