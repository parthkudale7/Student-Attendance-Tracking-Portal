<?php
$pageTitle = "Academic Year Configuration";
$activeNav = "academic_year";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Academic Year Configuration</h2>
        <p class="page-header-subtitle">Manage academic years and configure current year</p>
    </div>
    <button class="btn-primary-action" id="btnAddAcademicYear" data-bs-toggle="modal" data-bs-target="#ayModal">
        <i class="fa-solid fa-plus"></i> Add Academic Year
    </button>
</div>

<!-- Stat Cards Grid Matching Image 5 -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Academic Years</div>
                <div class="stat-value" id="statTotalAY">6</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Current Academic Year</div>
                <div class="stat-value text-success" id="statCurrentAY" style="font-size: 1.25rem;">2024-25</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-sky">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Upcoming Academic Year</div>
                <div class="stat-value text-info" id="statUpcomingAY" style="font-size: 1.25rem;">2025-26</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Completed Years</div>
                <div class="stat-value" id="statCompletedAY">3</div>
            </div>
        </div>
    </div>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="data-card-header">
        <h3 class="data-card-title">Academic Years List</h3>
    </div>

    <div class="table-responsive">
        <table id="academicYearTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Academic Year</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Current Year</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX Populated -->
            </tbody>
        </table>
    </div>
</div>

<!-- Academic Year Modal -->
<div class="modal fade" id="ayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ayModalLabel"><span>Add Academic Year</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ayForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="academic_year_id" id="academic_year_id">

                    <div class="mb-3">
                        <label for="year_label" class="form-label">Academic Year Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="year_label" name="year_label" placeholder="e.g. 2026-2027" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-dark" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-dark" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select form-select-dark" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_current" name="is_current" value="1">
                        <label class="form-check-label fw-semibold" for="is_current">Set as Current Active Academic Year</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveAY">Save Academic Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    let ayTable = $('#academicYearTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/academic_year_actions.php?action=list',
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const data = json.data;
                    const current = data.find(item => item.is_current == 1);
                    if (current) {
                        $('#statCurrentAY').text(current.year_label);
                    }
                    $('#statTotalAY').text(data.length || 6);
                    return json.data;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) { return meta.row + 1; }
            },
            { 
                data: 'year_label',
                render: function(data) { return `<span class="fw-semibold text-white">${data}</span>`; }
            },
            { 
                data: 'start_date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
                }
            },
            { 
                data: 'end_date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
                }
            },
            { 
                data: 'status',
                render: function(data, type, row) {
                    if (row.is_current == 1) {
                        return '<span class="badge-status badge-status-active">Active</span>';
                    } else if (new Date(row.end_date) < new Date()) {
                        return '<span class="badge-status badge-status-completed">Completed</span>';
                    } else if (new Date(row.start_date) > new Date()) {
                        return '<span class="badge-status badge-status-upcoming">Upcoming</span>';
                    } else {
                        return '<span class="badge-status badge-status-inactive">Inactive</span>';
                    }
                }
            },
            { 
                data: 'is_current',
                render: function(data) {
                    return data == 1 
                        ? '<span class="badge bg-warning text-dark"><i class="fa-solid fa-star me-1"></i>Yes</span>' 
                        : '<span class="text-muted">No</span>';
                }
            },
            { 
                data: null,
                orderable: false,
                className: 'text-end',
                render: function(data, type, row) {
                    const setCurrBtn = row.is_current == 1 
                        ? '' 
                        : `<button class="btn-action-edit btn-set-current me-1" data-id="${row.academic_year_id}" title="Set as Current Year"><i class="fa-solid fa-star text-warning"></i></button>`;

                    return `
                        <div class="d-inline-flex align-items-center">
                            ${setCurrBtn}
                            <button class="btn-action-edit btn-edit me-1" data-id="${row.academic_year_id}" data-bs-toggle="modal" data-bs-target="#ayModal" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn-action-delete btn-delete" data-id="${row.academic_year_id}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    `;
                }
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search academic year..." }
    });

    const ayModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('ayModal'));

    $('#btnAddAcademicYear').on('click', function() {
        $('#ayForm')[0].reset();
        $('#academic_year_id').val('');
        $('#ayForm').removeClass('was-validated');
        $('#ayModalLabel span').text('Add Academic Year');
    });

    $('#ayModal').on('hidden.bs.modal', function() {
        $('#ayForm')[0].reset();
        $('#academic_year_id').val('');
        $('#ayForm').removeClass('was-validated');
        $('#ayModalLabel span').text('Add Academic Year');
    });

    $('#ayForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveAcademicYear')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save';
        $.ajax({
            url: 'actions/academic_year_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    ayModal.hide();
                    showToast(res.message, 'success');
                    ayTable.ajax.reload(null, false);
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving academic year.', 'error');
            }
        });
    });

    $('#academicYearTable').on('click', '.btn-set-current', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        showConfirm('Set Current Year?', 'Do you want to set this as the active Academic Year?').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/academic_year_actions.php',
                    type: 'POST',
                    data: { action: 'set_current', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            ayTable.ajax.reload(null, false);
                        } else {
                            showAlert('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });

    $('#academicYearTable').on('click', '.btn-edit', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        $.ajax({
            url: 'actions/academic_year_actions.php',
            type: 'GET',
            data: { action: 'get', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const data = res.data;
                    $('#academic_year_id').val(data.academic_year_id);
                    $('#year_label').val(data.year_label);
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $('#status').val(data.status);
                    $('#is_current').prop('checked', data.is_current == 1);
                    $('#ayModalLabel span').text('Edit Academic Year');
                }
            }
        });
    });

    $('#academicYearTable').on('click', '.btn-delete', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        showConfirm('Delete Academic Year?', 'This action cannot be undone.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/academic_year_actions.php',
                    type: 'POST',
                    data: { action: 'delete', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            ayTable.ajax.reload(null, false);
                        } else {
                            showAlert('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
