<td>
    {{ $app->jobVacancie->title }}<br>
    @if($app->status == 'process')
        <span class="badge badge-info">Seleksi Sedang Berlangsung</span>
    @elseif($app->status == 'passed')
        <span class="badge badge-success">Lolos Seleksi</span>
    @elseif($app->status == 'failed')
        <span class="badge badge-danger">Gagal Seleksi</span>
    @endif
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
                            <br>
                            <small><i class="far fa-calendar-alt"></i> {{ ($selection->batchStage->batch->date ?? $selection->selection_date) ? \Carbon\Carbon::parse($selection->batchStage->batch->date ?? $selection->selection_date)->format('d M Y') : '-' }}</small>
                        </div>
                        <div>
                            @if($selection->status == 'passed')
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Lolos</span>
                            @elseif($selection->status == 'failed')
                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Gagal</span>
                            @elseif($selection->status == 'process')
                                <span class="badge badge-info px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Dalam Proses</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Diproses</span>
                            @endif
                            
                            <div class="mt-2 text-right">
                                @if($selection->status == 'unprocess')
                                    <form action="{{ route('selectionapplicant.update', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="process">
                                        <button type="submit" class="btn btn-warning btn-xs shadow-sm" onclick="return confirm('Mulai proses tahapan ini?')">
                                            <i class="fas fa-play mr-1"></i> Mulai Proses
                                        </button>
                                    </form>
                                @endif

                                @if($selection->status == 'process')
                                    @php
                                        $isEvaluated = ($selection->score != 0 || $selection->notes != '-');
                                    @endphp

                                    @if(!$isEvaluated)
                                        <a href="{{ route('selectionapplicant.edit', $selection->selection_applicant_id) }}" class="btn btn-info btn-xs shadow-sm">
                                            <i class="fas fa-edit mr-1"></i> Input Nilai & Catatan
                                        </a>
                                    @else
                                        <div class="btn-group shadow-sm">
                                            <form action="{{ route('selectionapplicant.update', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="passed">
                                                <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Luluskan tahapan ini?')">
                                                    <i class="fas fa-check mr-1"></i> Lulus
                                                </button>
                                            </form>
                                            <form action="{{ route('selectionapplicant.update', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="failed">
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Gagalkan tahapan ini?')">
                                                    <i class="fas fa-times mr-1"></i> Gagal
                                                </button>
                                            </form>
                                        </div>
                                        <a href="{{ route('selectionapplicant.edit', $selection->selection_applicant_id) }}" class="btn btn-link btn-xs text-muted" title="Edit Nilai/Catatan">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </a>
                                    @endif
                                @endif

                                <form action="{{ route('selectionapplicant.destroy', $selection->selection_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 ml-1" onclick="return confirm('Hapus tahapan ini?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
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
