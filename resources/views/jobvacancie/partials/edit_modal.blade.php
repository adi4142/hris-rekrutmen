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
                            <input type="date" name="expired_at" value="{{ old('expired_at', $editJobVacancie->expired_at ? date('Y-m-d', strtotime($editJobVacancie->expired_at)) : '') }}" min="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="job_type_{{ $editJobVacancie->vacancies_id }}">Tipe Pekerjaan</label>
                            <select name="job_type" id="job_type_{{ $editJobVacancie->vacancies_id }}" class="form-control" required>
                                <option value="full time" {{ $editJobVacancie->job_type == 'full time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part time" {{ $editJobVacancie->job_type == 'part time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ $editJobVacancie->job_type == 'contract' ? 'selected' : '' }}>Contract</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="quota_{{ $editJobVacancie->vacancies_id }}">Quota Pelamar Diterima</label>
                            <input type="number" name="quota" id="quota_{{ $editJobVacancie->vacancies_id }}" class="form-control" value="{{ $editJobVacancie->quota ?? 1 }}" min="1" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="salary_type_{{ $editJobVacancie->vacancies_id }}">Tipe Gaji</label>
                            <select name="salary_type" id="salary_type_{{ $editJobVacancie->vacancies_id }}" class="form-control" required>
                                <option value="daily" {{ $editJobVacancie->salary_type == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ $editJobVacancie->salary_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ $editJobVacancie->salary_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="negotiate" {{ $editJobVacancie->salary_type == 'negotiate' ? 'selected' : '' }}>Negotiate</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group" id="nominal_gaji_container_{{ $editJobVacancie->vacancies_id }}" {!! $editJobVacancie->salary_type == 'negotiate' ? 'style="display:none;"' : '' !!}>
                            <label for="salary_nominal_{{ $editJobVacancie->vacancies_id }}">Nominal Gaji</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="salary_nominal" id="salary_nominal_{{ $editJobVacancie->vacancies_id }}" value="{{ $editJobVacancie->salary_nominal ? number_format($editJobVacancie->salary_nominal, 0, ',', '.') : '' }}" class="form-control" placeholder="Masukkan nominal gaji">
                            </div>
                        </div>

                        <script>
                            function formatRupiah{{ $editJobVacancie->vacancies_id }}(angka, prefix) {
                                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                                    split = number_string.split(','),
                                    sisa = split[0].length % 3,
                                    rupiah = split[0].substr(0, sisa),
                                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                                if (ribuan) {
                                    separator = sisa ? '.' : '';
                                    rupiah += separator + ribuan.join('.');
                                }

                                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
                            }

                            document.getElementById('salary_type_{{ $editJobVacancie->vacancies_id }}').addEventListener('change', function() {
                                const nominalContainer = document.getElementById('nominal_gaji_container_{{ $editJobVacancie->vacancies_id }}');
                                const nominalInput = document.getElementById('salary_nominal_{{ $editJobVacancie->vacancies_id }}');
                                if (this.value === 'negotiate') {
                                    nominalContainer.style.display = 'none';
                                    nominalInput.value = '';
                                    nominalInput.required = false;
                                } else {
                                    nominalContainer.style.display = 'block';
                                    nominalInput.required = true;
                                }
                            });

                            const salaryInput_{{ $editJobVacancie->vacancies_id }} = document.getElementById('salary_nominal_{{ $editJobVacancie->vacancies_id }}');
                            salaryInput_{{ $editJobVacancie->vacancies_id }}.addEventListener('keyup', function(e) {
                                this.value = formatRupiah{{ $editJobVacancie->vacancies_id }}(this.value);
                            });

                            salaryInput_{{ $editJobVacancie->vacancies_id }}.closest('form').addEventListener('submit', function() {
                                const val = salaryInput_{{ $editJobVacancie->vacancies_id }}.value.replace(/\./g, '');
                                salaryInput_{{ $editJobVacancie->vacancies_id }}.value = val;
                            });
                        </script>

                        @php
                            $requiredDocs = $editJobVacancie->required_documents;
                            if (is_string($requiredDocs)) {
                                $requiredDocs = json_decode($requiredDocs, true) ?: [];
                            }
                            $requiredDocs = is_array($requiredDocs) ? $requiredDocs : [];
                        @endphp
                        <div class="col-md-12 form-group">
                            <label>Dokumen Yang Diperlukan</label>
                            <div class="row">
                                <div class="col-md-3">
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
                            <label class="d-flex align-items-center">
                                Assign HR (Penanggung Jawab)
                                <button type="button" class="btn btn-success btn-sm ml-2 add-hr"><i class="fas fa-plus"></i> Tambah HR</button>
                            </label>
                            <div class="hr-container">
                                @php
                                    $assignedHrs = $editJobVacancie->hrs;
                                @endphp
                                @if(count($assignedHrs) > 0)
                                    @foreach($assignedHrs as $assignedHr)
                                    <div class="input-group mb-2 hr-item">
                                        <select name="hr_ids[]" class="form-control">
                                            <option value="">Pilih HR...</option>
                                            @foreach($hrUsers as $hr)
                                                <option value="{{ $hr->user_id }}" {{ $assignedHr->user_id == $hr->user_id ? 'selected' : '' }}>{{ $hr->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-hr"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 hr-item">
                                        <select name="hr_ids[]" class="form-control">
                                            <option value="">Pilih HR...</option>
                                            @foreach($hrUsers as $hr)
                                                <option value="{{ $hr->user_id }}">{{ $hr->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-hr"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <small class="text-muted">HR yang dipilih dapat melihat dan mengelola kandidat untuk lowongan ini.</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="requirements_{{ $editJobVacancie->vacancies_id }}" class="d-flex align-items-center">
                                Requirements 
                                <button type="button" class="btn btn-success btn-sm ml-2 add-requirement"><i class="fas fa-plus"></i></button>
                            </label>
                            <div class="requirements-container">
                                @php
                                    $requirements = $editJobVacancie->requirements;
                                    if (is_string($requirements)) {
                                        $requirements = json_decode($requirements, true) ?: [];
                                    }
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
