@extends('layouts.admin')
@section('page_title', 'Seleksi')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-layer-group mr-2 text-primary"></i> Tahap Seleksi</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Aspek Penilaian</th>
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
                        <ul class="pl-3 mb-0">
                            @foreach($j->aspects as $aspect)
                                <li>{{ $aspect->name }}</li>
                            @endforeach
                            @if($j->aspects->isEmpty())
                                <span class="text-muted small italic">-</span>
                            @endif
                        </ul>
                    </td>
                    <td style="white-space: nowrap;">
                        <div class="d-inline-flex align-items-center" style="gap: 5px;">
                            <button type="button" class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#modal-edit-{{ $j->selection_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('selection.destroy', $j->selection_id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Kamu serius?')" class="btn btn-danger btn-sm">
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
        </div>
    </div>
</div>

        @foreach ($selections as $j)
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
                            <hr>
                            <h6 class="font-weight-bold">Aspek Penilaian</h6>
                            <table class="table table-sm table-bordered" id="table-aspects-edit-{{ $j->selection_id }}">
                                <thead>
                                    <tr>
                                        <th>Nama Aspek</th>
                                        <th>Deskripsi Aspek (Opsional)</th>
                                        <th style="width: 50px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($j->aspects as $index => $aspect)
                                    <tr>
                                        <td><input type="text" name="aspects[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $aspect->name }}" required></td>
                                        <td><input type="text" name="aspects[{{ $index }}][description]" class="form-control form-control-sm" value="{{ $aspect->description }}"></td>
                                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-aspect-row"><i class="fas fa-times"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><button type="button" class="btn btn-sm btn-success btn-add-aspect" data-target="#table-aspects-edit-{{ $j->selection_id }}"><i class="fas fa-plus mr-1"></i> Tambah Aspek</button></td>
                                    </tr>
                                </tfoot>
                            </table>
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
                    <hr>
                    <h6 class="font-weight-bold">Aspek Penilaian</h6>
                    <table class="table table-sm table-bordered" id="table-aspects-create">
                        <thead>
                            <tr>
                                <th>Nama Aspek</th>
                                <th>Deskripsi Aspek (Opsional)</th>
                                <th style="width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="aspects[0][name]" class="form-control form-control-sm" placeholder="Contoh: Tes Tulis" required></td>
                                <td><input type="text" name="aspects[0][description]" class="form-control form-control-sm" placeholder="..."></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-aspect-row"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><button type="button" class="btn btn-sm btn-success btn-add-aspect" data-target="#table-aspects-create"><i class="fas fa-plus mr-1"></i> Tambah Aspek</button></td>
                            </tr>
                        </tfoot>
                    </table>
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

@push('scripts')
<script>
    $(document).ready(function() {
        let aspectIndex = 1000;

        // Tambah baris aspek
        $(document).on('click', '.btn-add-aspect', function() {
            let targetTable = $(this).data('target');
            let tbody = $(targetTable).find('tbody');
            
            let newRow = `
                <tr>
                    <td><input type="text" name="aspects[${aspectIndex}][name]" class="form-control form-control-sm" required></td>
                    <td><input type="text" name="aspects[${aspectIndex}][description]" class="form-control form-control-sm"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-aspect-row"><i class="fas fa-times"></i></button></td>
                </tr>
            `;
            tbody.append(newRow);
            aspectIndex++;
        });

        // Hapus baris aspek
        $(document).on('click', '.remove-aspect-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush