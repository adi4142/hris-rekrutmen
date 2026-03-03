<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Job Vacancie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('jobvacancie.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="departement_id">Departement</label>
                            <select name="departement_id" id="departement_id" class="form-control" required>
                                <option value="">Pilih Departement</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->departement_id }}">{{ $departement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="position_id">Position</label>
                            <select name="position_id" id="position_id" class="form-control" required>
                                <option value="">Pilih Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->position_id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="description">Description</label>
                            <textarea type="text" name="description" id="description" class="form-control"></textarea>
                        </div>

                        <div class="col-md-6 form-group">
    <label>Tanggal Kadaluarsa</label>
    <input type="date" name="expired_at" class="form-control" required>
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
                        <div class="col-md-12 form-group">
                            <label for="requirements" class="d-flex align-items-center">
                                Requirements 
                                <button type="button" class="btn btn-success btn-sm ml-2 add-requirement"><i class="fas fa-plus"></i></button>
                            </label>
                            <div class="requirements-container">
                                <div class="input-group mb-2 requirement-item">
                                    <input type="text" name="requirements[]" class="form-control" placeholder="Requirement">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-requirement"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
