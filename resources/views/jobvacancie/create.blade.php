@extends('layouts.admin')
@section('content')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Tambah Job Vacancie</h3>
    </div>
    <div class="card-body">
<form action="{{ route('jobvacancie.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Job Vacancie</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="departement_id">Departement</label>
                            <select name="departement_id" id="departement_id" class="form-control">
                                <option value="">Pilih Departement</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->departement_id }}">{{ $departement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="position_id">Position</label>
                            <select name="position_id" id="position_id" class="form-control">
                                <option value="">Pilih Position</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->position_id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="requirements">Requirements</label>
                            <input type="text" name="requirements" id="requirements" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Dokumen Yang Diperlukan</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_cv" name="required_documents[]" value="CV">
                                        <label for="doc_cv" class="custom-control-label">CV</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ktp" name="required_documents[]" value="KTP">
                                        <label for="doc_ktp" class="custom-control-label">KTP</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ijazah" name="required_documents[]" value="Ijazah">
                                        <label for="doc_ijazah" class="custom-control-label">Ijazah</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_transkip" name="required_documents[]" value="Transkip Nilai">
                                        <label for="doc_transkip" class="custom-control-label">Transkip Nilai</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_skck" name="required_documents[]" value="SKCK">
                                        <label for="doc_skck" class="custom-control-label">SKCK</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_health" name="required_documents[]" value="Surat Keterangan Sehat">
                                        <label for="doc_health" class="custom-control-label">Surat Keterangan Sehat</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_portfolio" name="required_documents[]" value="Portfolio">
                                        <label for="doc_portfolio" class="custom-control-label">Portfolio</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_photo" name="required_documents[]" value="Pas Foto">
                                        <label for="doc_photo" class="custom-control-label">Pas Foto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <a href="{{ route('jobvacancie.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection