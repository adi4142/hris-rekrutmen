$(document).ready(function() {
    // Check all logic
    $(document).on('change', '#checkAll', function() {
        $('.app-checkbox').prop('checked', $(this).prop('checked'));
    });

    $(document).on('change', '.app-checkbox', function() {
        if(!$(this).prop('checked')) {
            $('#checkAll').prop('checked', false);
        }
    });

    // AJAX Score Submission
    $('#btnSaveScore').on('click', function() { submitScore('save_only', this); });
    $('#btnPassToOffering').on('click', function() { 
        if(confirm('Luluskan pelamar ini ke tahap Offering?')) {
            submitScore('pass', this); 
        }
    });
    $('#btnFailApplicant').on('click', function() { 
        if(confirm('Apakah Anda yakin ingin menggagalkan pelamar ini?')) {
            submitScore('fail', this); 
        }
    });

    function submitScore(action, btn) {
        const $btn = $(btn);
        const $form = $('#scoreForm');
        $('#modal-action').val(action);
        const data = $form.serialize();
        
        $btn.prop('disabled', true).prepend('<i class="fas fa-spinner fa-spin mr-1"></i> ');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: data,
            success: function(response) {
                $('#scoreModal').modal('hide');
                toastr.success(response.message || 'Penilaian berhasil disimpan');
                localRefresh();
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan nilai.';
                alert(msg);
            },
            complete: function() {
                $btn.prop('disabled', false).find('.fa-spinner').remove();
            }
        });
    }

    // AJAX Offering Submission
    $('#btnSubmitOffering').on('click', function() {
        const $btn = $(this);
        const $form = $('#offeringForm');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function() {
                $('#offeringModal').modal('hide');
                toastr.success('Offering berhasil dikirim');
                localRefresh();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Gagal mengirim offering.');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Kirim Penawaran (Offering)');
            }
        });
    });

    // AJAX Negotiation Submission
    $('#btnSubmitNegotiation').on('click', function() {
        const $btn = $(this);
        const $form = $('#negotiationForm');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function() {
                $('#negotiationModal').modal('hide');
                toastr.success('Keputusan berhasil disimpan');
                localRefresh();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Gagal menyimpan keputusan.');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Simpan Keputusan');
            }
        });
    });

    // Add Toastr if not present or just use simple alert for now
    window.toastr = window.toastr || { 
        success: (m) => console.log('Success: ' + m),
        error: (m) => alert('Error: ' + m)
    };

    // Pagination AJAX
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            history.pushState(null, '', url);
            localRefresh(url);
        }
    });
});

// Function to refresh content without full page reload
function submitAjaxPhase(formId, action, confirmMsg) {
    if (!confirm(confirmMsg)) return;
    
    const $form = $(formId);
    const url = $form.attr('action');
    
    // Add action to the request
    const formData = $form.serializeArray();
    formData.push({ name: 'action', value: action });
    
    $.ajax({
        url: url,
        method: 'POST',
        data: $.param(formData),
        success: function(response) {
            toastr.success('Data berhasil diperbarui');
            localRefresh();
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
            alert(msg);
        }
    });
}

function localRefresh(url = window.location.href) {
    // Save current scroll position
    const scrollPos = $(window).scrollTop();
    
    $.get(url, function(html) {
        const $content = $(html).find('[data-card-id="manage-applications-card"]');
        if($content.length > 0) {
            $('[data-card-id="manage-applications-card"]').html($content.html());
        } else {
             // Fallback to full reload if partial not found
             window.location.reload();
             return;
        }
        
        // Reset checkAll
        $('#checkAll').prop('checked', false);
        
        // Restore scroll position
        setTimeout(function() {
            $(window).scrollTop(scrollPos);
        }, 50);
    });
}

function submitBulkSelection(url, confirmMsg = null) {
    const ids = [];
    $('.app-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    
    if (ids.length === 0) {
        alert('Pilih setidaknya satu pelamar.');
        return;
    }

    if (confirmMsg && !confirm(confirmMsg)) {
        return;
    }

    // Specific logic for offering
    if (url.includes('promote-to-offering')) {
        const container = document.getElementById('offering-ids-container');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'application_ids[]';
            input.value = id;
            container.appendChild(input);
        });
        $('#offeringModal').modal('show');
        return;
    }

    const form = document.getElementById('bulkSelectionForm');
    form.action = url;
    
    // Clear existing hidden inputs for application_ids
    $(form).find('input[name="application_ids[]"]').remove();
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'application_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    // If assigning batch, check if batch is selected
    if (url.includes('assign-batch')) {
        if (!$('#batchSelect').val()) {
            alert('Pilih batch terlebih dahulu.');
            return;
        }
    }

    // AJAX Submission
    const data = $(form).serialize();
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function(response) {
            toastr.success('Operasi berhasil');
            localRefresh();
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal memproses data.';
            alert(msg);
        }
    });
}

function openOfferingDetail(appId, jobDesc, salary, hours, quota, startDate) {
    const container = document.getElementById('offering-ids-container');
    container.innerHTML = `<input type="hidden" name="application_ids[]" value="${appId}">`;
    
    const form = document.getElementById('offeringForm');
    $(form).find('textarea[name="offering_job_desc"]').val(jobDesc);
    $(form).find('input[name="offering_salary"]').val(salary);
    $(form).find('input[name="offering_working_hours"]').val(hours);
    $(form).find('input[name="offering_leave_quota"]').val(quota);
    $(form).find('input[name="offering_start_date"]').val(startDate);
    
    $('#offeringModal').modal('show');
}

function openNegotiationModal(appId, appName, oldSalary, expSalary, reason) {
    $('#neg-app-id').val(appId);
    $('#neg-app-name').text(appName);
    $('#neg-old-salary').text('Rp ' + parseInt(oldSalary).toLocaleString('id-ID'));
    $('#neg-expected-salary').text('Rp ' + parseInt(expSalary).toLocaleString('id-ID'));
    $('#neg-reason').text(reason || 'Tidak ada alasan dilampirkan.');
    
    // Reset counter offer
    $('#neg-action').val('accept');
    $('#counter-offer-group').hide();
    
    $('#negotiationModal').modal('show');
}

function toggleCounterOffer(val) {
    if (val === 'counter') {
        $('#counter-offer-group').show();
        $('#counter-offer-group input').prop('required', true);
    } else {
        $('#counter-offer-group').hide();
        $('#counter-offer-group input').prop('required', false);
    }
}

function openScoreModal(appId, appName, selId, selName, bsId, score, notes, status, aspectsB64, isFinished) {
    $('#modal-app-id').val(appId);
    $('#modal-app-name').text(appName);
    $('#modal-sel-id').val(selId);
    $('#modal-stage-name').text(selName);
    $('#modal-bs-id').val(bsId);
    $('#modal-score').val(score);
    $('#modal-notes').val(notes);
    
    // Reset action
    $('#modal-action').val('save_only');

    // Toggle buttons based on progress
    if (isFinished) {
        $('#btnPassToOffering').show();
        $('#btnSaveScore').removeClass('btn-primary').addClass('btn-outline-primary');
    } else {
        $('#btnPassToOffering').hide();
        $('#btnSaveScore').removeClass('btn-outline-primary').addClass('btn-primary');
    }

    // Render dynamic aspects
    let aspectsContainer = $('#aspects-container');
    let fallbackScoreContainer = $('#fallback-score-container');
    let modalScoreInput = $('#modal-score');
    
    aspectsContainer.empty();
    modalScoreInput.prop('required', false);
    
    try {
        let aspects = JSON.parse(atob(aspectsB64));
        if (aspects && aspects.length > 0) {
            // Render stars for each aspect
            let html = '<label class="font-weight-bold">Aspek Penilaian (Rating 1-5):</label><div class="row">';
            aspects.forEach(function(aspect) {
                let currentRate = aspect.score || 0;
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-normal small mb-1 d-block">${aspect.name}</label>
                        <div class="star-rating" data-aspect-id="${aspect.id}">
                            <input type="hidden" name="aspects[${aspect.id}]" value="${currentRate}" required>
                            <i class="fas fa-star ${currentRate >= 1 ? 'checked' : ''}" data-value="1" title="Kurang Baik"></i>
                            <i class="fas fa-star ${currentRate >= 2 ? 'checked' : ''}" data-value="2" title="Kurang"></i>
                            <i class="fas fa-star ${currentRate >= 3 ? 'checked' : ''}" data-value="3" title="Cukup"></i>
                            <i class="fas fa-star ${currentRate >= 4 ? 'checked' : ''}" data-value="4" title="Baik"></i>
                            <i class="fas fa-star ${currentRate >= 5 ? 'checked' : ''}" data-value="5" title="Sangat Baik"></i>
                            <span class="rating-label ml-2 small text-muted font-italic">${getRatingLabel(currentRate)}</span>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            aspectsContainer.html(html);
            
            // Hide fallback score, remove required
            fallbackScoreContainer.hide();
            modalScoreInput.val(score); // keep original score around
        } else {
            // Show fallback single score
            fallbackScoreContainer.show();
            modalScoreInput.prop('required', true).val(score);
        }
    } catch(e) {
        console.error('Failed parsing aspects: ', e);
        fallbackScoreContainer.show();
        modalScoreInput.prop('required', true).val(score);
    }
    
    $('#scoreModal').modal('show');
}

function fetchProfile(id) {
    // Reset and show loading
    $('#p-name, #p-email, #p-phone, #p-address, #p-dob, #p-gender').text('...');
    $('#modalProfileDetail').modal('show');

    $.ajax({
        url: `/jobapplicant/${id}/profile-ajax`,
        method: 'GET',
        success: function(data) {
            $('#p-name').text(data.name);
            $('#p-email').text(data.email);
            $('#p-phone').text(data.phone);
            $('#p-address').text(data.address);
            $('#p-dob').text(data.date_of_birth);
            $('#p-gender').text(data.gender);
        },
        error: function() {
            alert('Gagal mengambil data profil.');
            $('#modalProfileDetail').modal('hide');
        }
    });
}

// Modal Documents Application Specific
$(document).on('click', '.btn-view-app-docs', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    
    $('#applicantName').text(name);
    $('#docsList').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin text-info"></i> Memuat...</div>');
    $('#previewArea').html('<div class="text-center p-5"><i class="fas fa-file-signature fa-4x mb-3 text-muted"></i><h5>Pilih berkas dari daftar</h5></div>');
    $('#modalDocs').modal('show');

    $.ajax({
        url: `/jobapplication/documents/${id}`,
        method: 'GET',
        success: function(docs) {
            let docsHtml = '';
            let hasDocs = false;
            
            docs.forEach(doc => {
                if (doc.url) {
                    hasDocs = true;
                    docsHtml += `
                        <button type="button" class="list-group-item list-group-item-action border-left-0 border-right-0 py-3 btn-preview-file" 
                            data-url="${doc.url}" data-title="${doc.name || doc.title}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas ${doc.icon || 'fa-file-alt'} fa-lg text-info mr-3"></i>
                                    <span class="font-weight-bold">${doc.name || doc.title}</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </div>
                        </button>
                    `;
                }
            });

            if (!hasDocs) {
                docsHtml = '<div class="p-4 text-center text-muted small">Tidak ada dokumen.</div>';
            }
            
            $('#docsList').html(docsHtml);
        },
        error: function() {
            $('#docsList').html('<div class="p-4 text-center text-danger small">Gagal memuat.</div>');
        }
    });
});

// Handle File Preview Click (Reuse the logic)
$(document).on('click', '.btn-preview-file', function() {
    const url = $(this).data('url');
    const title = $(this).data('title');
    $('.btn-preview-file').removeClass('active bg-info text-white');
    $(this).addClass('active bg-info text-white');
    
    $('#previewArea').html('<div class="text-center text-white"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br>Membuka file...</div>');
    
    let ext = url.split('.').pop().toLowerCase();
    let previewHtml = '';

    if (ext === 'pdf') {
        previewHtml = `<iframe src="${url}#toolbar=0" width="100%" height="100%" style="border: none;"></iframe>`;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
        previewHtml = `<img src="${url}" class="img-fluid" style="max-height: 100%; object-fit: contain;">`;
    } else {
        previewHtml = `
            <div class="text-center p-5 text-white">
                <i class="fas fa-file-download fa-4x mb-3 text-warning"></i>
                <h5>Format tidak didukung untuk preview</h5>
                <p>Format: .${ext}</p>
                <a href="${url}" download class="btn btn-warning mt-2">
                    <i class="fas fa-download mr-1"></i> Download File
                </a>
            </div>`;
    }
    
    setTimeout(() => {
        $('#previewArea').html(previewHtml);
    }, 300);
});

// Rating Label Helper
function getRatingLabel(val) {
    const labels = {
        1: 'Kurang Baik',
        2: 'Kurang',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };
    return labels[val] || '';
}

// Star Click event handler
$(document).on('click', '.star-rating i', function() {
    const value = $(this).data('value');
    const parent = $(this).parent();
    
    // Update hidden input
    parent.find('input').val(value);
    
    // Update visuals
    parent.find('i').removeClass('checked');
    parent.find('i').each(function() {
        if ($(this).data('value') <= value) {
            $(this).addClass('checked');
        }
    });
    
    // Update label
    parent.find('.rating-label').text(getRatingLabel(value));
});

function bulkApproveOffering(token, url) {
    const ids = [];
    $('.app-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    
    if (ids.length === 0) {
        alert('Pilih setidaknya satu pelamar (Draft Offering).');
        return;
    }

    if (!confirm('Apakah Anda yakin ingin menyetujui dan mengirim Offering Letter PDF ke pelamar terpilih?')) {
        return;
    }

    const $btn = $(event.currentTarget);
    $btn.prop('disabled', true).prepend('<i class="fas fa-spinner fa-spin mr-1"></i> ');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: token,
            application_ids: ids
        },
        success: function(response) {
            toastr.success('Offering Letter berhasil disetujui dan dikirim');
            localRefresh();
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal menyetujui offering.';
            alert(msg);
        },
        complete: function() {
            $btn.prop('disabled', false).find('.fa-spinner').remove();
        }
    });
}
