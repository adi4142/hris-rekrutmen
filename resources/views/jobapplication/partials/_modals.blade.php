<!-- Modal Dokumen dengan Preview -->
<div class="modal fade" id="modalDocs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-alt mr-2"></i> Dokumen Lamaran: <span id="applicantName"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <!-- Sidebar List Berkas -->
                    <div class="col-md-4 bg-light p-3 border-right" style="max-height: 80vh; overflow-y: auto;">
                        <h6 class="text-muted font-weight-bold mb-3">DAFTAR BERKAS</h6>
                        <div id="docsList" class="list-group list-group-flush shadow-sm rounded">
                            <!-- Dokumen akan dimuat di sini -->
                        </div>
                    </div>
                    <!-- Preview Area -->
                    <div class="col-md-8 bg-dark d-flex flex-column" style="height: 80vh;">
                        <div id="previewArea" class="flex-grow-1 d-flex align-items-center justify-content-center text-white" style="position: relative; overflow: hidden; height: 100%;">
                            <div class="text-center p-5">
                                <i class="fas fa-file-signature fa-4x mb-3 text-muted"></i>
                                <h5>Pilih berkas untuk melihat preview</h5>
                                <p class="text-muted small">Mendukung Format PDF dan Gambar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INPUT NILAI --}}
<div class="modal fade" id="scoreModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
            <form action="{{ route('jobapplication.inputScore') }}" method="POST" id="scoreForm">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-edit mr-2"></i> Input Nilai <span id="modal-stage-name"></span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border mb-3">
                        <small class="text-muted d-block">PELAMAR:</small>
                        <h5 class="mb-0" id="modal-app-name"></h5>
                    </div>
                    
                    <input type="hidden" name="application_id" id="modal-app-id">
                    <input type="hidden" name="selection_id" id="modal-sel-id">
                    <input type="hidden" name="batch_stage_id" id="modal-bs-id">

                    <div id="aspects-container" class="mb-3">
                        <!-- Disini diisi field aspek secara dinamis JS -->
                    </div>

                    <div class="form-group" id="fallback-score-container" style="display: none;">
                        <label class="font-weight-bold">Skor Keseluruhan (0-100)</label>
                        <input type="number" name="score" id="modal-score" class="form-control form-control-lg" step="0.1">
                        <small class="text-muted text-info"><i class="fas fa-info-circle"></i> Nilai akhir dirata-rata otomatis jika terdapat Aspek Penilaian</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-muted">Catatan / Feedback</label>
                        <textarea name="notes" id="modal-notes" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <input type="hidden" name="action" id="modal-action" value="save_only">
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Tutup</button>
                    <div class="btn-group">
                        <button type="button" id="btnSaveScore" class="btn btn-primary font-weight-bold shadow-sm px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Saja
                        </button>
                        <button type="button" id="btnPassToOffering" class="btn btn-success font-weight-bold shadow-sm px-4" style="display: none;">
                            <i class="fas fa-paper-plane mr-1"></i> Lulus ke Offering
                        </button>
                        <button type="button" id="btnFailApplicant" class="btn btn-danger font-weight-bold shadow-sm px-4">
                            <i class="fas fa-times-circle mr-1"></i> Gagalkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL OFFERING DETAILS --}}
<div class="modal fade" id="offeringModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <form action="{{ route('jobapplication.promoteToOffering') }}" method="POST" id="offeringForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-handshake mr-2"></i> Detail Penawaran Kerja (Offering)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> Isi detail penawaran untuk pelamar terpilih. Email penawaran akan dikirimkan secara otomatis setelah disimpan.
                    </div>
                    
                    <div id="offering-ids-container"></div>

                    <div class="form-group">
                        <label class="font-weight-bold">Job Description Utama</label>
                        <textarea name="offering_job_desc" class="form-control" rows="5" required placeholder="Masukkan tugas dan tanggung jawab utama...">{{ $selectedVacancy->description ?? '' }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Nominal Gaji (Sesuai Kesepakatan)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                </div>
                                <input type="number" name="offering_salary" class="form-control font-weight-bold text-success" style="font-size: 1.1rem" value="{{ $selectedVacancy->salary_nominal ?? '' }}" required>
                            </div>
                            <small class="text-muted">Masukkan angka saja, tanpa titik atau koma.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jam Kerja</label>
                            <input type="text" name="offering_working_hours" class="form-control" value="Senin - Jumat, 08:00 - 17:00" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tanggal Mulai Kerja</label>
                            <input type="date" name="offering_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Jatah Cuti</label>
                            <input type="text" name="offering_leave_quota" class="form-control" value="12 hari per tahun" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="btnSubmitOffering" class="btn btn-success font-weight-bold shadow-sm">Simpan Draft Penawaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL NEGOTIATION RESPONSE --}}
<div class="modal fade" id="negotiationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
            <form action="{{ route('jobapplication.respondNegotiation') }}" method="POST" id="negotiationForm">
                @csrf
                <div class="modal-header bg-orange text-white" style="background-color: #fd7e14">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-comments mr-2"></i> Review Permintaan Negosiasi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="application_id" id="neg-app-id">
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="small text-muted mb-0">Pelamar</label>
                            <div id="neg-app-name" class="font-weight-bold"></div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-0">Gaji Awal Ditawarkan</label>
                            <div id="neg-old-salary" class="font-weight-bold text-muted"></div>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mb-3 border-left border-primary" style="background-color: #f8faff !important;">
                        <label class="small text-uppercase text-muted font-weight-bold mb-1 d-block">Ekspektasi Gaji Pelamar:</label>
                        <h3 id="neg-expected-salary" class="text-primary font-weight-bold mb-2"></h3>
                        <label class="small text-uppercase text-muted font-weight-bold mb-0 d-block">Alasan Negosiasi:</label>
                        <p id="neg-reason" class="text-dark mb-0 font-italic"></p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keputusan HR</label>
                        <select name="action" class="form-control" id="neg-action" onchange="toggleCounterOffer(this.value)">
                            <option value="accept">Terima Negosiasi (Gunakan Gaji Ekspektasi)</option>
                            <option value="counter">Counter Offer (Tawarkan Gaji Lain)</option>
                            <option value="reject">Tolak Negosiasi</option>
                        </select>
                    </div>

                    <div class="form-group" id="counter-offer-group" style="display: none;">
                        <label class="font-weight-bold text-success">Nominal Counter Offer (Gaji Baru)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success text-white font-weight-bold">Rp</span>
                            </div>
                            <input type="number" name="counter_salary" class="form-control font-weight-bold" placeholder="Contoh: 8500000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan HR (Opsional)</label>
                        <textarea name="hr_note" class="form-control" rows="2" placeholder="Berikan alasan atau pesan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="btnSubmitNegotiation" class="btn btn-orange text-white px-4 shadow-sm" style="background-color: #fd7e14">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PROFIL --}}
<div class="modal fade" id="modalProfileDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-circle mr-2"></i> Detail Profil Pelamar</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-8 p-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3 font-weight-bold">Informasi Personal</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Nama Lengkap</label>
                                <div id="p-name" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Email</label>
                                <div id="p-email" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Nomor Telepon</label>
                                <div id="p-phone" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Jenis Kelamin</label>
                                <div id="p-gender" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="small text-muted mb-1 d-block">Tanggal Lahir</label>
                                <div id="p-dob" class="font-weight-bold"></div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label class="small text-muted mb-1 d-block">Alamat Lengkap</label>
                                <div id="p-address" class="font-weight-bold"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
