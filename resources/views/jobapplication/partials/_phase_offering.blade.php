<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th width="40" class="text-center pl-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                    </div>
                </th>
                <th>PELAMAR (DRAFT OFFERING)</th>
                <th class="text-center">DETAIL PENAWARAN</th>
                <th class="text-center">STATUS</th>
                <th width="180" class="text-right pr-4">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                @include('jobapplication.partials._applicant_row', ['app' => $app])
            @empty
                <tr>
                    <td colspan="5" class="py-5 text-center text-muted">
                        <i class="fas fa-handshake fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada pelamar di tahap Offering (Draft).</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="p-3 bg-light border-top d-flex justify-content-between align-items-center" style="background-color: var(--bg-card) !important; color: var(--text-primary) !important;">
    <button type="button" class="btn btn-primary shadow-sm" onclick="bulkApproveOffering('{{ csrf_token() }}', '{{ route('jobapplication.approveOffering') }}')">
        <i class="fas fa-paper-plane mr-2"></i> Approve & Kirim Offering
    </button>
</div>
