@extends('layouts.admin')
@section('page_title', 'Seleksi')
@section('card_tools')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
    <i class="fas fa-plus"></i> Tambah
</button>
@endsection
@section('content')

        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($selections as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->name }}</td>
                    <td>{{ $j->description }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $j->selection_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('selection.destroy', $j->selection_id) }}" method="POST" style="display:inline-block; margin-left: 5px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="modal-edit-{{ $j->selection_id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Seleksi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('selection.update', $j->selection_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Nama Seleksi</label>
                                        <input type="text" name="name" class="form-control" value="{{ $j->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea name="description" class="form-control">{{ $j->description }}</textarea>
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
                <h5 class="modal-title">Tambah Seleksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('selection.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Seleksi</label>
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