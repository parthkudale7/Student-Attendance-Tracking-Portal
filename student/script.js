document.addEventListener('DOMContentLoaded', () => {
    // 1. Navigation Logic (SPA)
    const navItems = document.querySelectorAll('.nav-item[data-target]');
    const viewSections = document.querySelectorAll('.view-section');
    const topbarTitle = document.getElementById('topbar-title');
    const topbarSubtitle = document.getElementById('topbar-subtitle');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            item.classList.add('active');
            
            // Get target view id
            const targetId = item.getAttribute('data-target');
            const targetTitle = item.getAttribute('data-title');
            
            // Update Topbar Title
            if(targetId === 'view-dashboard') {
                topbarTitle.innerHTML = 'Good Morning, Parth Kudale <span class="emoji">👋</span>';
                topbarSubtitle.textContent = 'Welcome back to your dashboard';
            } else {
                topbarTitle.textContent = targetTitle;
                if(targetId === 'view-daily') topbarSubtitle.textContent = 'Check your daily attendance record';
                else if(targetId === 'view-subject') topbarSubtitle.textContent = 'Check your attendance percentage for each subject';
                else if(targetId === 'view-percentage') topbarSubtitle.textContent = 'Track your overall attendance percentage';
                else if(targetId === 'view-monthly') topbarSubtitle.textContent = 'Track your attendance trends month-wise';
                else if(targetId === 'view-download') topbarSubtitle.textContent = 'Download your attendance report in PDF or Excel format';
                else topbarSubtitle.textContent = '';
            }

            // Hide all views and show target
            viewSections.forEach(view => {
                view.classList.remove('active');
            });
            document.getElementById(targetId).classList.add('active');
            
            // If navigating to charts, we might need to trigger resize to ensure they render correctly
            if(targetId === 'view-dashboard' && overviewChartInstance) {
                overviewChartInstance.resize();
            }
            if(targetId === 'view-percentage' && trendChartInstance) {
                trendChartInstance.resize();
            }
            if(targetId === 'view-monthly' && window.monthlyTrendChartInstance) {
                window.monthlyTrendChartInstance.resize();
            }
            if(targetId === 'view-daily' && window.presentAbsentChartInstance) {
                window.presentAbsentChartInstance.resize();
            }
            if(targetId === 'view-subject' && window.subjectBarChartInstance) {
                window.subjectBarChartInstance.resize();
            }
        });
    });

    // 2. Chart.js Initialization
    
    // Common Chart Options for the glowing area charts
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(10, 14, 23, 0.9)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: 'rgba(255,255,255,0.1)',
                borderWidth: 1,
                padding: 10,
                displayColors: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    color: '#94a3b8',
                    callback: function(value) { return value + '%'; },
                    font: { size: 11 }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.05)',
                    drawBorder: false,
                },
                border: { display: false }
            },
            x: {
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 }
                },
                grid: {
                    display: false,
                    drawBorder: false,
                },
                border: { display: false }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
    };

    // Present vs Absent Doughnut Chart
    const presentAbsentCtx = document.getElementById('presentAbsentChart');
    window.presentAbsentChartInstance = null;
    if (presentAbsentCtx) {
        window.presentAbsentChartInstance = new Chart(presentAbsentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [76.45, 23.55],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#94a3b8', font: { size: 12 }, padding: 20 }
                    },
                    tooltip: commonOptions.plugins.tooltip
                }
            }
        });
    }

    // Subject-wise Bar Chart
    const subjectBarCtx = document.getElementById('subjectBarChart');
    window.subjectBarChartInstance = null;
    if (subjectBarCtx) {
        window.subjectBarChartInstance = new Chart(subjectBarCtx, {
            type: 'bar',
            data: {
                labels: ['DBMS', 'DSA', 'CN', 'OS', 'DM', 'SE'],
                datasets: [{
                    label: 'Attendance %',
                    data: [78, 82, 71, 75, 81, 76],
                    backgroundColor: '#8b5cf6', // purple color matching theme
                    borderRadius: 4,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: commonOptions.plugins.tooltip
                },
                scales: commonOptions.scales
            }
        });
    }

    // Dashboard Overview Chart
    const overviewCtx = document.getElementById('overviewChart');
    let overviewChartInstance = null;

    if (overviewCtx) {
        // Create gradient
        const gradient = overviewCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.5)'); // Blue glow
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        overviewChartInstance = new Chart(overviewCtx, {
            type: 'line',
            data: {
                labels: ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Attendance',
                    data: [71, 74, 78, 72, 76, 75.6],
                    borderColor: '#60a5fa',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#60a5fa',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: commonOptions
        });
    }

    // Attendance Percentage Trend Chart
    const trendCtx = document.getElementById('trendChart');
    let trendChartInstance = null;
    
    if (trendCtx) {
        // Create gradient
        const gradientTrend = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientTrend.addColorStop(0, 'rgba(37, 99, 235, 0.5)');
        gradientTrend.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        trendChartInstance = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Attendance',
                    data: [71, 74, 78, 72, 76, 75.6],
                    borderColor: '#60a5fa',
                    backgroundColor: gradientTrend,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#60a5fa',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: commonOptions
        });
    }

    // Monthly Trend Chart
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
    window.monthlyTrendChartInstance = null;
    
    if (monthlyTrendCtx) {
        const gradientMonthly = monthlyTrendCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientMonthly.addColorStop(0, 'rgba(37, 99, 235, 0.5)');
        gradientMonthly.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        window.monthlyTrendChartInstance = new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Attendance %',
                    data: [71.20, 71.45, 82.35, 75.60, 76.85, 78.90],
                    borderColor: '#60a5fa',
                    backgroundColor: gradientMonthly,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#60a5fa',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: commonOptions
        });
    }

    // 3. Download Report Logic
    const downloadBtn = document.getElementById('download-report-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', () => {
            const format = document.getElementById('report-format').value;
            // Let's download the subject-wise table as the actual report since that contains the data
            // Or fallback to the preview box if you want just a receipt. 
            // We'll target the whole subject layout for a rich report.
            const elementToDownload = document.querySelector('.subject-layout') || document.getElementById('report-preview-box');
            
            // Show loading state
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
            downloadBtn.disabled = true;

            // Small delay to allow UI to update before blocking main thread
            setTimeout(() => {
                if (format === 'pdf') {
                    const opt = {
                        margin:       0.5,
                        filename:     'Attendance_Report.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#0a0e17' },
                        jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
                    };
                    
                    html2pdf().set(opt).from(elementToDownload).save().then(() => {
                        downloadBtn.innerHTML = originalText;
                        downloadBtn.disabled = false;
                    });
                } else if (format === 'image') {
                    html2canvas(elementToDownload, {
                        scale: 2,
                        useCORS: true,
                        backgroundColor: '#0a0e17'
                    }).then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'Attendance_Report.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        
                        downloadBtn.innerHTML = originalText;
                        downloadBtn.disabled = false;
                    });
                }
            }, 100);
        });
    }

    // 4. Update Report Preview Dynamically
    const reportFromDate = document.getElementById('report-from-date');
    const reportToDate = document.getElementById('report-to-date');
    const reportSubject = document.getElementById('report-subject');
    const previewDateRange = document.getElementById('preview-date-range');
    const previewSubjectText = document.getElementById('preview-subject-text');

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        const date = new Date(dateStr);
        if (isNaN(date)) return dateStr;
        return date.toLocaleDateString('en-GB', options);
    }

    function updatePreview() {
        if (reportFromDate && reportToDate && previewDateRange) {
            const from = formatDate(reportFromDate.value) || 'Start Date';
            const to = formatDate(reportToDate.value) || 'End Date';
            previewDateRange.textContent = `${from} - ${to}`;
        }
        if (reportSubject && previewSubjectText) {
            const selectedOption = reportSubject.options[reportSubject.selectedIndex];
            if (reportSubject.value === 'all') {
                previewSubjectText.textContent = 'Subject-wise attendance';
            } else {
                previewSubjectText.textContent = `${selectedOption.text} attendance`;
            }
        }
    }

    if (reportFromDate) reportFromDate.addEventListener('change', updatePreview);
    if (reportToDate) reportToDate.addEventListener('change', updatePreview);
    if (reportSubject) reportSubject.addEventListener('change', updatePreview);
    
    // Initial call to set formatted default dates
    updatePreview();

    // 5. Logout Modal Logic
    const logoutBtn = document.getElementById('logout-btn');
    const logoutModal = document.getElementById('logout-modal');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');

    if (logoutBtn && logoutModal) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.classList.add('show');
        });

        const hideModal = () => logoutModal.classList.remove('show');

        if (cancelLogoutBtn) cancelLogoutBtn.addEventListener('click', hideModal);
        if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
        
        // Hide when clicking outside
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) hideModal();
        });

        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', () => {
                // Redirect to a login page (we'll just reload the dashboard or go to a placeholder login.html)
                // For demonstration, since we don't have a backend, we will just redirect to index.html with a query param
                window.location.href = '../auth/logout.php';
            });
        }
    }
});

// Expose navigate function for quick action buttons
window.navigateTo = function(targetId) {
    const navLink = document.querySelector(`.nav-item[data-target="${targetId}"]`);
    if(navLink) {
        navLink.click();
    }
};
