@extends('layouts.admin')

@section('title', 'Job Vacancie')
@section('page_title', 'Job Vacancie')

@section('card_tools')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
    <i class="fas fa-plus"></i> Tambah
</button>
@endsection

@section('content')  
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Posisi</th>
                                <th>Departemen</th>
                                <th>Deskripsi</th>
                                <th>Batas Tanggal</th>
                                <th>Requirements</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobVacancies as $jobVacancie)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jobVacancie->title }}</td>
                                <td>{{ $jobVacancie->departement->name }}</td>
                                <td>{{ $jobVacancie->description }}</td>
                                <td>{{ $jobVacancie->expired_at }}</td>
                                <td>
                                    @php
                                        $reqs = json_decode($jobVacancie->requirements, true);
                                    @endphp
                                    @if(is_array($reqs))
                                        <ul>
                                            @foreach(array_slice($reqs, 0, 3) as $req)
                                                <li>{{ Str::limit($req, 30) }}</li>
                                            @endforeach
                                            @if(count($reqs) > 3)
                                                <li>...</li>
                                            @endif
                                        </ul>
                                    @else
                                        {{ Str::limit($jobVacancie->requirements, 50) }}
                                    @endif
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-status" id="statusSwitch{{ $jobVacancie->vacancies_id }}" data-id="{{ $jobVacancie->vacancies_id }}" {{ $jobVacancie->status == 'open' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="statusSwitch{{ $jobVacancie->vacancies_id }}">{{ ucfirst($jobVacancie->status) }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $jobVacancie->vacancies_id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('jobvacancie.destroy', $jobVacancie->vacancies_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus posisi ini?')" title="Hapus">
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

                @foreach($jobVacancies as $jobVacancie)
                    @include('jobvacancie.partials.edit_modal', ['editJobVacancie' => $jobVacancie, 'departements' => $departements, 'positions' => $positions])
                @endforeach


@include('jobvacancie.partials.create_modal')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').change(function() {
        var toggle = $(this);
        var id = toggle.data('id');
        var label = toggle.siblings('.custom-control-label');
        
        $.ajax({
            url: `/jobvacancie/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    label.text(response.status.charAt(0).toUpperCase() + response.status.slice(1));
                }
            },
            error: function() {
                alert('Gagal mengubah status');
                toggle.prop('checked', !toggle.prop('checked')); // revert the switch
            }
        });
    });

    $(document).on('click', '.add-requirement', function() {
        var html = '<div class="input-group mb-2 requirement-item">' +
                   '<input type="text" name="requirements[]" class="form-control" placeholder="Requirement">' +
                   '<div class="input-group-append">' +
                   '<button type="button" class="btn btn-danger remove-requirement"><i class="fas fa-minus"></i></button>' +
                   '</div></div>';
        $(this).closest('.form-group').find('.requirements-container').append(html);
    });
    
    $(document).on('click', '.remove-requirement', function() {
        $(this).closest('.requirement-item').remove();
    });
});
</script>
@endpush