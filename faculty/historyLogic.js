window.historyLogic = (function() {
    let allRecords = [];
    let filteredRecords = [];
    let masterData = null;
    let currentPage = 1;
    let rowsPerPage = 10;
    
    async function loadRecords() {
        try {
            const API_BASE = '../api/index.php';
            
            // Fetch master data first
            if (!masterData) {
                const masterRes = await fetch(`${API_BASE}?request=master-data`, { cache: 'no-store' });
                if (masterRes.ok) {
                    masterData = await masterRes.json();
                }
            }

            const res = await fetch(`${API_BASE}?request=history`, { cache: 'no-store' });
            if (res.ok) {
                const data = await res.json();
                allRecords = Array.isArray(data) ? data : [];
            } else {
                allRecords = [];
                if (window.showNotification) window.showNotification('Failed to load history data', 'danger');
            }
        } catch (error) {
            console.error('Network error:', error);
            allRecords = [];
            if (window.showNotification) window.showNotification('Network error while loading history', 'danger');
        }
        
        populateMasterFilters();
        applyFilters();
    }
    
    function populateMasterFilters() {
        if (!masterData) return;

        // Department
        const deptSelect = document.getElementById('hist-dept');
        if (deptSelect && deptSelect.options.length <= 1) {
            masterData.departments.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d; opt.textContent = d;
                deptSelect.appendChild(opt);
            });
        }

        // Semester
        const semSelect = document.getElementById('hist-sem');
        if (semSelect && semSelect.options.length <= 1) {
            masterData.semesters.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s; opt.textContent = s;
                semSelect.appendChild(opt);
            });
        }

        // Faculty was removed per user request

        updateDependentFilters();
    }

    function updateDependentFilters() {
        if (!masterData) return;
        
        const dept = document.getElementById('hist-dept') ? document.getElementById('hist-dept').value : '';
        const sem = document.getElementById('hist-sem') ? document.getElementById('hist-sem').value : '';
        
        const subjSelect = document.getElementById('hist-subject');
        const divSelect = document.getElementById('hist-div');

        if (subjSelect) {
            const currentSubj = subjSelect.value;
            subjSelect.innerHTML = '<option value="">All Subjects</option>';
            if (dept && sem && masterData.subjects[dept] && masterData.subjects[dept][sem]) {
                masterData.subjects[dept][sem].forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s; opt.textContent = s;
                    subjSelect.appendChild(opt);
                });
            } else {
                // If not both selected, we could show all subjects, but to match Mark Attendance exactly:
                // If no specific filter, just populate all subjects or leave it empty? User said "Load subjects according to selected Department and Semester... Show All Subjects by default."
                // Let's dump all subjects if dept/sem not selected
                let allSubjs = new Set();
                Object.values(masterData.subjects).forEach(deptObj => {
                    Object.values(deptObj).forEach(arr => {
                        arr.forEach(s => allSubjs.add(s));
                    });
                });
                if (dept && !sem && masterData.subjects[dept]) {
                    allSubjs = new Set();
                    Object.values(masterData.subjects[dept]).forEach(arr => arr.forEach(s => allSubjs.add(s)));
                } else if (!dept && sem) {
                    allSubjs = new Set();
                    Object.values(masterData.subjects).forEach(deptObj => {
                        if(deptObj[sem]) deptObj[sem].forEach(s => allSubjs.add(s));
                    });
                }
                
                allSubjs.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s; opt.textContent = s;
                    subjSelect.appendChild(opt);
                });
            }
            if (currentSubj) subjSelect.value = currentSubj;
        }

        if (divSelect) {
            const currentDiv = divSelect.value;
            divSelect.innerHTML = '<option value="">All Divisions</option>';
            
            let allDivs = new Set();
            if (dept && sem && masterData.divisions[dept] && masterData.divisions[dept][sem]) {
                masterData.divisions[dept][sem].forEach(d => allDivs.add(d));
            } else {
                Object.values(masterData.divisions).forEach(deptObj => {
                    Object.values(deptObj).forEach(arr => arr.forEach(d => allDivs.add(d)));
                });
            }

            allDivs.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d; opt.textContent = d;
                divSelect.appendChild(opt);
            });
            if (currentDiv) divSelect.value = currentDiv;
        }
    }

    function onDeptChange() {
        updateDependentFilters();
    }

    function onSemChange() {
        updateDependentFilters();
    }

    function applyFilters() {
        const academicYear = document.getElementById('hist-academic-year') ? document.getElementById('hist-academic-year').value : '';
        const sem = document.getElementById('hist-sem') ? document.getElementById('hist-sem').value : '';
        const dept = document.getElementById('hist-dept') ? document.getElementById('hist-dept').value : '';
        const div = document.getElementById('hist-div') ? document.getElementById('hist-div').value : '';
        const subject = document.getElementById('hist-subject') ? document.getElementById('hist-subject').value : '';
        const fromDate = document.getElementById('hist-from-date') ? document.getElementById('hist-from-date').value : '';
        const toDate = document.getElementById('hist-to-date') ? document.getElementById('hist-to-date').value : '';
        filteredRecords = [];
        allRecords.forEach(record => {
            let match = true;
            if (sem && record.sem !== sem) match = false;
            if (dept && record.dept !== dept) match = false;
            if (div && record.div !== div) match = false;
            if (subject && record.subject !== subject) match = false;
            if (fromDate && record.date < fromDate) match = false;
            if (toDate && record.date > toDate) match = false;
            
            if (match && record.students) {
                record.students.forEach(student => {
                     filteredRecords.push({
                         ...record, 
                         students: undefined, 
                         rollNo: student.roll, 
                         studentName: student.name, 
                         attendanceStatus: student.status, 
                         remarks: student.remarks || '-'
                     });
                });
            }
        });
        
        filteredRecords.sort((a,b) => new Date(b.date) - new Date(a.date));
        
        currentPage = 1;
        updateSummaryCards();
        renderTable();
        if(typeof renderPagination === 'function') renderPagination();
        
        if(typeof event !== 'undefined' && event && event.type === 'click') {
            if(window.showNotification) window.showNotification('Filters Applied Successfully', 'success');
        }
    }
    
    function resetFilters() {
        if(document.getElementById('hist-academic-year')) document.getElementById('hist-academic-year').selectedIndex = 0;
        if(document.getElementById('hist-sem')) document.getElementById('hist-sem').value = '';
        if(document.getElementById('hist-dept')) document.getElementById('hist-dept').value = '';
        if(document.getElementById('hist-div')) document.getElementById('hist-div').value = '';
        if(document.getElementById('hist-subject')) document.getElementById('hist-subject').value = '';
        updateDependentFilters();
        if(document.getElementById('hist-from-date')) document.getElementById('hist-from-date').value = '';
        if(document.getElementById('hist-to-date')) document.getElementById('hist-to-date').value = '';
        
        applyFilters();
        if(window.showNotification) window.showNotification('Filters Reset Successfully', 'info');
    }
    
    function updateSummaryCards() {
        let total = filteredRecords.length;
        let approved = 0, pending = 0, rejected = 0;
        
        filteredRecords.forEach(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            if (valStatusLower === 'approved') approved++;
            else if (valStatusLower === 'rejected') rejected++;
            else pending++;
        });
        
        if(document.getElementById('hist-sum-total')) document.getElementById('hist-sum-total').textContent = total;
        if(document.getElementById('hist-sum-approved')) document.getElementById('hist-sum-approved').textContent = approved;
        if(document.getElementById('hist-sum-pending')) document.getElementById('hist-sum-pending').textContent = pending;
        if(document.getElementById('hist-sum-rejected')) document.getElementById('hist-sum-rejected').textContent = rejected;
    }
    
    function renderTable() {
        const tbody = document.getElementById('hist-tbody');
        const emptyState = document.getElementById('hist-empty-state');
        const tableContainer = document.getElementById('hist-table-container');
        
        if (!tbody) return;
        tbody.innerHTML = '';
        
        if (filteredRecords.length === 0) {
            if(emptyState) emptyState.style.display = 'block';
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="15" style="text-align: center; padding: 30px; color: var(--text-muted);">No records found matching your filters.</td>`;
            tbody.appendChild(tr);
            return;
        } else {
            if(emptyState) emptyState.style.display = 'none';
        }
        
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, filteredRecords.length);
        
        for (let i = startIndex; i < endIndex; i++) {
            const r = filteredRecords[i];
            
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : 
                            valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
                            
            const valBadgeClass = rStatus === 'Approved' ? 'badge-approved' : 
                                  rStatus === 'Rejected' ? 'badge-rejected' : 'badge-pending';
            
            const valIcon = rStatus === 'Approved' ? 'fa-check' : 
                            rStatus === 'Rejected' ? 'fa-xmark' : 'fa-clock';
            
            const attBadgeClass = r.attendanceStatus === 'Present' ? 'status-present' : 'status-absent';
                               
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${r.date}</td>
                <td>${r.dept}</td>
                <td>${r.sem}</td>
                <td>${r.div || 'A'}</td>
                <td>${r.subject}</td>
                <td>${r.lecture}</td>
                <td>${r.rollNo}</td>
                <td>${r.studentName}</td>
                <td><span class="status-badge-premium ${attBadgeClass}">${r.attendanceStatus}</span></td>
                <td><span class="validation-badge ${valBadgeClass}"><i class="fa-solid ${valIcon}"></i> ${rStatus}</span></td>
                <td>${r.faculty || '-'}</td>
                <td>${r.validatedBy || '-'}</td>
                <td>${r.validationDate || '-'}</td>
                <td>${r.remarks || '-'}</td>
                <td class="action-cell" onclick="window.historyLogic.viewDetails('${r.id}')"><i class="fa-solid fa-ellipsis-vertical"></i></td>
            `;
            tbody.appendChild(tr);
        }
        if(typeof checkBulkActions === 'function') checkBulkActions();
    }
    
    function checkBulkActions() {
        const checkboxes = document.querySelectorAll('.hist-row-checkbox:checked');
        const bulkActions = document.getElementById('hist-bulk-actions');
        if (!bulkActions) return;
        if (checkboxes.length > 0) {
            bulkActions.style.display = 'flex';
        } else {
            bulkActions.style.display = 'none';
        }
    }
    
    function renderPagination() {
        const totalRecords = filteredRecords.length;
        document.getElementById('hist-total-records').textContent = totalRecords;
        
        if (totalRecords === 0) {
            document.getElementById('hist-page-start').textContent = 0;
            document.getElementById('hist-page-end').textContent = 0;
            document.getElementById('hist-pagination-controls').innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(totalRecords / rowsPerPage);
        
        const startIndex = (currentPage - 1) * rowsPerPage + 1;
        const endIndex = Math.min(currentPage * rowsPerPage, totalRecords);
        
        document.getElementById('hist-page-start').textContent = startIndex;
        document.getElementById('hist-page-end').textContent = endIndex;
        
        const controls = document.getElementById('hist-pagination-controls');
        controls.innerHTML = '';
        
        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
        prevBtn.disabled = currentPage === 1;
        if(currentPage === 1) prevBtn.style.opacity = '0.5';
        prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; renderTable(); renderPagination(); } };
        controls.appendChild(prevBtn);
        
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        if (startPage > 1) {
            controls.appendChild(createPageBtn(1));
            if (startPage > 2) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                dots.style.margin = '0 5px';
                controls.appendChild(dots);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            controls.appendChild(createPageBtn(i));
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                dots.style.margin = '0 5px';
                controls.appendChild(dots);
            }
            controls.appendChild(createPageBtn(totalPages));
        }
        
        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
        nextBtn.disabled = currentPage === totalPages;
        if(currentPage === totalPages) nextBtn.style.opacity = '0.5';
        nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; renderTable(); renderPagination(); } };
        controls.appendChild(nextBtn);
    }
    
    function createPageBtn(pageNum) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${pageNum === currentPage ? 'active' : ''}`;
        btn.textContent = pageNum;
        btn.onclick = () => {
            currentPage = pageNum;
            renderTable();
            renderPagination();
        };
        return btn;
    }
    
    function changeRowsPerPage() {
        rowsPerPage = parseInt(document.getElementById('hist-rows-per-page').value);
        currentPage = 1;
        renderTable();
        renderPagination();
    }
    
    function toggleSelectAll() {
        const isChecked = document.getElementById('hist-select-all').checked;
        const checkboxes = document.querySelectorAll('.hist-row-checkbox');
        checkboxes.forEach(cb => cb.checked = isChecked);
        checkBulkActions();
    }
    
    function handleSearch() {
        applyFilters();
    }
    
    function viewSelected() {
        const checkboxes = document.querySelectorAll('.hist-row-checkbox:checked');
        if (checkboxes.length > 0) {
            viewDetails(checkboxes[0].value);
        }
    }
    
    function printHistory() {
        const printWindow = window.open('', '_blank');
        
        let tableHtml = `
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h1 { text-align: center; color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .status-present { color: green; }
                .status-absent { color: red; }
            </style>
            <h1>Attendance History</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Dept</th>
                        <th>Sem</th>
                        <th>Div</th>
                        <th>Subject</th>
                        <th>Lecture</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Validation</th>
                        <th>Faculty</th>
                        <th>Validated By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        filteredRecords.forEach(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
            
            tableHtml += `
                <tr>
                    <td>${r.date}</td>
                    <td>${r.dept}</td>
                    <td>${r.sem}</td>
                    <td>${r.div || 'A'}</td>
                    <td>${r.subject}</td>
                    <td>${r.lecture}</td>
                    <td>${r.rollNo}</td>
                    <td>${r.studentName}</td>
                    <td class="status-${r.attendanceStatus.toLowerCase()}">${r.attendanceStatus}</td>
                    <td>${rStatus}</td>
                    <td>${r.faculty || '-'}</td>
                    <td>${r.validatedBy || '-'}</td>
                    <td>${r.remarks || '-'}</td>
                </tr>
            `;
        });
        
        tableHtml += `</tbody></table>`;
        
        printWindow.document.write(tableHtml);
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    }
    
    function exportSelected() {
        exportData('pdf');
    }
    
    // --- Export Logic ---
    function toggleExportDropdown(event) {
        if(event) event.stopPropagation();
        const dropdown = document.getElementById('hist-export-dropdown');
        dropdown.classList.toggle('show');
    }
    
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('hist-export-dropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            const btn = dropdown.previousElementSibling;
            if (!dropdown.contains(event.target) && event.target !== btn && !btn.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        }
    });
    
    function exportPDF() {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            console.error('jsPDF library missing');
            if (window.showNotification) window.showNotification('PDF export library not loaded.', 'danger');
            return;
        }
        
        const doc = new window.jspdf.jsPDF('landscape');
        
        doc.setFontSize(18);
        doc.text("Attendance History Report", 14, 22);
        
        doc.setFontSize(11);
        doc.setTextColor(100);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 30);
        
        const headers = [['Date', 'Dept', 'Sem', 'Div', 'Subject', 'Lecture', 'Roll No', 'Name', 'Status', 'Validation']];
        const rows = filteredRecords.map(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
            return [
                r.date, r.dept, r.sem, r.div || 'A', r.subject, r.lecture, r.rollNo, r.studentName, r.attendanceStatus, rStatus
            ];
        });
        
        doc.autoTable({
            startY: 35,
            head: headers,
            body: rows,
            theme: 'grid',
            headStyles: { fillColor: [11, 42, 91] }, // Dark blue matching UI
            styles: { fontSize: 8 },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        
        doc.save(`Attendance_History_${new Date().toISOString().split('T')[0]}.pdf`);
        if (window.showNotification) window.showNotification('PDF exported successfully', 'success');
    }

    function exportData(format) {
        document.getElementById('hist-export-dropdown').classList.remove('show');
        
        let recordsToExport = filteredRecords; // Always export only filtered data as per instructions
        
        if (recordsToExport.length === 0) {
            if(window.showNotification) window.showNotification('No records available to export', 'danger');
            return;
        }
        
        if(window.showNotification) window.showNotification('Export Started...', 'info');
        setTimeout(() => {
            if(window.showNotification) window.showNotification('Generating Report...', 'info');
            
            try {
                if (format === 'csv') {
                    exportCSV(recordsToExport);
                } else if (format === 'excel') {
                    exportExcel(recordsToExport);
                } else if (format === 'pdf') {
                    exportPDF();
                }
                setTimeout(() => {
                    if(window.showNotification) window.showNotification('Report Exported Successfully', 'success');
                }, 1000);
            } catch (err) {
                console.error('Export Error:', err);
                if(window.showNotification) window.showNotification('Export Failed', 'danger');
            }
        }, 500); // simulate slightly asynchronous behavior for the toasts
    }
    
    function getExportTimestamp() {
        const d = new Date();
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const hours = String(d.getHours()).padStart(2, '0');
        const mins = String(d.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}_${hours}-${mins}`;
    }

    function exportCSV(records) {
        const headers = ['Date', 'Department', 'Semester', 'Division', 'Subject', 'Lecture No.', 'Roll No.', 'Student Name', 'Attendance Status', 'Validation Status', 'Faculty', 'Validated By', 'Validation Date & Time', 'Remarks'];
        const rows = records.map(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
            return [
                r.date, r.dept, r.sem, r.div || 'A', r.subject, r.lecture, r.rollNo, r.studentName, r.attendanceStatus, rStatus, r.faculty||'-', r.validatedBy||'-', r.validationDate||'-', r.remarks||'-'
            ];
        });
        
        const csvContent = "\uFEFF" + [headers, ...rows].map(e => e.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(",")).join("\n");
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", `Attendance_Report_${getExportTimestamp()}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
    
    function exportExcel(records) {
        if (!window.XLSX) throw new Error('Excel library missing');
        const headers = ['Date', 'Department', 'Semester', 'Division', 'Subject', 'Lecture No.', 'Roll No.', 'Student Name', 'Attendance Status', 'Validation Status', 'Faculty', 'Validated By', 'Validation Date & Time', 'Remarks'];
        const rows = records.map(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
            return [
                r.date, r.dept, r.sem, r.div || 'A', r.subject, r.lecture, r.rollNo, r.studentName, r.attendanceStatus, rStatus, r.faculty||'-', r.validatedBy||'-', r.validationDate||'-', r.remarks||'-'
            ];
        });
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
        
        XLSX.utils.book_append_sheet(wb, ws, "Attendance History");
        XLSX.writeFile(wb, `Attendance_Report_${getExportTimestamp()}.xlsx`);
    }
    
    function exportPDF(records) {
        if (!window.jspdf || !window.jspdf.jsPDF) throw new Error('PDF library missing');
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        doc.setFontSize(16);
        doc.text('College Name', 14, 15);
        doc.setFontSize(12);
        doc.text('Attendance History Report', 14, 23);
        
        doc.setFontSize(10);
        doc.text(`Generated Date: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 14, 30);
        
        const ay = document.getElementById('hist-academic-year') ? document.getElementById('hist-academic-year').value : '';
        if(ay) doc.text(`Academic Year: ${ay}`, 14, 36);

        const headers = [['Date', 'Dept', 'Sem', 'Div', 'Subject', 'Lecture', 'Roll No', 'Name', 'Status', 'Validation']];
        const rows = records.map(r => {
            const valStatusLower = (r.validationStatus || '').toLowerCase();
            const rStatus = valStatusLower === 'approved' ? 'Approved' : valStatusLower === 'rejected' ? 'Rejected' : 'Pending';
            return [
                r.date, r.dept, r.sem, r.div||'A', r.subject, r.lecture, r.rollNo, r.studentName, r.attendanceStatus, rStatus
            ];
        });
        
        doc.autoTable({
            startY: 42,
            head: headers,
            body: rows,
            theme: 'grid',
            headStyles: { fillColor: [79, 70, 229] }, // Primary color
            styles: { fontSize: 8 },
            didDrawPage: function (data) {
                doc.setFontSize(8);
                doc.text('Confidential - Page ' + data.pageCount, 14, doc.internal.pageSize.height - 10);
            }
        });
        doc.save(`Attendance_Report_${getExportTimestamp()}.pdf`);
    }
    
    // --- Modal Logic ---
    let currentModalRecord = null;
    
    function viewDetails(recordId) {
        currentModalRecord = allRecords.find(r => r.id === recordId);
        if (!currentModalRecord) return;
        
        const infoHtml = `
            <h4 style="margin-top: 0;">Record Summary</h4>
            <div style="font-size: 0.9rem; line-height: 1.6; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div><span class="text-muted">Subject:</span> <strong style="color: #fff;">${currentModalRecord.subject}</strong></div>
                <div><span class="text-muted">Faculty:</span> <strong style="color: #fff;">${currentModalRecord.faculty || 'Unknown'}</strong></div>
                <div><span class="text-muted">Date:</span> <strong style="color: #fff;">${currentModalRecord.date}</strong></div>
                <div><span class="text-muted">Lecture:</span> <strong style="color: #fff;">${currentModalRecord.lecture}</strong></div>
                <div><span class="text-muted">Class:</span> <strong style="color: #fff;">${currentModalRecord.dept} - Sem ${currentModalRecord.sem} ${currentModalRecord.div||''}</strong></div>
                <div><span class="text-muted">Status:</span> <strong style="color: #fff; text-transform: capitalize;">${currentModalRecord.validationStatus || 'Pending'}</strong></div>
                <div><span class="text-muted">Updated:</span> <strong style="color: #fff;">${currentModalRecord.created_date || currentModalRecord.date} ${currentModalRecord.created_time || '10:00 AM'}</strong></div>
                <div><span class="text-muted">Total Students:</span> <strong style="color: #fff;">${currentModalRecord.students.length}</strong></div>
            </div>
        `;
        document.getElementById('hist-modal-info').innerHTML = infoHtml;
        
        document.getElementById('hist-modal-search').value = '';
        renderModalTable();
        
        document.getElementById('hist-details-modal').style.display = 'flex';
    }
    
    function renderModalTable() {
        if (!currentModalRecord) return;
        const searchTerm = document.getElementById('hist-modal-search').value.toLowerCase();
        const tbody = document.getElementById('hist-modal-tbody');
        tbody.innerHTML = '';
        
        currentModalRecord.students.forEach(student => {
            if (searchTerm && 
                !student.name.toLowerCase().includes(searchTerm) && 
                !student.roll.toLowerCase().includes(searchTerm)) {
                return;
            }
            
            const colorMap = {
                'Present': 'var(--success)',
                'Absent': 'var(--danger)',
                'Late': 'var(--warning)',
                'On Leave': '#3B82F6',
                'Not Marked': '#9CA3AF'
            };
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${student.roll}</td>
                <td>${student.name}</td>
                <td>
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: ${colorMap[student.status] || colorMap['Not Marked']};"></div>
                        ${student.status}
                    </span>
                </td>
                <td>${currentModalRecord.subject}</td>
                <td>${currentModalRecord.dept}</td>
                <td>${currentModalRecord.div || 'A'}</td>
                <td>${currentModalRecord.sem}</td>
                <td>${currentModalRecord.lecture}</td>
                <td>${currentModalRecord.date}</td>
                <td>${currentModalRecord.faculty || 'Unknown'}</td>
            `;
            tbody.appendChild(tr);
        });
    }
    
    function handleModalSearch() {
        renderModalTable();
    }
    
    function closeModal() {
        document.getElementById('hist-details-modal').style.display = 'none';
        currentModalRecord = null;
    }
    
    function exportModalData() {
        if(!currentModalRecord) return;
        
        const headers = ['Roll No', 'Name', 'Status'];
        const rows = currentModalRecord.students.map(s => [s.roll, s.name, s.status]);
        
        const csvContent = "\uFEFF" + [headers, ...rows].map(e => e.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(",")).join("\n");
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", `Student_List_${currentModalRecord.date}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
    
    // Setup listener to reload records when navigating to the history module
    document.addEventListener('DOMContentLoaded', () => {
        const originalNav = window.navigateTo;
        if(typeof window.navigateTo === 'function') {
            window.navigateTo = function(viewId) {
                originalNav(viewId);
                if(viewId === 'attendance-history') {
                    loadRecords();
                }
            };
        }
    });

    return {
        loadRecords,
        applyFilters,
        onDeptChange,
        onSemChange,
        resetFilters,
        changeRowsPerPage,
        toggleSelectAll,
        handleSearch,
        toggleExportDropdown,
        exportData,
        viewDetails,
        closeModal,
        handleModalSearch,
        exportModalData,
        viewSelected,
        printHistory,
        exportSelected,
        checkBulkActions
    };
})();
