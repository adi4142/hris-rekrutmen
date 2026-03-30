<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="bg-light small font-weight-bold text-uppercase">
            <tr>
                @if(in_array($phase, ['review', 'selection', 'offering']))
                <th width="40" class="text-center">#</th>
                @endif
                <th>Pelamar</th>
                @if($phase == 'review')
                    <th>Dokumen</th>
                @endif
                @if($phase == 'selection')
                    <th>Batch Info</th>
                    <th>Progress / Skor</th>
                @endif
                @if($phase == 'offering')
                    <th>Hasil Akhir Skor</th>
                @endif
                <th>Status</th>
                <th width="100">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                @include('jobapplication.partials._applicant_row', ['app' => $app, 'phase' => $phase])
            @empty
            <tr>
                <td colspan="10" class="text-center p-5 text-muted">
                    <i class="fas fa-user-slash fa-4x mb-4 opacity-25"></i><br>
                    <h5 class="font-weight-light">Tidak ada pelamar dalam fase ini.</h5>
                    <p class="small">Silakan cek fase lain atau ganti lowongan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
