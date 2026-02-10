@extends('layouts.admin')

@section('title', 'Add Selection Applicant')
@section('page_title', 'Selection Applicant')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Add New Selection Applicant</h3>
    </div>
    <form action="{{ route('selectionapplicant.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="application_id">Applicant Name</label>
                @if(isset($selectedApplication))
                    <input type="text" class="form-control" value="{{ $selectedApplication->jobApplicant->name }} - {{ $selectedApplication->jobVacancie->title }}" disabled>
                    <input type="hidden" name="application_id" value="{{ $selectedApplication->application_id }}">
                @else
                    <select name="application_id" id="application_id" class="form-control @error('application_id') is-invalid @enderror">
                        <option value="">-- Select Applicant --</option>
                        @foreach($jobApplications as $app)
                            <option value="{{ $app->application_id }}" {{ old('application_id') == $app->application_id ? 'selected' : '' }}>
                                {{ $app->jobApplicant->name }} - {{ $app->jobVacancie->title }}
                            </option>
                        @endforeach
                    </select>
                     @error('application_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <div class="form-group">
                <label for="selection_id">Selection Stage</label>
                <select name="selection_id" id="selection_id" class="form-control @error('selection_id') is-invalid @enderror">
                    <option value="">-- Select Stage --</option>
                    @foreach($selections as $selection)
                        <option value="{{ $selection->selection_id }}" {{ old('selection_id') == $selection->selection_id ? 'selected' : '' }}>
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
                <input type="number" name="score" id="score" class="form-control @error('score') is-invalid @enderror" value="{{ old('score') }}" placeholder="Enter score">
                @error('score')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="">-- Select Status --</option>
                    <option value="passed" {{ old('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="process" {{ old('status') == 'process' ? 'selected' : '' }}>Process</option>
                    <option value="unprocess" {{ old('status') == 'unprocess' ? 'selected' : '' }}>Unprocess</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Enter notes">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('selectionapplicant.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
