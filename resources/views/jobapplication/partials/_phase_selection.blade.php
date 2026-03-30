<div class="p-3 bg-light border-bottom">
    <form action="" method="POST" class="form-horizontal" id="bulkSelectionForm">
        @csrf
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="custom-control custom-checkbox pl-4">
                    <input class="custom-control-input" type="checkbox" id="checkAll">
                    <label for="checkAll" class="custom-control-label font-weight-bold">Pilih Semua</label>
                </div>
                <div class="d-flex align-items-center">
                    <select name="batch_id" class="form-control mr-2" id="batchSelect" style="width: auto;">
                        <option value="">-- Hubungkan ke Batch --</option>
                        @foreach($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ \Carbon\Carbon::parse($b->date)->format('d M') }})</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-info shadow-sm" onclick="submitBulkSelection('{{ route('jobapplication.assignBatch') }}')">
                        <i class="fas fa-link mr-1"></i> Hubungkan Batch
                    </button>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="button" class="btn btn-success shadow-sm" onclick="submitBulkSelection('{{ route('jobapplication.promoteToOffering') }}', 'Yakin luluskan pelamar terpilih ke tahap Offering?')">
                    <i class="fas fa-check-circle mr-1"></i> Luluskan ke Offering
                </button>
                <button type="button" class="btn btn-danger shadow-sm" onclick="submitBulkSelection('{{ route('jobapplication.failSelection') }}', 'Yakin nyatakan pelamar terpilih TIDAK LULUS seleksi?')">
                    <i class="fas fa-times-circle mr-1"></i> Gagalkan
                </button>
            </div>
        </div>
    </form>
</div>
@include('jobapplication.partials._table_content', ['phase' => 'selection'])
