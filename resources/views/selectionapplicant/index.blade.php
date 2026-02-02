@extends('layouts.app')

@section('title', 'Selection Applicant')
@section('page_title', 'Selection Applicant')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">List Selection Applicant</h3>
        <div class="card-tools">
            <a href="{{ route('selectionapplicant.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Selection Applicant
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Applicant Name</th>
                        <th>Selection Stage</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectionApplicants as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->application_id->jobApplicant->name ?? '-' }}</td>
                        <td>{{ $s->selection_id->name ?? '-' }}</td>
                        <td>{{ $s->score }}</td>
                        <td>
                            @if($s->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($s->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $s->notes }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('selectionapplicant.edit', $s->selection_applicant_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('selectionapplicant.destroy', $s->selection_applicant_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')" title="Delete">
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
    </div>
</div>
@endsection