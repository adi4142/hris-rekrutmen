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
                            <select name="status" id="status" class="form-control">
                                <option value="open" {{ $editJobVacancie->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $editJobVacancie->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        @php
                            $requiredDocs = json_decode($editJobVacancie->required_documents) ?? [];
                        @endphp
                        <div class="col-md-12 form-group">
                            <label>Dokumen Yang Diperlukan</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_cv" name="required_documents[]" value="CV" {{ in_array('CV', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_cv" class="custom-control-label">CV</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ktp" name="required_documents[]" value="KTP" {{ in_array('KTP', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_ktp" class="custom-control-label">KTP</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ijazah" name="required_documents[]" value="Ijazah" {{ in_array('Ijazah', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_ijazah" class="custom-control-label">Ijazah</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_transkip" name="required_documents[]" value="Transkip Nilai" {{ in_array('Transkip Nilai', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_transkip" class="custom-control-label">Transkip Nilai</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_skck" name="required_documents[]" value="SKCK" {{ in_array('SKCK', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_skck" class="custom-control-label">SKCK</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_health" name="required_documents[]" value="Surat Keterangan Sehat" {{ in_array('Surat Keterangan Sehat', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_health" class="custom-control-label">Surat Keterangan Sehat</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_portfolio" name="required_documents[]" value="Portfolio" {{ in_array('Portfolio', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_portfolio" class="custom-control-label">Portfolio</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_photo" name="required_documents[]" value="Pas Foto" {{ in_array('Pas Foto', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_photo" class="custom-control-label">Pas Foto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('jobvacancie.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection