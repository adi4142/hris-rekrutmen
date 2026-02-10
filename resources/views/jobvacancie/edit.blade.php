@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Job Vacancie</h3>
    </div>
    <div class="card-body">
<form action="{{ route('jobvacancie.update', $editJobVacancie->vacancies_id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" value="{{ $editJobVacancie->title }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="departement_id">Departement</label>
                            <select name="departement_id" id="departement_id">
                                @foreach($departement as $departement)
                                    <option value="{{ $departement->departement_id }}" {{ $editJobVacancie->departement_id == $departement->departement_id ? 'selected' : '' }}>{{ $departement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="position_id">Position</label>
                            <select name="position_id" id="position_id">
                                @foreach($position as $position)
                                    <option value="{{ $position->position_id }}" {{ $editJobVacancie->position_id == $position->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description" value="{{ $editJobVacancie->description }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="requirements">Requirements</label>
                            <input type="text" name="requirements" id="requirements" value="{{ $editJobVacancie->requirements }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status">
                                <option value="open" {{ $editJobVacancie->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $editJobVacancie->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit">Update</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection