@extends('layouts.app')

@section('title', 'Edit Selection Applicant')
@section('page_title', 'Selection Applicant')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Selection Applicant</h3>
    </div>
    <form action="{{ route('selectionapplicant.update', $selectionApplicant->selection_applicant_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="application_id">Job Applicant</label>
                <select name="application_id" id="application_id" class="form-control @error('application_id') is-invalid @enderror">
                    <option value="">-- Select Applicant --</option>
                    @foreach($jobApplications as $application)
                        <option value="{{ $application->application_id }}" {{ (old('application_id') ?? $selectionApplicant->application_id) == $application->application_id ? 'selected' : '' }}>
                            {{ $application->jobApplicant->name }} - {{ $application->jobVacancie->title }}
                        </option>
                    @endforeach
                </select>
                @error('application_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="selection_id">Selection Stage</label>
                <select name="selection_id" id="selection_id" class="form-control @error('selection_id') is-invalid @enderror">
                    <option value="">-- Select Stage --</option>
                    @foreach($selections as $selection)
                        <option value="{{ $selection->selection_id }}" {{ (old('selection_id') ?? $selectionApplicant->selection_id) == $selection->selection_id ? 'selected' : '' }}>
                            {{ $selection->name }}
                        </option>
                    @endforeach
                </select>
                @error('selection_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" name="score" id="score" class="form-control @error('score') is-invalid @enderror" value="{{ old('score') ?? $selectionApplicant->score }}" placeholder="Enter score">
                @error('score')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="pending" {{ (old('status') ?? $selectionApplicant->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ (old('status') ?? $selectionApplicant->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ (old('status') ?? $selectionApplicant->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Enter notes">{{ old('notes') ?? $selectionApplicant->notes }}</textarea>
                @error('notes')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('selectionapplicant.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
