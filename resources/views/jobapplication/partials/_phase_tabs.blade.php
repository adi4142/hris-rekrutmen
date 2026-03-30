<div class="phase-container d-flex flex-wrap justify-content-end" style="gap: 8px;">
    <a href="{{ route('jobapplication.manage', ['vacancy_id' => $selectedVacancyId, 'phase' => 'review']) }}" 
       class="phase-item {{ $phase == 'review' ? 'active' : '' }}">
        <div class="phase-number ">1</div>
        <div class="phase-label">Review Berkas</div>
    </a>
    <a href="{{ route('jobapplication.manage', ['vacancy_id' => $selectedVacancyId, 'phase' => 'selection']) }}" 
       class="phase-item {{ $phase == 'selection' ? 'active' : '' }}">
        <div class="phase-number">2</div>
        <div class="phase-label">Mulai Seleksi</div>
    </a>
    <a href="{{ route('jobapplication.manage', ['vacancy_id' => $selectedVacancyId, 'phase' => 'offering']) }}" 
       class="phase-item {{ $phase == 'offering' ? 'active' : '' }}">
        <div class="phase-number">3</div>
        <div class="phase-label">Offering</div>
    </a>
    <a href="{{ route('jobapplication.manage', ['vacancy_id' => $selectedVacancyId, 'phase' => 'final']) }}" 
       class="phase-item {{ $phase == 'final' ? 'active final' : '' }}">
        <div class="phase-number"><i class="fas fa-check text-xs"></i></div>
        <div class="phase-label">Selesai</div>
    </a>
</div>
