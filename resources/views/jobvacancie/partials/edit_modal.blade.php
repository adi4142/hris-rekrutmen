<div class="modal fade" id="modal-edit-{{ $editJobVacancie->vacancies_id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Job Vacancie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('jobvacancie.update', $editJobVacancie->vacancies_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="departement_id">Departement</label>
                            <select name="departement_id" class="form-control" required>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->departement_id }}" {{ $editJobVacancie->departement_id == $departement->departement_id ? 'selected' : '' }}>{{ $departement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="position_id">Position</label>
                            <select name="position_id" class="form-control" required>
                                @foreach($positions as $position)
                                    <option value="{{ $position->position_id }}" {{ $editJobVacancie->position_id == $position->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control">{{ $editJobVacancie->description }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="expired_at">Tanggal Kadaluarsa</label>
                            <input type="date" name="expired_at" value="{{ old('expired_at', $editJobVacancie->expired_at ? date('Y-m-d', strtotime($editJobVacancie->expired_at)) : '') }}" class="form-control" required>
                        </div>

                        @php
                            $requiredDocs = json_decode($editJobVacancie->required_documents) ?? [];
                        @endphp
                        <div class="col-md-12 form-group">
                            <label>Dokumen Yang Diperlukan</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_cv_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="CV" {{ in_array('CV', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_cv_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">CV</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ktp_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="KTP" {{ in_array('KTP', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_ktp_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">KTP</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_ijazah_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="Ijazah" {{ in_array('Ijazah', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_ijazah_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">Ijazah</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_transkip_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="Transkip Nilai" {{ in_array('Transkip Nilai', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_transkip_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">Transkip Nilai</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_skck_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="SKCK" {{ in_array('SKCK', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_skck_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">SKCK</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_health_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="Surat Keterangan Sehat" {{ in_array('Surat Keterangan Sehat', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_health_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">Surat Keterangan Sehat</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_portfolio_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="Portfolio" {{ in_array('Portfolio', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_portfolio_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">Portfolio</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="doc_photo_{{ $editJobVacancie->vacancies_id }}" name="required_documents[]" value="Pas Foto" {{ in_array('Pas Foto', $requiredDocs) ? 'checked' : '' }}>
                                        <label for="doc_photo_{{ $editJobVacancie->vacancies_id }}" class="custom-control-label">Pas Foto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="requirements_{{ $editJobVacancie->vacancies_id }}" class="d-flex align-items-center">
                                Requirements 
                                <button type="button" class="btn btn-success btn-sm ml-2 add-requirement"><i class="fas fa-plus"></i></button>
                            </label>
                            <div class="requirements-container">
                                @php
                                    $requirements = json_decode($editJobVacancie->requirements, true);
                                    if (!is_array($requirements)) {
                                        $requirements = $editJobVacancie->requirements ? [$editJobVacancie->requirements] : [];
                                    }
                                    $requirements = array_filter($requirements);
                                @endphp
                                @if(count($requirements) > 0)
                                    @foreach($requirements as $index => $req)
                                    <div class="input-group mb-2 requirement-item">
                                        <input type="text" name="requirements[]" class="form-control" placeholder="Requirement" value="{{ $req }}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-requirement"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 requirement-item">
                                        <input type="text" name="requirements[]" class="form-control" placeholder="Requirement">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-requirement"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
