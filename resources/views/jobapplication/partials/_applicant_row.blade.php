<tr>
    @if(in_array($phase, ['review', 'selection', 'offering']))
    <td class="text-center align-middle">
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input app-checkbox" type="checkbox" name="application_ids[]" id="chk{{ $app->application_id }}" value="{{ $app->application_id }}">
            <label for="chk{{ $app->application_id }}" class="custom-control-label"></label>
        </div>
    </td>
    @endif
    <td class="align-middle">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem;">
                    {{ strtoupper(substr($app->jobApplicant->name, 0, 1)) }}
                </div>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold">{{ $app->jobApplicant->name }}</h6>
                <small class="text-muted"><i class="fas fa-envelope mr-1"></i> {{ $app->jobApplicant->email }}</small>
            </div>
        </div>
    </td>
    
    @if($phase == 'review')
    <td class="align-middle">
        <button type="button" class="btn btn-info btn-sm btn-view-app-docs" 
            data-id="{{ $app->application_id }}"
            data-name="{{ $app->jobApplicant->name }}">
            <i class="fas fa-file-alt mr-1"></i> Lihat Berkas
        </button>
    </td>
    @endif

    @if($phase == 'selection')
    <td class="align-middle">
        @if($app->batch)
            <span class="text-primary font-weight-bold">{{ $app->batch->name }}</span><br>
            <small class="text-muted">{{ \Carbon\Carbon::parse($app->batch->date)->format('d M Y') }}</small>
        @else
            <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Belum ada batch</span>
        @endif
    </td>
    <td class="align-middle">
        @include('jobapplication.partials._selection_progress')
    </td>
    @endif

    @if($phase == 'offering')
    <td class="align-middle text-center">
        <div class="h4 mb-0 font-weight-bold text-success">
            {{ number_format($app->selectionApplicant->avg('score'), 1) }}
        </div>
        <small class="text-muted">Rata-rata Skor Seleksi</small>
    </td>
    @endif

    <td class="align-middle">
        @php $badgeState = $app->badge_status; @endphp
        <span class="badge badge-{{ $badgeState['badge'] }} px-2 py-1 text-white" style="{{ $badgeState['style'] }}">
            {{ $badgeState['label'] }}
        </span>
    </td>
    <td class="align-middle text-right" style="white-space: nowrap;">
        <div class="d-inline-flex justify-content-end align-items-center" style="gap: 5px; flex-wrap: nowrap;">
             @if($phase == 'offering')
                @if($app->status == 'offering')
                    <a href="{{ route('jobapplication.previewOffering', $app->application_id) }}" target="_blank" class="btn btn-info btn-sm shadow-sm" title="Pratinjau Draft Offering">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                @endif
                @if($app->status == 'negotiation_requested')
                    <button type="button" class="btn btn-orange btn-sm shadow-sm text-white" style="background-color: #fd7e14"
                            onclick="openNegotiationModal('{{ $app->application_id }}', '{{ addslashes($app->jobApplicant->name) }}', '{{ $app->offering_salary }}', '{{ $app->expected_salary }}', '{{ addslashes($app->negotiation_reason) }}')"
                            title="Review Negosiasi Gaji">
                        <i class="fas fa-comments"></i>
                    </button>
                @endif
                <button type="button" class="btn btn-success btn-sm shadow-sm" 
                        onclick="openOfferingDetail('{{ $app->application_id }}', '{{ addslashes($app->offering_job_desc) }}', '{{ $app->offering_salary }}', '{{ addslashes($app->offering_working_hours) }}', '{{ addslashes($app->offering_leave_quota) }}', '{{ $app->offering_start_date }}')"
                        title="Detail & Update Draft Offering">
                    <i class="fas fa-edit"></i>
                </button>
            @endif
            <button type="button" class="btn btn-default btn-sm shadow-sm" title="Lihat Detail Profil"
                    onclick="fetchProfile('{{ $app->job_applicant_id }}')">
                <i class="fas fa-eye text-primary"></i>
            </button>
        </div>
    </td>
</tr>
