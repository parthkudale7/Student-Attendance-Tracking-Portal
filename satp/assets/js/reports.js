/**
 * Student Attendance Tracking Portal (SATP) - Reports Controller JS
 * Manages Dynamic AJAX Data, Chart.js Visualizations, PDF & Excel Exports
 */

document.addEventListener('DOMContentLoaded', () => {
    // Determine active page
    const currentPage = document.body.dataset.page || 'monthly';
    
    // Global chart instances registry
    window.satpCharts = {};

    // Mobile Sidebar Drawer Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Initialize global filters dropdowns
    loadFilters();

    // Page-specific initialization
    switch (currentPage) {
        case 'monthly':
            initMonthlyReport();
            break;
        case 'student':
            initStudentReport();
            break;
        case 'department':
            initDepartmentReport();
            break;
        case 'low_attendance':
            initLowAttendanceReport();
            break;
        case 'faculty':
            initFacultyProfile();
            break;
    }
});

// Helper for API fetch
async function fetchAPI(action, params = {}) {
    try {
        const query = new URLSearchParams({ action, ...params }).toString();
        const response = await fetch(`api/reports_api.php?${query}`);
        if (!response.ok) throw new Error('API Network Error');
        return await response.json();
    } catch (err) {
        console.warn('API fetch error, returning null:', err);
        return null;
    }
}

// ----------------------------------------------------
// FILTERS LOADING
// ----------------------------------------------------
async function loadFilters() {
    const data = await fetchAPI('get_filters');
    if (!data) return;

    // Populate Month Filter
    const monthSelects = document.querySelectorAll('#filterMonth, .filter-month');
    const allMonths = data.months || [
        { id: '01', name: 'January' }, { id: '02', name: 'February' }, { id: '03', name: 'March' },
        { id: '04', name: 'April' }, { id: '05', name: 'May' }, { id: '06', name: 'June' },
        { id: '07', name: 'July' }, { id: '08', name: 'August' }, { id: '09', name: 'September' },
        { id: '10', name: 'October' }, { id: '11', name: 'November' }, { id: '12', name: 'December' }
    ];
    monthSelects.forEach(select => {
        if (!select) return;
        const currentVal = select.value || '07';
        select.innerHTML = '';
        allMonths.forEach(m => {
            select.innerHTML += `<option value="${m.id}">${m.name}</option>`;
        });
        select.value = currentVal;
    });

    // Populate Year Filter (2001 to 2027)
    const yearSelects = document.querySelectorAll('#filterYear, .filter-year');
    const allYears = data.years || Array.from({ length: 27 }, (_, i) => 2001 + i);
    yearSelects.forEach(select => {
        if (!select) return;
        const currentVal = select.value || '2026';
        select.innerHTML = '';
        allYears.forEach(y => {
            select.innerHTML += `<option value="${y}">${y}</option>`;
        });
        select.value = currentVal;
    });

    // Populate Semester Filter (1 to 8)
    const semSelects = document.querySelectorAll('#filterSem, .filter-sem');
    const allSemesters = data.semesters || [1, 2, 3, 4, 5, 6, 7, 8];
    semSelects.forEach(select => {
        if (!select) return;
        const currentVal = select.value || '5';
        select.innerHTML = '<option value="">All Semesters</option>';
        allSemesters.forEach(s => {
            select.innerHTML += `<option value="${s}">Sem ${s}</option>`;
        });
        if (currentVal) select.value = currentVal;
    });

    // Populate Division Filter (A to E)
    const divSelects = document.querySelectorAll('#filterDiv, .filter-div');
    const allDivisions = data.divisions || ['A', 'B', 'C', 'D', 'E'];
    divSelects.forEach(select => {
        if (!select) return;
        const currentVal = select.value;
        select.innerHTML = '<option value="">All Divisions</option>';
        allDivisions.forEach(d => {
            select.innerHTML += `<option value="${d}">Division ${d}</option>`;
        });
        if (currentVal) select.value = currentVal;
    });

    // Populate Department Filter & Wire Cascading Subject Filtering
    const deptSelects = document.querySelectorAll('.filter-dept');
    const subjSelects = document.querySelectorAll('.filter-subject');
    const departments = data.departments || [];
    const subjects = data.subjects || [];
    const faculties = data.faculties || [];
    const students = data.students || [];

    deptSelects.forEach((deptSelect, index) => {
        if (!deptSelect) return;
        const currentDeptVal = deptSelect.value;
        deptSelect.innerHTML = '<option value="">All Departments</option>';
        departments.forEach(d => {
            deptSelect.innerHTML += `<option value="${d.id}">${d.dept_code} - ${d.dept_name}</option>`;
        });
        if (currentDeptVal) deptSelect.value = currentDeptVal;

        const subjSelect = subjSelects[index] || document.querySelector('.filter-subject');

        function updateSubjectsForDept() {
            if (!subjSelect) return;
            const selectedDeptId = deptSelect.value;
            const currentSubjVal = subjSelect.value;
            subjSelect.innerHTML = '<option value="">All Subjects</option>';

            const filteredSubjects = selectedDeptId
                ? subjects.filter(s => String(s.dept_id) === String(selectedDeptId))
                : subjects;

            filteredSubjects.forEach(s => {
                subjSelect.innerHTML += `<option value="${s.id}">${s.subject_code} - ${s.subject_name}</option>`;
            });

            if (currentSubjVal && Array.from(subjSelect.options).some(o => o.value === currentSubjVal)) {
                subjSelect.value = currentSubjVal;
            }
        }

        deptSelect.addEventListener('change', updateSubjectsForDept);
        updateSubjectsForDept();
    });

    // Populate Faculty Filter
    const facSelects = document.querySelectorAll('.filter-faculty');
    facSelects.forEach(select => {
        if (!select) return;
        select.innerHTML = '<option value="">All Faculty</option>';
        faculties.forEach(f => {
            select.innerHTML += `<option value="${f.id}">${f.name}</option>`;
        });
    });

    // Populate Student Selector (For Student Report)
    const studentSelect = document.getElementById('studentSelect');
    if (studentSelect && students.length > 0) {
        studentSelect.innerHTML = '';
        students.forEach(st => {
            studentSelect.innerHTML += `<option value="${st.id}">${st.roll_no} - ${st.name} (${st.dept})</option>`;
        });
    }
}

// ----------------------------------------------------
// 1. MONTHLY ATTENDANCE REPORT MODULE
// ----------------------------------------------------
async function initMonthlyReport() {
    const btnGenerate = document.getElementById('btnGenerateMonthly');
    const btnReset = document.getElementById('btnResetMonthly');

    if (btnGenerate) btnGenerate.addEventListener('click', loadMonthlyData);
    if (btnReset) {
        btnReset.addEventListener('click', () => {
            document.querySelectorAll('.form-control-dark').forEach(el => el.value = '');
            loadMonthlyData();
        });
    }

    loadMonthlyData();
}

async function loadMonthlyData() {
    const dept_id = document.getElementById('filterDept')?.value || '';
    const semester = document.getElementById('filterSem')?.value || '';
    const division = document.getElementById('filterDiv')?.value || '';
    const month = document.getElementById('filterMonth')?.value || '07';
    const year = document.getElementById('filterYear')?.value || '2026';

    const data = await fetchAPI('monthly_report', { dept_id, semester, division, month, year });
    if (!data) return;

    // Update Stats
    document.getElementById('statTotalStudents').textContent = data.stats.total_students;
    document.getElementById('statTotalPresent').textContent = data.stats.present_count;
    document.getElementById('statTotalAbsent').textContent = data.stats.absent_count;
    document.getElementById('statOverallPct').textContent = `${data.stats.overall_pct}%`;

    // Render Table
    const tbody = document.getElementById('monthlyTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        if (data.table.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">No attendance records matching filter criteria.</td></tr>`;
        } else {
            data.table.forEach(r => {
                let badgeClass = 'badge-green';
                let fillClass = 'fill-green';
                if (r.status === 'Warning') { badgeClass = 'badge-orange'; fillClass = 'fill-orange'; }
                if (r.status === 'Critical') { badgeClass = 'badge-red'; fillClass = 'fill-red'; }

                tbody.innerHTML += `
                    <tr>
                        <td class="fw-semibold text-white">${r.roll_no}</td>
                        <td>${r.name}</td>
                        <td><span class="badge bg-secondary opacity-75">${r.dept}</span></td>
                        <td><span class="text-success fw-bold">${r.present}</span></td>
                        <td><span class="text-danger fw-bold">${r.absent}</span></td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill ${fillClass}" style="width: ${r.attendance_pct}%"></div>
                            </div>
                            <span class="fw-bold text-white">${r.attendance_pct}%</span>
                        </td>
                        <td><span class="badge-status ${badgeClass}"><i class="bi bi-shield-check"></i> ${r.status}</span></td>
                    </tr>
                `;
            });
        }
    }

    // Render Charts
    renderMonthlyTrendChart(data.trend);
    renderMonthlyDistChart(data.distribution);
}

function renderMonthlyTrendChart(trendData) {
    const ctx = document.getElementById('chartMonthlyTrend')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.monthlyTrend) window.satpCharts.monthlyTrend.destroy();

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(108, 99, 255, 0.4)');
    gradient.addColorStop(1, 'rgba(108, 99, 255, 0.0)');

    window.satpCharts.monthlyTrend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [{
                label: 'Attendance %',
                data: trendData.percentages,
                borderColor: '#6C63FF',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#00D4FF',
                pointRadius: 5
            }]
        },
        options: getDarkChartOptions()
    });
}

function renderMonthlyDistChart(distData) {
    const ctx = document.getElementById('chartMonthlyDist')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.monthlyDist) window.satpCharts.monthlyDist.destroy();

    window.satpCharts.monthlyDist = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: distData.labels,
            datasets: [{
                data: distData.data,
                backgroundColor: ['#22C55E', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94A3B8', font: { family: 'Poppins' } } }
            }
        }
    });
}

// ----------------------------------------------------
// 2. STUDENT WISE REPORT MODULE
// ----------------------------------------------------
async function initStudentReport() {
    const select = document.getElementById('studentSelect');
    if (select) {
        select.addEventListener('change', (e) => {
            if (e.target.value) {
                loadStudentData(e.target.value);
            }
        });
        if (select.value) {
            loadStudentData(select.value);
        }
    }
}

async function loadStudentData(studentId) {
    if (!studentId) return;
    const data = await fetchAPI('student_report', { student_id: studentId });
    if (!data || !data.profile) return;

    // Profile Card
    const p = data.profile;
    if (document.getElementById('stName')) document.getElementById('stName').textContent = p.name;
    if (document.getElementById('stRollNo')) document.getElementById('stRollNo').textContent = p.roll_no;
    if (document.getElementById('stPRN')) document.getElementById('stPRN').textContent = p.prn;
    if (document.getElementById('stDept')) document.getElementById('stDept').textContent = p.dept;
    if (document.getElementById('stSemDiv')) document.getElementById('stSemDiv').textContent = `Semester ${p.semester} - Div ${p.division}`;
    if (document.getElementById('stOverallPct')) document.getElementById('stOverallPct').textContent = `${p.overall_pct}%`;
    if (document.getElementById('stAvatar')) document.getElementById('stAvatar').src = p.avatar;

    const badge = document.getElementById('stStatusBadge');
    if (badge) {
        if (p.overall_pct >= 75) {
            badge.className = 'badge-status badge-green';
            badge.innerHTML = '<i class="bi bi-check-circle"></i> Good Standing';
        } else if (p.overall_pct >= 60) {
            badge.className = 'badge-status badge-orange';
            badge.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Warning';
        } else {
            badge.className = 'badge-status badge-red';
            badge.innerHTML = '<i class="bi bi-x-circle"></i> Critical Low';
        }
    }

    // Subject Breakdown Table
    const subjTbody = document.getElementById('studentSubjectTable');
    if (subjTbody) {
        subjTbody.innerHTML = '';
        data.subjects.forEach(s => {
            let fillClass = s.pct >= 75 ? 'fill-green' : (s.pct >= 60 ? 'fill-orange' : 'fill-red');
            subjTbody.innerHTML += `
                <tr>
                    <td class="fw-semibold text-white">${s.code}</td>
                    <td>${s.name}</td>
                    <td>${s.faculty}</td>
                    <td>${s.total}</td>
                    <td class="text-success fw-bold">${s.present}</td>
                    <td class="text-danger fw-bold">${s.absent}</td>
                    <td>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill ${fillClass}" style="width: ${s.pct}%"></div>
                        </div>
                        <span class="fw-bold text-white">${s.pct}%</span>
                    </td>
                </tr>
            `;
        });
    }

    // History Table
    const histTbody = document.getElementById('studentHistoryTable');
    if (histTbody) {
        histTbody.innerHTML = '';
        data.history.forEach(h => {
            const stColor = h.status === 'Present' ? 'text-success' : 'text-danger';
            histTbody.innerHTML += `
                <tr>
                    <td>${h.date}</td>
                    <td class="fw-semibold text-white">${h.subject}</td>
                    <td>${h.faculty}</td>
                    <td><span class="${stColor} fw-bold">${h.status}</span></td>
                    <td class="text-muted">${h.remarks || '-'}</td>
                </tr>
            `;
        });
    }

    // Charts
    if (data.charts) {
        if (data.charts.subject_bar) renderStudentSubjectChart(data.charts.subject_bar);
        if (data.charts.monthly_trend) renderStudentTrendChart(data.charts.monthly_trend);
        if (data.charts.pie) renderStudentPieChart(data.charts.pie);
    }
}

function renderStudentSubjectChart(chartData) {
    const ctx = document.getElementById('chartStudentSubject')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.stSubject) window.satpCharts.stSubject.destroy();

    window.satpCharts.stSubject = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Subject Attendance %',
                data: chartData.percentages,
                backgroundColor: ['#6C63FF', '#3B82F6', '#00D4FF', '#22C55E'],
                borderRadius: 8
            }]
        },
        options: getDarkChartOptions()
    });
}

function renderStudentTrendChart(chartData) {
    const ctx = document.getElementById('chartStudentTrend')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.stTrend) window.satpCharts.stTrend.destroy();

    window.satpCharts.stTrend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Monthly Trend %',
                data: chartData.percentages,
                borderColor: '#00D4FF',
                borderWidth: 3,
                tension: 0.3,
                fill: false
            }]
        },
        options: getDarkChartOptions()
    });
}

function renderStudentPieChart(chartData) {
    const ctx = document.getElementById('chartStudentPie')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.stPie) window.satpCharts.stPie.destroy();

    window.satpCharts.stPie = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.data,
                backgroundColor: ['#22C55E', '#EF4444'],
                borderWidth: 2,
                borderColor: 'rgba(255, 255, 255, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { color: '#94A3B8', font: { family: 'Poppins', size: 12 } } 
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const val = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${label}: ${val} Classes (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
}

// ----------------------------------------------------
// 3. DEPARTMENT WISE REPORT MODULE
// ----------------------------------------------------
async function initDepartmentReport() {
    const btnGen = document.getElementById('btnGenDept');
    if (btnGen) btnGen.addEventListener('click', loadDeptData);
    loadDeptData();
}

async function loadDeptData() {
    const data = await fetchAPI('department_report');
    if (!data) return;

    // Stats
    document.getElementById('statDeptAvg').textContent = `${data.stats.avg_attendance}%`;
    document.getElementById('statDeptHighest').textContent = data.stats.highest_dept;
    document.getElementById('statDeptLowest').textContent = data.stats.lowest_dept;
    document.getElementById('statDeptTotalStudents').textContent = data.stats.total_students;

    // Department Table
    const tbody = document.getElementById('deptTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        data.table.forEach(d => {
            let fillClass = d.avg_pct >= 85 ? 'fill-green' : (d.avg_pct >= 75 ? 'fill-orange' : 'fill-red');
            tbody.innerHTML += `
                <tr>
                    <td class="fw-semibold text-white">${d.code}</td>
                    <td>${d.dept}</td>
                    <td>${d.total_students}</td>
                    <td>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill ${fillClass}" style="width: ${d.avg_pct}%"></div>
                        </div>
                        <span class="fw-bold text-white">${d.avg_pct}%</span>
                    </td>
                    <td><span class="badge-status badge-green">${d.status}</span></td>
                </tr>
            `;
        });
    }

    // Charts
    renderDeptCompChart(data.dept_comparison);
    renderSemCompChart(data.sem_comparison);
    renderSubjCompChart(data.subject_comparison);
}

function renderDeptCompChart(chartData) {
    const ctx = document.getElementById('chartDeptComparison')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.deptComp) window.satpCharts.deptComp.destroy();

    window.satpCharts.deptComp = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Average Attendance %',
                data: chartData.percentages,
                backgroundColor: ['#6C63FF', '#3B82F6', '#00D4FF', '#22C55E'],
                borderRadius: 10
            }]
        },
        options: getDarkChartOptions()
    });
}

function renderSemCompChart(chartData) {
    const ctx = document.getElementById('chartSemComparison')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.semComp) window.satpCharts.semComp.destroy();

    window.satpCharts.semComp = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Semester Attendance %',
                data: chartData.percentages,
                backgroundColor: '#00D4FF',
                borderRadius: 8
            }]
        },
        options: getDarkChartOptions()
    });
}

function renderSubjCompChart(chartData) {
    const ctx = document.getElementById('chartSubjComparison')?.getContext('2d');
    if (!ctx) return;

    if (window.satpCharts.subjComp) window.satpCharts.subjComp.destroy();

    window.satpCharts.subjComp = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Subject %',
                data: chartData.percentages,
                backgroundColor: '#3B82F6',
                borderRadius: 6
            }]
        },
        options: {
            ...getDarkChartOptions(),
            indexAxis: 'y'
        }
    });
}

// ----------------------------------------------------
// 4. LOW ATTENDANCE ALERTS MODULE
// ----------------------------------------------------
async function initLowAttendanceReport() {
    const thresholdInput = document.getElementById('thresholdInput');
    if (thresholdInput) {
        thresholdInput.addEventListener('change', () => loadLowAttendanceData(thresholdInput.value));
    }
    loadLowAttendanceData(75);
}

async function loadLowAttendanceData(threshold = 75) {
    const data = await fetchAPI('low_attendance', { threshold });
    if (!data) return;

    document.getElementById('statTotalFlagged').textContent = data.summary.total_flagged;
    document.getElementById('statCriticalCount').textContent = data.summary.critical_count;
    document.getElementById('statWarningCount').textContent = data.summary.warning_count;

    const tbody = document.getElementById('lowAttendanceTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        if (data.students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">All students meet the threshold requirement!</td></tr>`;
        } else {
            data.students.forEach(s => {
                let badgeClass = s.color === 'red' ? 'badge-red' : 'badge-orange';
                let fillClass = s.color === 'red' ? 'fill-red' : 'fill-orange';

                tbody.innerHTML += `
                    <tr>
                        <td class="fw-semibold text-white">${s.roll_no}</td>
                        <td>${s.name}</td>
                        <td><span class="badge bg-secondary opacity-75">${s.dept}</span></td>
                        <td>Sem ${s.semester} (${s.division})</td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill ${fillClass}" style="width: ${s.attendance_pct}%"></div>
                            </div>
                            <span class="fw-bold text-white">${s.attendance_pct}%</span>
                        </td>
                        <td><span class="badge-status ${badgeClass}"><i class="bi bi-exclamation-triangle"></i> ${s.status}</span></td>
                        <td><span class="text-muted small">${s.parent_phone}</span></td>
                        <td>
                            <button onclick="triggerSendAlert(${s.id}, '${s.name}', ${s.attendance_pct})" class="btn-custom btn-danger-custom btn-sm py-1 px-3">
                                <i class="bi bi-bell"></i> Send Alert
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
    }
}

// Send Alert Modal / Action
async function triggerSendAlert(studentId, studentName, pct) {
    const response = await fetch('api/reports_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'send_alert', student_id: studentId, name: studentName, pct: pct })
    });
    const res = await response.json();
    if (res.success) {
        showToast(res.message);
    }
}

// ----------------------------------------------------
// EXPORT UTILITIES (PDF, EXCEL, PRINT)
// ----------------------------------------------------
function exportToPDF(reportTitle = 'Attendance Report') {
    const element = document.getElementById('reportExportArea') || document.body;
    const opt = {
        margin:       0.4,
        filename:     `${reportTitle.toLowerCase().replace(/\s+/g, '_')}_${new Date().toISOString().slice(0,10)}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, backgroundColor: '#070B17' },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
    };

    if (window.html2pdf) {
        window.html2pdf().set(opt).from(element).save();
    } else {
        window.print();
    }
}

function exportToExcel(tableId, filename = 'attendance_report') {
    const table = document.getElementById(tableId);
    if (!table) return;

    if (window.XLSX) {
        const wb = XLSX.utils.table_to_book(table, { sheet: "Report Data" });
        XLSX.writeFile(wb, `${filename}_${new Date().toISOString().slice(0,10)}.xlsx`);
    } else {
        alert("Excel export library initializing...");
    }
}

function triggerPrint() {
    window.print();
}

// Toast notification helper
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'modal-glass-backdrop show';
    toast.innerHTML = `
        <div class="modal-glass-content text-center">
            <i class="bi bi-check-circle-fill text-success display-4 mb-3"></i>
            <h5 class="text-white fw-bold mb-2">Notification Sent</h5>
            <p class="text-body small mb-4">${message}</p>
            <button onclick="this.closest('.modal-glass-backdrop').remove()" class="btn-custom btn-primary-custom px-4">OK</button>
        </div>
    `;
    document.body.appendChild(toast);
}

// Dark Chart Options Preset
function getDarkChartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#94A3B8', font: { family: 'Poppins' } } }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                ticks: { color: '#94A3B8', font: { family: 'Poppins' } }
            },
            y: {
                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                ticks: { color: '#94A3B8', font: { family: 'Poppins' } },
                suggestedMin: 0,
                suggestedMax: 100
            }
        }
    };
}

// ----------------------------------------------------
// FACULTY PROFILE MODULE
// ----------------------------------------------------
function initFacultyProfile() {
    console.log('Faculty Profile Module initialized.');
}

function openEditProfileModal() {
    const modalEl = document.getElementById('editProfileModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function saveFacultyProfile(e) {
    e.preventDefault();
    const name = document.getElementById('editName').value;
    const role = document.getElementById('editRole').value;
    const dept = document.getElementById('editDept').value;
    const id = document.getElementById('editId').value;
    const office = document.getElementById('editOffice').value;
    const email = document.getElementById('editEmail').value;
    const phone = document.getElementById('editPhone').value;

    // Update DOM elements dynamically
    const nameDisp = document.getElementById('profileNameDisplay');
    const roleBadge = document.getElementById('profileRoleBadge');
    const deptDisp = document.getElementById('profileDeptDisplay');
    const idDisp = document.getElementById('profileIdDisplay');
    const officeDisp = document.getElementById('profileOfficeDisplay');
    const emailDisp = document.getElementById('profileEmailDisplay');
    const phoneDisp = document.getElementById('profilePhoneDisplay');

    if (nameDisp) nameDisp.textContent = name;
    if (roleBadge) roleBadge.textContent = role;
    if (deptDisp) deptDisp.innerHTML = `<i class="bi bi-building me-1"></i> ${dept}`;
    if (idDisp) idDisp.textContent = id;
    if (officeDisp) officeDisp.textContent = office;
    if (emailDisp) emailDisp.textContent = email;
    if (phoneDisp) phoneDisp.textContent = phone;

    const navName = document.getElementById('navUserName');
    const navRole = document.getElementById('navUserRole');
    if (navName) navName.textContent = name;
    if (navRole) navRole.textContent = role;

    // Close modal
    const modalEl = document.getElementById('editProfileModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    showToast(`Faculty profile updated successfully for <strong>${name}</strong>.`);
}

function togglePasswordForm() {
    const form = document.getElementById('passwordForm');
    if (form) {
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    }
}

function handlePasswordChange(e) {
    e.preventDefault();
    const newPass = document.getElementById('newPass').value;
    const confirmPass = document.getElementById('confirmPass').value;

    if (newPass !== confirmPass) {
        alert('New passwords do not match!');
        return;
    }

    document.getElementById('passwordForm').reset();
    togglePasswordForm();
    showToast('Your account security password was updated successfully.');
}

// ----------------------------------------------------
// LOGOUT CONFIRMATION DIALOG
// ----------------------------------------------------
function confirmLogout(e) {
    if (e) e.preventDefault();
    
    // Remove existing modal if open
    const oldModal = document.getElementById('logoutConfirmModal');
    if (oldModal) oldModal.remove();

    // Create custom glass confirmation modal
    const modal = document.createElement('div');
    modal.className = 'modal-glass-backdrop show';
    modal.id = 'logoutConfirmModal';
    modal.innerHTML = `
        <div class="modal-glass-content text-center" style="max-width: 420px; border: 1px solid rgba(239, 68, 68, 0.4); box-shadow: 0 20px 50px rgba(239, 68, 68, 0.2);">
            <div style="width: 60px; height: 60px; font-size: 1.8rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3);" class="mx-auto mb-3">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <h4 class="text-white fw-bold mb-2">Confirm Logout</h4>
            <p class="text-muted small mb-4">Are you sure you want to log out of your session? Any unsaved portal changes will be saved.</p>
            <div class="d-flex gap-3 justify-content-center">
                <button onclick="document.getElementById('logoutConfirmModal').remove()" class="btn-custom btn-outline-glass px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <button onclick="executeLogout()" class="btn-custom btn-danger-custom px-4">
                    <i class="bi bi-check2-circle me-1"></i> Yes, Logout
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function executeLogout() {
    const modal = document.getElementById('logoutConfirmModal');
    if (modal) modal.remove();

    showToast('Session terminated successfully. Redirecting to login portal...');
    setTimeout(() => {
        const isPhp = window.location.pathname.endsWith('.php');
        window.location.href = isPhp ? 'monthly_report.php?logout=1' : 'monthly_report.html';
    }, 1200);
}


