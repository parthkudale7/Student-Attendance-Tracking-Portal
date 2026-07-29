// Attendance Validation Logic

window.fetchValidationRecords = async () => {
    // Fetch all validation records directly
    let url = `${API_BASE}?request=validation-list&status=All`;
    
    try {
        const res = await fetch(url, { cache: 'no-store' });
        
        if (!res.ok) {
            console.error(`HTTP Error: ${res.status} ${res.statusText}`);
            throw new Error(`Server returned ${res.status}`);
        }
        
        const text = await res.text();
        let records;
        
        try {
            records = JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text);
            throw new Error('Invalid JSON response from server');
        }
        
        if (records && records.error) {
            console.error('API Error:', records.error);
            throw new Error(records.error);
        }
        
        if (!Array.isArray(records)) {
            console.error('Expected an array but got:', records);
            throw new Error('Invalid data format received');
        }
        
        window.renderValidationTables(records);
    } catch(err) {
        console.error('Validation fetch error:', err);
        
        const setErrorMessage = (tbodyId) => {
            const tbody = document.getElementById(tbodyId);
            if (tbody) tbody.innerHTML = `<tr><td colspan="11" style="text-align: center; color: var(--danger); padding: 20px;">Error: ${err.message}</td></tr>`;
        };
        
        setErrorMessage('val-tbody-pending');
        setErrorMessage('val-tbody-approved');
        setErrorMessage('val-tbody-rejected');
        
        // Only show toast for actual server/network errors, not just "no data"
        if (typeof window.showToast === 'function') {
            window.showToast(`Error: ${err.message}`, 'error');
        }
    }
};

window.renderValidationTables = (records) => {
    const pendingRecords = records.filter(r => r.validationStatus === 'Pending');
    const approvedRecords = records.filter(r => r.validationStatus === 'Approved');
    const rejectedRecords = records.filter(r => r.validationStatus === 'Rejected');
    
    // Update count badges
    const badgePending = document.getElementById('badge-pending');
    const badgeApproved = document.getElementById('badge-approved');
    const badgeRejected = document.getElementById('badge-rejected');
    if (badgePending) badgePending.textContent = pendingRecords.length;
    if (badgeApproved) badgeApproved.textContent = approvedRecords.length;
    if (badgeRejected) badgeRejected.textContent = rejectedRecords.length;
    
    // Helper to format attendance badge safely
    const getAttBadge = (status) => {
        if (!status) return '-';
        const s = String(status).toLowerCase();
        if (s === 'present') return '<span class="status-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16,185,129,0.3);"><i class="fa-solid fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i> Present</span>';
        if (s === 'absent') return '<span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.3);"><i class="fa-solid fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i> Absent</span>';
        if (s === 'late') return '<span class="status-badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245,158,11,0.3);"><i class="fa-solid fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i> Late</span>';
        return `<span class="status-badge" style="background: rgba(59, 130, 246, 0.1); color: var(--info); border: 1px solid rgba(59,130,246,0.3);"><i class="fa-solid fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i> ${status}</span>`;
    };

    const getSafe = (val) => val !== null && val !== undefined ? val : '-';

    // Render Pending
    const pendingTbody = document.getElementById('val-tbody-pending');
    if (pendingTbody) {
        pendingTbody.innerHTML = '';
        if (pendingRecords.length === 0) {
            pendingTbody.innerHTML = '<tr><td colspan="11" style="text-align: center; color: var(--text-secondary); padding: 20px;">No pending validation records found.</td></tr>';
        } else {
            pendingRecords.forEach(record => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${getSafe(record.date)}</td>
                    <td>${getSafe(record.dept)}</td>
                    <td>${getSafe(record.sem)}</td>
                    <td>${getSafe(record.div)}</td>
                    <td>${getSafe(record.subject)}</td>
                    <td>${getSafe(record.lecture)}</td>
                    <td>${getAttBadge(record.status)}</td>
                    <td>${getSafe(record.roll)}</td>
                    <td><strong>${getSafe(record.name)}</strong></td>
                    <td>${getSafe(record.faculty)}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <button class="btn btn-sm btn-outline success" style="padding: 6px 12px;" onclick="window.submitValidation('Single', 'Approved', ${record.id})"><i class="fa-solid fa-check"></i> Approve</button>
                            <button class="btn btn-sm btn-outline danger" style="padding: 6px 12px;" onclick="window.submitValidation('Single', 'Rejected', ${record.id})"><i class="fa-solid fa-xmark"></i> Reject</button>
                        </div>
                    </td>
                `;
                pendingTbody.appendChild(tr);
            });
        }
    }

    // Render Approved
    const approvedTbody = document.getElementById('val-tbody-approved');
    if (approvedTbody) {
        approvedTbody.innerHTML = '';
        if (approvedRecords.length === 0) {
            approvedTbody.innerHTML = '<tr><td colspan="11" style="text-align: center; color: var(--text-secondary); padding: 20px;">No approved validation records found.</td></tr>';
        } else {
            approvedRecords.forEach(record => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${getSafe(record.date)}</td>
                    <td>${getSafe(record.dept)}</td>
                    <td>${getSafe(record.sem)}</td>
                    <td>${getSafe(record.div)}</td>
                    <td>${getSafe(record.subject)}</td>
                    <td>${getSafe(record.lecture)}</td>
                    <td>${getAttBadge(record.status)}</td>
                    <td>${getSafe(record.roll)}</td>
                    <td><strong>${getSafe(record.name)}</strong></td>
                    <td>${getSafe(record.faculty)}</td>
                    <td>
                        <div style="font-size: 0.9em; font-weight: 500;">${getSafe(record.date)} ${getSafe(record.time)}</div>
                    </td>
                `;
                approvedTbody.appendChild(tr);
            });
        }
    }

    // Render Rejected
    const rejectedTbody = document.getElementById('val-tbody-rejected');
    if (rejectedTbody) {
        rejectedTbody.innerHTML = '';
        if (rejectedRecords.length === 0) {
            rejectedTbody.innerHTML = '<tr><td colspan="11" style="text-align: center; color: var(--text-secondary); padding: 20px;">No rejected validation records found.</td></tr>';
        } else {
            rejectedRecords.forEach(record => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${getSafe(record.date)}</td>
                    <td>${getSafe(record.dept)}</td>
                    <td>${getSafe(record.sem)}</td>
                    <td>${getSafe(record.div)}</td>
                    <td>${getSafe(record.subject)}</td>
                    <td>${getSafe(record.lecture)}</td>
                    <td>${getAttBadge(record.status)}</td>
                    <td>${getSafe(record.roll)}</td>
                    <td><strong>${getSafe(record.name)}</strong></td>
                    <td>${getSafe(record.faculty)}</td>
                    <td>
                        <div style="font-size: 0.9em; font-weight: 500;">${getSafe(record.date)} ${getSafe(record.time)}</div>
                    </td>
                `;
                rejectedTbody.appendChild(tr);
            });
        }
    }
};

window.submitValidation = async (mode, action, singleId = null) => {
    let ids = [];
    if (mode === 'Single' && singleId !== null) {
        ids.push(singleId);
    }
    
    if (ids.length === 0) return;
    
    try {
        const res = await fetch(`${API_BASE}?request=validate-attendance`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids, action })
        });
        
        if (!res.ok) {
            throw new Error(`Server returned ${res.status}`);
        }
        
        const data = await res.json();
        
        if (data.success) {
            window.showToast(`Successfully ${action} record`, 'success');
            
            // Refresh table
            window.fetchValidationRecords();
            
            // Refresh dashboard stats so the counter updates globally
            if (typeof window.loadDashboardStats === 'function') {
                window.loadDashboardStats();
            }
        } else {
            window.showToast(data.error || 'Validation failed', 'error');
        }
    } catch(err) {
        console.error('Validation submission error:', err);
        window.showToast(`Error: ${err.message}`, 'error');
    }
};
