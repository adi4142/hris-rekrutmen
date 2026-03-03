@extends('layouts.admin')
@section('page_title', 'Daftar Divisi')

@section('card_tools')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
    <i class="fas fa-plus"></i> Tambah
</button>
@endsection

@section('content')
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Divisi</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($division as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            @if (auth()->user()->role->name !== 'Super Admin')            
            <td>
                <form action="{{ route('division.destroy', $v->division_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-edit-{{ $v->division_id }}">Edit</button>
                    <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger">Hapus</button>
                </form>
            </td>
            @endif            
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="modal-edit-{{ $v->division_id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Divisi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('division.update', $v->division_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama Divisi</label>
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

<!-- Create Modal -->
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('division.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Divisi</label>
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