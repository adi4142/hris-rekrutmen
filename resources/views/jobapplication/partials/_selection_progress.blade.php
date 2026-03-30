@php 
    $progress = $app->selection_progress;
    $totalStages = $progress['total'];
    $completedStagesCount = $progress['completed'];
    $percent = $progress['percent'];
    $isSelectionFinished = $progress['is_finished'];
    $nextStage = $progress['next_stage'];
@endphp

@if($app->batch)
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <small class="font-weight-bold {{ $isSelectionFinished ? 'text-success' : 'text-primary' }}">
                {{ $isSelectionFinished ? 'SELEKSI SELESAI' : 'PROGRESS: ' . $completedStagesCount . '/' . $totalStages }}
            </small>
            <small class="font-weight-bold">{{ round($percent) }}%</small>
        </div>
        <div class="progress progress-xs mb-2" style="height: 6px; border-radius: 3px;">
            <div class="progress-bar {{ $isSelectionFinished ? 'bg-success' : 'bg-primary' }} progress-bar-striped" style="width: {{ $percent }}%"></div>
        </div>
    </div>
    
    <div class="d-flex flex-wrap" style="gap: 6px;">
        @foreach($app->batch->stages->sortBy('date') as $bs)
            @php 
                $res = $app->selectionApplicant->where('selection_id', $bs->selection_id)->first(); 
                $scoreParam = $res->score ?? '';
                $notesParam = $res->notes ?? '';
                $statusParam = $res->status ?? 'unprocess';
                
                $isCompleted = ($statusParam != 'unprocess');
                $badgeClass = 'badge-light border text-muted';
                $icon = 'fa-clock';
                $scoreText = '';

                if ($isCompleted) {
                    $scoreText = ' ('.number_format($res->score, 0).'%)';
                    if ($statusParam == 'passed') {
                        $badgeClass = 'badge-success'; $icon = 'fa-check-circle';
                    } elseif ($statusParam == 'failed') {
                        $badgeClass = 'badge-danger'; $icon = 'fa-times-circle';
                    } else {
                        $badgeClass = 'badge-primary'; $icon = 'fa-check';
                    }
                } elseif (\Carbon\Carbon::parse($bs->date)->isToday()) {
                    $badgeClass = 'badge-warning text-dark'; $icon = 'fa-star';
                }

                // Aspects Data...
                $aspectsData = [];
                foreach($bs->selection->aspects as $aspect) {
                    $aspectScore = '';
                    if ($res) {
                        $scoreRecord = $res->aspectScores->where('aspect_id', $aspect->aspect_id)->first();
                        if ($scoreRecord) $aspectScore = $scoreRecord->score;
                    }
                    $aspectsData[] = ['id' => $aspect->aspect_id, 'name' => $aspect->name, 'score' => $aspectScore];
                }
                $aspectsJson = json_encode($aspectsData);

                // Determine if current stage score plus previous ones will complete the selection
                $canPromote = ($completedStagesCount >= $totalStages - 1 && !$isCompleted) || $isSelectionFinished;
            @endphp
            <a href="javascript:void(0)" 
               class="badge {{ $badgeClass }} shadow-sm px-2 py-1" 
               style="font-size: 11px; font-weight: 500;"
               onclick="openScoreModal('{{ $app->application_id }}', '{{ addslashes($app->jobApplicant->name) }}', '{{ $bs->selection_id }}', '{{ addslashes($bs->selection->name) }}', '{{ $bs->id }}', '{{ $scoreParam }}', '{{ addslashes($notesParam) }}', '{{ $statusParam }}', '{{ base64_encode($aspectsJson) }}', {{ $canPromote ? 'true' : 'false' }})">
                <i class="fas {{ $icon }} mr-1"></i> {{ $bs->selection->name }}{{ $scoreText }}
            </a>
        @endforeach
    </div>

    @if(!$isSelectionFinished && $nextStage)
        <div class="mt-2 p-2 bg-light rounded border border-dashed text-center">
            <small class="text-muted">
                <i class="fas fa-arrow-right mr-1"></i> 
                Lanjut ke: <strong>{{ $nextStage->selection->name }}</strong> 
                ({{ \Carbon\Carbon::parse($nextStage->date)->translatedFormat('d M') }})
            </small>
        </div>
    @elseif($isSelectionFinished)
        <div class="mt-2 p-1 bg-success-light rounded text-center" style="background-color: #f0fdf4; border: 1px dashed #bcf0da;">
            <small class="text-success font-weight-bold">
                <i class="fas fa-flag-checkered mr-1"></i> Seluruh Tahapan Selesai
            </small>
        </div>
    @endif
@else
    <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Belum ada batch</span>
@endif
