<form action="{{ route('jobapplication.batchProcess') }}" method="POST" id="phaseReviewForm">
    @csrf
    <div class="p-3 bg-light border-bottom d-flex justify-content-between" style="background-color: var(--bg-card) !important;">
        <div class="custom-control custom-checkbox pl-4" style="color: var(--text-primary) !important;">
            <input class="custom-control-input" type="checkbox" id="checkAll">
            <label for="checkAll" class="custom-control-label">Pilih Semua</label>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-success shadow-sm" onclick="submitAjaxPhase('#phaseReviewForm', 'pass_review', 'Luluskan berkas pelamar terpilih?')">
                <i class="fas fa-check-circle mr-1"></i> Lulus Berkas
            </button>
            <button type="button" class="btn btn-danger shadow-sm" onclick="submitAjaxPhase('#phaseReviewForm', 'fail_review', 'Gagalkan berkas pelamar terpilih?')">
                <i class="fas fa-times-circle mr-1"></i> Tidak Lulus
            </button>
        </div>
    </div>
    
    @include('jobapplication.partials._table_content', ['phase' => 'review'])
</form>
