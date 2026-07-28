<?php
$pageKey = 'faculty';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="page-container" id="profileArea">
        <!-- Title & Action Buttons -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1">Faculty Profile & Account Settings</h3>
                <p class="text-muted small mb-0">Manage personal credentials, assigned courses, department roles, and security settings.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button onclick="openEditProfileModal()" class="btn-custom btn-primary-custom">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </button>
                <button onclick="window.print()" class="btn-custom btn-outline-glass">
                    <i class="bi bi-printer-fill"></i> Print Profile
                </button>
            </div>
        </div>

        <!-- Profile Hero Banner Card -->
        <div class="glass-card mb-4 animate-fade-in" style="background: linear-gradient(135deg, rgba(108, 99, 255, 0.12) 0%, rgba(0, 212, 255, 0.08) 100%);">
            <div class="d-flex align-items-center flex-wrap gap-4 p-2">
                <div class="position-relative">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=AlexMorgan" alt="Faculty Avatar" id="profileAvatar" class="rounded-circle border border-2 border-primary shadow" style="width: 110px; height: 110px; background: rgba(255,255,255,0.05); padding: 5px;">
                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle" style="width: 18px; height: 18px;" title="Active Status"></span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-1">
                        <h2 class="fw-bold text-white mb-0" id="profileNameDisplay">Prof. Alex Morgan</h2>
                        <span class="badge bg-primary text-white px-3 py-1 rounded-pill" id="profileRoleBadge">Head Admin / HOD</span>
                        <span class="badge bg-outline-info text-info border border-info px-3 py-1 rounded-pill"><i class="bi bi-patch-check-fill me-1"></i>Verified Faculty</span>
                    </div>
                    <p class="text-info mb-2" style="font-size: 0.95rem;" id="profileDeptDisplay"><i class="bi bi-building me-1"></i> Department of Computer Engineering & AI</p>
                    <div class="d-flex align-items-center gap-4 flex-wrap text-muted small">
                        <span><i class="bi bi-card-heading me-1 text-secondary"></i> Faculty ID: <strong class="text-white" id="profileIdDisplay">EMP-CE-1024</strong></span>
                        <span><i class="bi bi-envelope-fill me-1 text-secondary"></i> Email: <strong class="text-white" id="profileEmailDisplay">alex.morgan@university.edu</strong></span>
                        <span><i class="bi bi-telephone-fill me-1 text-secondary"></i> Phone: <strong class="text-white" id="profilePhoneDisplay">+1 (555) 234-5678</strong></span>
                        <span><i class="bi bi-geo-alt-fill me-1 text-secondary"></i> Office: <strong class="text-white" id="profileOfficeDisplay">Building A, Room 304</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="glass-card stat-card animate-fade-in">
                    <div class="stat-icon icon-purple"><i class="bi bi-journal-bookmark-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Assigned Courses</span>
                        <h3 class="stats-value text-white">4 Active</h3>
                        <span class="stat-subtext text-success"><i class="bi bi-check-circle me-1"></i>120 Total Students</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="glass-card stat-card animate-fade-in">
                    <div class="stat-icon icon-cyan"><i class="bi bi-easel-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Classes Conducted</span>
                        <h3 class="stats-value text-cyan">148 Sessions</h3>
                        <span class="stat-subtext text-info"><i class="bi bi-clock-history me-1"></i>This Semester</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="glass-card stat-card animate-fade-in">
                    <div class="stat-icon icon-green"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Avg Class Attendance</span>
                        <h3 class="stats-value text-success">86.4%</h3>
                        <span class="stat-subtext text-success"><i class="bi bi-arrow-up-right me-1"></i>+2.1% vs Last Month</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="glass-card stat-card animate-fade-in">
                    <div class="stat-icon icon-orange"><i class="bi bi-bell-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Defaulter Notices</span>
                        <h3 class="stats-value text-warning">12 Sent</h3>
                        <span class="stat-subtext text-muted"><i class="bi bi-envelope-paper me-1"></i>Automated SMS/Email</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Left Column: Details & Qualifications -->
            <div class="col-lg-5">
                <!-- Academic Profile Details -->
                <div class="glass-card black-card-theme mb-4">
                    <div class="card-header-flex mb-3">
                        <div class="card-title text-white"><i class="bi bi-person-vcard-fill me-2 text-primary"></i> Academic Details</div>
                        <span class="badge bg-secondary text-white">Full-Time</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless text-white mb-0 align-middle" style="font-size: 0.9rem;">
                            <tbody>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                                    <td class="text-muted ps-0" style="width: 40%;"><i class="bi bi-mortarboard-fill me-2 text-info"></i> Highest Degree</td>
                                    <td class="fw-semibold text-white">Ph.D. in Computer Science</td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                                    <td class="text-muted ps-0"><i class="bi bi-award-fill me-2 text-warning"></i> Specialization</td>
                                    <td class="fw-semibold text-white">Data Structures & Machine Learning</td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                                    <td class="text-muted ps-0"><i class="bi bi-briefcase-fill me-2 text-success"></i> Total Experience</td>
                                    <td class="fw-semibold text-white">12 Years (8 Yrs Academic)</td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                                    <td class="text-muted ps-0"><i class="bi bi-calendar-check-fill me-2 text-primary"></i> Joining Date</td>
                                    <td class="fw-semibold text-white">August 14, 2016</td>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                                    <td class="text-muted ps-0"><i class="bi bi-building-check me-2 text-cyan"></i> Faculty Code</td>
                                    <td class="fw-semibold text-cyan">FC-CE-2016-09</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0"><i class="bi bi-diagram-3-fill me-2 text-purple"></i> Direct Supervisor</td>
                                    <td class="fw-semibold text-white">Dean of Academics</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Security & Account Credentials Card -->
                <div class="glass-card black-card-theme">
                    <div class="card-header-flex mb-3">
                        <div class="card-title text-white"><i class="bi bi-shield-lock-fill me-2 text-warning"></i> Security & Credentials</div>
                        <button onclick="togglePasswordForm()" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-key-fill me-1"></i> Change Password
                        </button>
                    </div>
                    
                    <form id="passwordForm" onsubmit="handlePasswordChange(event)" class="mt-3 p-3 rounded-3" style="display: none; background: #050811; border: 1px solid #1E293B;">
                        <div class="mb-3">
                            <label class="form-label text-light small fw-medium">Current Password</label>
                            <input type="password" class="form-control-dark text-white" id="currentPass" required placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light small fw-medium">New Password</label>
                            <input type="password" class="form-control-dark text-white" id="newPass" required placeholder="Min 8 chars, 1 symbol">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light small fw-medium">Confirm New Password</label>
                            <input type="password" class="form-control-dark text-white" id="confirmPass" required placeholder="Repeat new password">
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" onclick="togglePasswordForm()" class="btn btn-sm btn-outline-glass">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-warning">Update Password</button>
                        </div>
                    </form>

                    <div id="securitySummary" class="small">
                        <div class="d-flex align-items-center justify-content-between py-2.5" style="border-bottom: 1px solid #1E293B;">
                            <span style="color: #CBD5E1;"><i class="bi bi-check2-circle text-success me-2 fs-6"></i> Two-Factor Auth (2FA)</span>
                            <span class="badge bg-success px-2 py-1">Enabled</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2.5" style="border-bottom: 1px solid #1E293B;">
                            <span style="color: #CBD5E1;"><i class="bi bi-laptop me-2 fs-6 text-cyan"></i> Last Login Session</span>
                            <span class="text-white fw-semibold">Today at 09:42 AM (Local Workstation)</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2.5">
                            <span style="color: #CBD5E1;"><i class="bi bi-pass-fill me-2 fs-6 text-warning"></i> Password Age</span>
                            <span class="text-white fw-semibold">Updated 24 days ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Assigned Subjects & Recent Log Activity -->
            <div class="col-lg-7">
                <!-- Assigned Courses Card -->
                <div class="glass-card black-card-theme mb-4">
                    <div class="card-header-flex mb-3">
                        <div class="card-title text-white"><i class="bi bi-book-half me-2 text-cyan"></i> Assigned Courses & Batches (AY 2025-2026)</div>
                        <span class="badge bg-info text-dark fw-bold">4 Classes</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0" style="background: #000000;">
                            <thead>
                                <tr style="background: #0B0E14; border-bottom: 2px solid #334155;">
                                    <th class="text-white">Subject Code</th>
                                    <th class="text-white">Subject Title</th>
                                    <th class="text-white">Sem / Div</th>
                                    <th class="text-white">Students</th>
                                    <th class="text-white">Avg. Attendance</th>
                                    <th class="text-white">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid #1E293B;">
                                    <td><span class="badge bg-primary">CE501</span></td>
                                    <td class="fw-semibold text-white">Data Structures & Algorithms</td>
                                    <td style="color: #CBD5E1;">Sem 5 - Div A</td>
                                    <td class="text-white fw-medium">64</td>
                                    <td><span class="badge bg-success">88.2%</span></td>
                                    <td><span class="badge bg-outline-glass text-success border border-success">Active</span></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #1E293B;">
                                    <td><span class="badge bg-info text-dark">AD501</span></td>
                                    <td class="fw-semibold text-white">Machine Learning Fundamentals</td>
                                    <td style="color: #CBD5E1;">Sem 5 - Div B</td>
                                    <td class="text-white fw-medium">58</td>
                                    <td><span class="badge bg-success">91.4%</span></td>
                                    <td><span class="badge bg-outline-glass text-success border border-success">Active</span></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #1E293B;">
                                    <td><span class="badge bg-purple">CE702</span></td>
                                    <td class="fw-semibold text-white">Advanced Cloud Architecture</td>
                                    <td style="color: #CBD5E1;">Sem 7 - Div A</td>
                                    <td class="text-white fw-medium">52</td>
                                    <td><span class="badge bg-warning text-dark">79.8%</span></td>
                                    <td><span class="badge bg-outline-glass text-warning border border-warning">Notice Sent</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">CE304</span></td>
                                    <td class="fw-semibold text-white">Object Oriented Programming (C++)</td>
                                    <td style="color: #CBD5E1;">Sem 3 - Div C</td>
                                    <td class="text-white fw-medium">60</td>
                                    <td><span class="badge bg-success">86.0%</span></td>
                                    <td><span class="badge bg-outline-glass text-success border border-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Administrative Activity Log -->
                <div class="glass-card black-card-theme">
                    <div class="card-header-flex mb-3">
                        <div class="card-title text-white"><i class="bi bi-clock-history me-2 text-success"></i> Recent Activity & Portal Audit Log</div>
                        <span class="badge bg-outline-glass text-light border border-secondary">Last 7 Days</span>
                    </div>

                    <div class="activity-timeline">
                        <div class="d-flex gap-3 mb-3 align-items-start p-3 rounded-3" style="background: #080C14; border: 1px solid #1E293B;">
                            <div class="p-2.5 rounded-circle bg-success text-white mt-1"><i class="bi bi-file-earmark-pdf-fill fs-6"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between text-white fw-semibold small">
                                    <span class="text-white fs-6">Exported Department Attendance Report</span>
                                    <span class="fw-normal" style="color: #94A3B8; font-size: 0.78rem;">Today, 10:15 AM</span>
                                </div>
                                <p class="mb-0 small mt-1" style="color: #CBD5E1; font-size: 0.85rem;">Generated PDF summary for Computer Engineering Department (AY 2025-26).</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-3 align-items-start p-3 rounded-3" style="background: #080C14; border: 1px solid #1E293B;">
                            <div class="p-2.5 rounded-circle bg-warning text-dark mt-1"><i class="bi bi-send-exclamation-fill fs-6"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between text-white fw-semibold small">
                                    <span class="text-white fs-6">Dispatched Defaulter SMS Warning Alerts</span>
                                    <span class="fw-normal" style="color: #94A3B8; font-size: 0.78rem;">Yesterday, 04:30 PM</span>
                                </div>
                                <p class="mb-0 small mt-1" style="color: #CBD5E1; font-size: 0.85rem;">Sent official parent notifications to 3 students falling below 75% threshold.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background: #080C14; border: 1px solid #1E293B;">
                            <div class="p-2.5 rounded-circle bg-info text-dark mt-1"><i class="bi bi-arrow-repeat fs-6"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between text-white fw-semibold small">
                                    <span class="text-white fs-6">Updated Monthly Attendance Register</span>
                                    <span class="fw-normal" style="color: #94A3B8; font-size: 0.78rem;">July 21, 2026</span>
                                </div>
                                <p class="mb-0 small mt-1" style="color: #CBD5E1; font-size: 0.85rem;">Synchronized bi-weekly attendance data for Sem 5 Data Structures lecture.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-secondary text-white" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px);">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Faculty Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm" onsubmit="saveFacultyProfile(event)">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Full Name & Title</label>
                        <input type="text" class="form-control-dark" id="editName" value="Prof. Alex Morgan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Designation / Role</label>
                        <input type="text" class="form-control-dark" id="editRole" value="Head Admin / HOD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Department</label>
                        <input type="text" class="form-control-dark" id="editDept" value="Department of Computer Engineering & AI" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Faculty ID</label>
                            <input type="text" class="form-control-dark" id="editId" value="EMP-CE-1024" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Office Room</label>
                            <input type="text" class="form-control-dark" id="editOffice" value="Building A, Room 304" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email Address</label>
                        <input type="email" class="form-control-dark" id="editEmail" value="alex.morgan@university.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Contact Phone</label>
                        <input type="text" class="form-control-dark" id="editPhone" value="+1 (555) 234-5678" required>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-glass" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4"><i class="bi bi-check2-circle me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
