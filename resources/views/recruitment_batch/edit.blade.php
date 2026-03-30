@extends('layouts.admin')
@section('title', 'Edit Jadwal Batch')
@section('page_title', 'Edit Jadwal Batch')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('recruitment-batch.update', $batch->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Informasi Dasar Batch - <strong>{{ $vacancy->title }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nama Batch <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg" value="{{ $batch->name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control form-control-lg" value="{{ $batch->date }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tahapan Seleksi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" id="add-stage">
                            <i class="fas fa-plus"></i> Tambah Tahapan
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0" id="stages-table">
                        <thead class="bg-light">
                            <tr>
                                <th>Tahapan</th>
                                <th style="width: 150px">Mulai</th>
                                <th style="width: 150px">Selesai</th>
                                <th>Lokasi</th>
                                <th style="width: 50px"></th>
                            </tr>
                        </thead>
                        <tbody id="stages-container">
                            @foreach($batch->stages as $index => $stage)
                            <tr class="stage-row">
                                <td>
                                    <select name="stages[{{ $index }}][selection_id]" class="form-control" required>
                                        <option value="">-- Pilih Tahapan --</option>
                                        @foreach($selections as $sel)
                                            <option value="{{ $sel->selection_id }}" {{ $stage->selection_id == $sel->selection_id ? 'selected' : '' }}>{{ $sel->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="time" name="stages[{{ $index }}][start_time]" class="form-control" value="{{ $stage->start_time }}"></td>
                                <td><input type="time" name="stages[{{ $index }}][end_time]" class="form-control" value="{{ $stage->end_time }}"></td>
                                <td><input type="text" name="stages[{{ $index }}][location]" class="form-control" value="{{ $stage->location }}"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('recruitment-batch.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-warning px-5 shadow font-weight-bold text-white">Perbarui Jadwal Batch</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Template Stage Row --}}
<template id="stage-row-template">
    <tr class="stage-row">
        <td>
            <select name="stages[INDEX][selection_id]" class="form-control" required>
                <option value="">-- Pilih Tahapan --</option>
                @foreach($selections as $sel)
                    <option value="{{ $sel->selection_id }}">{{ $sel->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="time" name="stages[INDEX][start_time]" class="form-control"></td>
        <td><input type="time" name="stages[INDEX][end_time]" class="form-control"></td>
        <td><input type="text" name="stages[INDEX][location]" class="form-control" placeholder="Ruang / Zoom"></td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
        </td>
    </tr>
</template>

@push('scripts')
<script>
    let stageCount = {{ $batch->stages->count() }};
    const container = document.getElementById('stages-container');
    const template = document.getElementById('stage-row-template').innerHTML;

    function addRow() {
        let rowHtml = template.replace(/INDEX/g, stageCount);
        container.insertAdjacentHTML('beforeend', rowHtml);
        stageCount++;
    }

    document.getElementById('add-stage').addEventListener('click', addRow);

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.parentElement.classList.contains('remove-row')) {
            const row = e.target.closest('.stage-row');
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada satu tahapan.');
            }
        }
    });

    // Validasi Time Before Submit
    $(document).on('submit', 'form', function(e) {
        const rows = $('.stage-row');
        let isValid = true;

        rows.each(function(index) {
            const row = $(this);
            const startTime = row.find('input[type="time"][name*="[start_time]"]').val();
            const endTime = row.find('input[type="time"][name*="[end_time]"]').val();
            const stageName = row.find('select option:checked').text() || "Tahapan " + (index + 1);

            if (startTime && endTime && startTime >= endTime) {
                alert(`Error pada ${stageName}:\nJam Selesai (${endTime}) tidak boleh lebih awal atau sama dengan Jam Mulai (${startTime})!`);
                isValid = false;
                return false; // Break loop
            }
        });

        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush
@endsection
