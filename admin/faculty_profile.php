<?php
$pageTitle = "Faculty Profile View";
$activeNav = "faculty_profile";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Faculty Profile Directory</h2>
        <p class="page-header-subtitle">View detailed faculty profiles, qualifications, and allocated subjects list</p>
    </div>
    <a href="faculty.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Faculty Directory
    </a>
</div>

<!-- Select Faculty Dropdown Header Card -->
<div class="data-card mb-4">
    <div class="row align-items-center g-3">
        <div class="col-12 col-md-6">
            <label for="selectFacultyProfile" class="form-label mb-1 fw-bold text-white">Select Faculty Member</label>
            <select class="form-select form-select-dark" id="selectFacultyProfile">
                <option value="">Loading faculty members...</option>
            </select>
        </div>
    </div>
</div>

<!-- Profile Details Container (Loaded via AJAX) -->
<div id="facultyProfileContainer">
    <div class="text-center text-muted py-5">
        <div class="spinner-border text-primary me-2" role="status"></div>
        <span>Loading faculty profile details...</span>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Load Faculty options
    function loadFacultyList() {
        $.ajax({
            url: 'actions/faculty_actions.php?action=list',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && res.data.length > 0) {
                    let html = '';
                    res.data.forEach(function(f, idx) {
                        html += `<option value="${f.faculty_id}">${f.full_name} (${f.employee_id}) - ${f.department_name}</option>`;
                    });
                    $('#selectFacultyProfile').html(html);
                    
                    // Load first faculty by default
                    loadProfile(res.data[0].faculty_id);
                } else {
                    $('#facultyProfileContainer').html('<div class="alert alert-warning text-center">No faculty members found in database.</div>');
                }
            }
        });
    }

    function loadProfile(facultyId) {
        $.ajax({
            url: 'actions/faculty_actions.php',
            type: 'GET',
            data: { action: 'get_profile', id: facultyId },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const f = res.data.faculty;
                    const allocs = res.data.allocations;
                    const stats = res.data.stats;
                    const initials = f.full_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    let allocRows = '';
                    if (allocs.length > 0) {
                        allocs.forEach((a, idx) => {
                            allocRows += `<tr>
                                <td>${idx + 1}</td>
                                <td class="fw-semibold text-white">${a.subject_name} (${a.subject_code})</td>
                                <td>${a.course_code} - Sem ${a.semester_number}</td>
                                <td><span class="badge bg-secondary opacity-75">Div ${a.division_name}</span></td>
                                <td>${a.session_name}</td>
                                <td><span class="badge bg-primary opacity-75">${a.year_label}</span></td>
                            </tr>`;
                        });
                    } else {
                        allocRows = `<tr><td colspan="6" class="text-center text-muted py-4">No subjects allocated to this faculty member yet.</td></tr>`;
                    }

                    const profileHtml = `
                        <!-- Header Profile Card -->
                        <div class="profile-header-banner">
                            <div class="profile-avatar-placeholder">${initials}</div>
                            <div>
                                <h3 class="text-white mb-1 fw-bold">${f.full_name}</h3>
                                <div class="text-primary fw-semibold fs-6 mb-2">${f.designation} - ${f.department_name}</div>
                                <div class="d-flex flex-wrap gap-3 text-secondary small">
                                    <span><i class="fa-solid fa-id-badge text-info me-1"></i>${f.employee_id}</span>
                                    <span><i class="fa-solid fa-envelope text-warning me-1"></i>${f.email}</span>
                                    <span><i class="fa-solid fa-phone text-success me-1"></i>${f.phone}</span>
                                    <span><i class="fa-solid fa-graduation-cap me-1"></i>${f.qualification || 'N/A'}</span>
                                    <span><i class="fa-solid fa-briefcase me-1"></i>${f.experience_years} Years Exp.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded bg-card border border-secondary border-opacity-25 text-center">
                                    <div class="text-secondary small">Assigned Subjects</div>
                                    <div class="fs-3 fw-bold text-white mt-1">${stats.total_subjects}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded bg-card border border-secondary border-opacity-25 text-center">
                                    <div class="text-secondary small">Total Credits</div>
                                    <div class="fs-3 fw-bold text-success mt-1">${stats.total_credits}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded bg-card border border-secondary border-opacity-25 text-center">
                                    <div class="text-secondary small">Total Weekly Hours</div>
                                    <div class="fs-3 fw-bold text-info mt-1">${stats.total_classes} hrs</div>
                                </div>
                            </div>
                        </div>

                        <!-- Table Card -->
                        <div class="data-card">
                            <div class="data-card-header mb-3">
                                <h4 class="data-card-title">
                                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Assigned Subjects Directory
                                </h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Course & Sem</th>
                                            <th>Division</th>
                                            <th>Session</th>
                                            <th>Academic Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${allocRows}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                    $('#facultyProfileContainer').html(profileHtml);
                } else {
                    showAlert('Error', res.message, 'error');
                }
            }
        });
    }

    loadFacultyList();

    $('#selectFacultyProfile').on('change', function() {
        const id = $(this).val();
        if (id) {
            loadProfile(id);
        }
    });
});
</script>
