// app.js
const API_BASE = window.location.protocol === 'file:' ? 'http://localhost/Student-Attendance-Tracking-Portal-/api/index.php' : 'api/index.php';

const commonSem1 = [
    '107001 - Engineering Mathematics-I',
    '107002 - Engineering Physics',
    '102003 - Systems in Mechanical Engineering',
    '103004 - Basic Electrical Engineering',
    '110005 - Programming and Problem Solving',
    '111006 - Workshop Practice'
];

const commonSem2 = [
    '107008 - Engineering Mathematics-II',
    '107009 - Engineering Chemistry',
    '104010 - Basic Electronics Engineering',
    '101011 - Engineering Mechanics',
    '102012 - Engineering Graphics',
    '110013 - Project Based Learning'
];

const subjectData = {
    'CE': {
        'Semester 1': commonSem1,
        'Semester 2': commonSem2,
        'Semester 3': [
            '210241 - Discrete Mathematics',
            '210242 - Fundamentals of Data Structures',
            '210243 - Object Oriented Programming',
            '210244 - Computer Graphics',
            '210245 - Digital Electronics and Logic Design',
            '210246 - Data Structures Laboratory',
            '210247 - OOP and Computer Graphics Laboratory',
            '210248 - Digital Electronics Laboratory'
        ],
        'Semester 4': [
            '207003 - Engineering Mathematics-III',
            '210252 - Data Structures and Algorithms',
            '210253 - Software Engineering',
            '210254 - Microprocessor',
            '210255 - Principles of Programming Languages',
            '210256 - Data Structures and Algorithms Laboratory',
            '210257 - Microprocessor Laboratory',
            '210258 - Project Based Learning II'
        ],
        'Semester 5': [
            '310241 - Database Management Systems',
            '310242 - Theory of Computation',
            '310243 - Systems Programming and Operating System',
            '310244 - Computer Networks and Security',
            '310245 - Elective I',
            '310246 - Database Management Systems Laboratory',
            '310247 - Computer Networks and Security Laboratory',
            '310248 - Laboratory Practice I'
        ],
        'Semester 6': [
            '310251 - Data Science and Big Data Analytics',
            '310252 - Web Technology',
            '310253 - Artificial Intelligence',
            '310254 - Elective II',
            '310255 - Internship',
            '310256 - Data Science and Big Data Analytics Laboratory',
            '310257 - Web Technology Laboratory',
            '310258 - Laboratory Practice II'
        ],
        'Semester 7': [
            '410241 - Design and Analysis of Algorithms',
            '410242 - Machine Learning',
            '410243 - Blockchain Technology',
            '410244 - Elective III',
            '410245 - Elective IV',
            '410246 - Laboratory Practice III',
            '410247 - Laboratory Practice IV',
            '410248 - Project Work Stage I'
        ],
        'Semester 8': [
            '410251 - High Performance Computing',
            '410252 - Deep Learning',
            '410253 - Elective V',
            '410254 - Elective VI',
            '410255 - Laboratory Practice V',
            '410256 - Laboratory Practice VI',
            '410257 - Project Work Stage II'
        ]
    },
    'AIDS': {
        'Semester 1': commonSem1,
        'Semester 2': commonSem2,
        'Semester 3': [
            '210241 - Discrete Mathematics',
            '210242 - Fundamentals of Data Structures',
            '210243 - Object Oriented Programming',
            '210244 - Computer Graphics',
            '210245 - Operating Systems',
            '210246 - Data Structures Laboratory',
            '210247 - OOP and Computer Graphics Laboratory',
            '210248 - Operating Systems Laboratory'
        ],
        'Semester 4': [
            '207003 - Engineering Mathematics-III',
            '210252 - Data Structures and Algorithms',
            '210253 - Software Engineering',
            '214718 - Management Information Systems',
            '214719 - Internet of Things',
            '210256 - Data Structures and Algorithms Laboratory',
            '214720 - Internet of Things Laboratory',
            '214721 - Project Based Learning II'
        ],
        'Semester 5': [
            '310241 - Database Management Systems',
            '310244 - Computer Networks',
            '314716 - Web Technology',
            '314717 - Artificial Intelligence',
            '314718 - Elective I',
            '310246 - Database Management Systems Laboratory',
            '314719 - Computer Networks Laboratory',
            '314720 - Laboratory Practice I'
        ],
        'Semester 6': [
            '310251 - Data Science',
            '314721 - Cyber Security',
            '314722 - Artificial Neural Network',
            '314723 - Elective II',
            '314724 - Internship',
            '310256 - Data Science Laboratory',
            '314725 - Cyber Security Laboratory',
            '314726 - Laboratory Practice II'
        ],
        'Semester 7': [
            '414711 - Deep Learning',
            '414712 - Natural Language Processing',
            '414713 - Software Design and Architecture',
            '414714 - Elective III',
            '414715 - Elective IV',
            '414716 - Laboratory Practice III',
            '414717 - Laboratory Practice IV',
            '414718 - Project Work Stage I'
        ],
        'Semester 8': [
            '414721 - Reinforcement Learning',
            '414722 - IT and Business Management',
            '414723 - Elective V',
            '414724 - Elective VI',
            '414725 - Laboratory Practice V',
            '414726 - Laboratory Practice VI',
            '414727 - Project Work Stage II'
        ]
    },
    'EE': {
        'Semester 1': commonSem1,
        'Semester 2': commonSem2,
        'Semester 3': [
            '203141 - Power Generation Technologies',
            '203142 - Material Science',
            '203143 - Analog and Digital Electronics',
            '203144 - Electrical Measurement and Instrumentation',
            '207006 - Engineering Mathematics-III',
            '203145 - Analog and Digital Electronics Lab',
            '203146 - Electrical Measurement and Instrumentation Lab'
        ],
        'Semester 4': [
            '203147 - Power System-I',
            '203148 - Electrical Machines-I',
            '203149 - Network Analysis',
            '203150 - Numerical Methods and Computer Programming',
            '203151 - Fundamental of Microcontroller and Applications',
            '203152 - Electrical Machines-I Lab',
            '203153 - Network Analysis Lab'
        ],
        'Semester 5': [
            '303141 - Industrial and Technology Management',
            '303142 - Power System-II',
            '303143 - Electrical Machines-II',
            '303144 - Electrical Installation, Maintenance and Testing',
            '303145 - Elective I',
            '303146 - Power System-II Lab',
            '303147 - Electrical Machines-II Lab'
        ],
        'Semester 6': [
            '303148 - Power System and Control',
            '303149 - Microcontroller and its Applications',
            '303150 - Control System-I',
            '303151 - Elective II',
            '303152 - Internship',
            '303153 - Microcontroller and its Applications Lab',
            '303154 - Control System-I Lab'
        ],
        'Semester 7': [
            '403141 - Power System Operation and Control',
            '403142 - PLC and SCADA',
            '403143 - Control System-II',
            '403144 - Elective III',
            '403145 - Elective IV',
            '403146 - PLC and SCADA Lab',
            '403147 - Control System-II Lab',
            '403148 - Project Work Stage I'
        ],
        'Semester 8': [
            '403149 - Switchgear and Protection',
            '403150 - Power Electronic Controlled Drives',
            '403151 - Elective V',
            '403152 - Elective VI',
            '403153 - Switchgear and Protection Lab',
            '403154 - Power Electronic Controlled Drives Lab',
            '403155 - Project Work Stage II'
        ]
    },
    'BT': {
        'Semester 1': commonSem1,
        'Semester 2': commonSem2,
        'Semester 3': [
            '215461 - Engineering Mathematics III',
            '215462 - Cell Biology and Tissue Culture',
            '215463 - Applied Chemistry',
            '215464 - Microbiology and Immunology',
            '215465 - Bioprocess Calculations',
            '215466 - Cell Biology and Tissue Culture Lab',
            '215467 - Applied Chemistry Lab'
        ],
        'Semester 4': [
            '215471 - Biochemistry',
            '215472 - Fluid Mechanics and Heat Transfer',
            '215473 - Molecular Biology',
            '215474 - Material Balances and Stoichiometry',
            '215475 - Introduction to Bioinformatics',
            '215476 - Biochemistry Lab',
            '215477 - Fluid Mechanics and Heat Transfer Lab'
        ],
        'Semester 5': [
            '315461 - Analytical Techniques',
            '315462 - Genetic Engineering',
            '315463 - Fermentation Technology I',
            '315464 - Mass Transfer',
            '315465 - Elective I',
            '315466 - Analytical Techniques Lab',
            '315467 - Genetic Engineering Lab'
        ],
        'Semester 6': [
            '315471 - Fermentation Technology II',
            '315472 - Enzyme Technology',
            '315473 - Plant Engineering',
            '315474 - Elective II',
            '315475 - Internship',
            '315476 - Fermentation Technology II Lab',
            '315477 - Enzyme Technology Lab'
        ],
        'Semester 7': [
            '415461 - Bioseparation Processes',
            '415462 - Biochemical Engineering',
            '415463 - Bioprocess Equipment Design',
            '415464 - Elective III',
            '415465 - Elective IV',
            '415466 - Bioseparation Processes Lab',
            '415467 - Biochemical Engineering Lab',
            '415468 - Project Work Stage I'
        ],
        'Semester 8': [
            '415471 - Bioinformatics',
            '415472 - Bio-therapeutics Technology',
            '415473 - Elective V',
            '415474 - Elective VI',
            '415475 - Bioinformatics Lab',
            '415476 - Bio-therapeutics Technology Lab',
            '415477 - Project Work Stage II'
        ]
    },
    'ME': {
        'Semester 1': commonSem1,
        'Semester 2': commonSem2,
        'Semester 3': [
            '207002 - Engineering Mathematics-III',
            '202041 - Solid Mechanics',
            '202042 - Solid Modeling and Drafting',
            '202043 - Engineering Thermodynamics',
            '202044 - Engineering Materials and Metallurgy',
            '202045 - Solid Mechanics Lab',
            '202046 - Solid Modeling and Drafting Lab'
        ],
        'Semester 4': [
            '202047 - Fluid Mechanics',
            '202048 - Kinematics of Machinery',
            '202049 - Applied Thermodynamics',
            '202050 - Machining Science and Technology',
            '202051 - Fluid Mechanics Lab',
            '202052 - Kinematics of Machinery Lab',
            '202053 - Project Based Learning II'
        ],
        'Semester 5': [
            '302041 - Numerical and Statistical Methods',
            '302042 - Heat and Mass Transfer',
            '302043 - Design of Machine Elements',
            '302044 - Mechatronics',
            '302045 - Elective I',
            '302046 - Heat and Mass Transfer Lab',
            '302047 - Design of Machine Elements Lab'
        ],
        'Semester 6': [
            '302048 - Artificial Intelligence and Machine Learning',
            '302049 - Computer Aided Engineering',
            '302050 - Design of Transmission Systems',
            '302051 - Elective II',
            '302052 - Internship',
            '302053 - Artificial Intelligence and Machine Learning Lab',
            '302054 - Computer Aided Engineering Lab'
        ],
        'Semester 7': [
            '402041 - Heating, Ventilation, Air Conditioning and Refrigeration',
            '402042 - Dynamics of Machinery',
            '402043 - Turbomachinery',
            '402044 - Elective III',
            '402045 - Elective IV',
            '402046 - HVAC&R Lab',
            '402047 - Dynamics of Machinery Lab',
            '402048 - Project Work Stage I'
        ],
        'Semester 8': [
            '402049 - Computer Integrated Manufacturing',
            '402050 - Energy Engineering',
            '402051 - Elective V',
            '402052 - Elective VI',
            '402053 - Computer Integrated Manufacturing Lab',
            '402054 - Energy Engineering Lab',
            '402055 - Project Work Stage II'
        ]
    }
};
window.subjectData = subjectData;

const facultyDB = [
    {
        email: 'smith@college.edu',
        password: 'pass123',
        name: 'Prof. Smith',
        role: 'Computer Science',
        avatar: 'https://ui-avatars.com/api/?name=Prof+Smith&background=0D8ABC&color=fff',
        subjects: ['Data Structures', 'Algorithms']
    },
    {
        email: 'davis@college.edu',
        password: 'pass123',
        name: 'Dr. Davis',
        role: 'Information Tech',
        avatar: 'https://ui-avatars.com/api/?name=Dr+Davis&background=10B981&color=fff',
        subjects: ['Database Management', 'Web Technologies']
    },
    {
        email: 'lee@college.edu',
        password: 'pass123',
        name: 'Prof. Lee',
        role: 'Artificial Intelligence',
        avatar: 'https://ui-avatars.com/api/?name=Prof+Lee&background=4A3AFF&color=fff',
        subjects: ['Machine Learning', 'Artificial Intelligence']
    }
];

let currentUser = facultyDB[0]; // Default to first mock user for UI data

document.addEventListener('DOMContentLoaded', () => {
    // Load User from LocalStorage if exists, else use default mock user
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        try {
            currentUser = JSON.parse(savedUser);
        } catch (e) {
            console.error("Invalid user data", e);
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
        }
    } else {
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
    }

    // Initialization
    initRouter();
    initTopNav();
    
    // Load default view
    navigateTo('dashboard');
});

// --- Routing & View Management ---
function initRouter() {
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            if (item.classList.contains('logout')) return;
            e.preventDefault();
            const viewId = item.getAttribute('data-view');
            navigateTo(viewId);
            
            // Update active state
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');
        });
    });
}

const pageTitles = {
    'dashboard': 'Faculty Dashboard',
    'daily-attendance': 'Daily Attendance Marking',
    'edit-attendance': 'Edit Attendance',
    'attendance-validation': 'Attendance Validation',
    'attendance-history': 'Attendance History',
    'my-profile': 'My Profile',
    'edit-profile': 'Edit Profile',
    'change-password': 'Change Password',
    'settings': 'Settings',
    'search-results': 'Search Results'
};

function navigateTo(viewId) {
    const container = document.getElementById('view-container');
    const template = document.getElementById(`tpl-${viewId}`);
    const pageTitle = document.getElementById('page-title');
    
    if (template) {
        // Clear current content
        container.innerHTML = '';
        
        // Clone template content and append
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
        
        // Update title
        pageTitle.textContent = pageTitles[viewId];
        
        // Populate specific data for the current user
        if (currentUser) {
            // Update Welcome Banner if it exists in this view
            const welcomeTitle = container.querySelector('.welcome-banner h2');
            if (welcomeTitle) welcomeTitle.textContent = `Welcome back, ${currentUser.name}!`;

            // Dynamic subject population is now handled in initViewLogic for each view
        }
        
        // Initialize view specific logic
        initViewLogic(viewId);
    }
}

function initViewLogic(viewId) {
    if (viewId === 'dashboard') {
        loadDashboardStats();
    } else if (viewId === 'daily-attendance') {
        const dateInput = document.getElementById('daily-date');
        if (dateInput) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        const deptSelect = document.getElementById('daily-dept');
        const semSelect = document.getElementById('daily-sem');
        const subjectSelect = document.getElementById('daily-subject');

        const updateSubjects = () => {
            if (!subjectSelect) return;
            const dept = deptSelect ? deptSelect.value : '';
            const sem = semSelect ? semSelect.value : '';
            
            if (dept && sem && dept !== "" && sem !== "") {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                if (subjectData[dept] && subjectData[dept][sem]) {
                    subjectData[dept][sem].forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub;
                        option.textContent = sub;
                        subjectSelect.appendChild(option);
                    });
                }
                subjectSelect.disabled = false;
            } else {
                subjectSelect.innerHTML = '<option value="">Select Department and Semester First</option>';
                subjectSelect.disabled = true;
            }
        };

        updateSubjects();

        if (deptSelect) deptSelect.addEventListener('change', updateSubjects);
        if (semSelect) semSelect.addEventListener('change', updateSubjects);
    }
    
    if (viewId === 'attendance-history') {
        if (window.historyLogic && typeof window.historyLogic.loadRecords === 'function') {
            window.historyLogic.loadRecords();
        } else {
            console.warn('historyLogic not found or loadRecords is not a function');
            const historyTbody = document.getElementById('hist-tbody') || document.getElementById('history-tbody');
            if (historyTbody) {
                historyTbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No attendance records found. (historyLogic missing)</td></tr>';
            }
        }
    }
    
    if (viewId === 'attendance-validation') {
        if (typeof window.fetchValidationRecords === 'function') {
            window.fetchValidationRecords();
        }
    }
    
    if (viewId === 'edit-attendance') {
        const dateInput = document.getElementById('edit-date');
        if (dateInput) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        const deptSelect = document.getElementById('edit-dept');
        const semSelect = document.getElementById('edit-sem');
        const subjectSelect = document.getElementById('edit-subject');

        const updateSubjects = () => {
            if (!subjectSelect) return;
            const dept = deptSelect ? deptSelect.value : '';
            const sem = semSelect ? semSelect.value : '';
            
            if (dept && sem && dept !== "" && sem !== "") {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                let subjects = null;
                
                if (subjectData[dept] && subjectData[dept][sem]) {
                    subjects = subjectData[dept][sem];
                }

                if (subjects && subjects.length > 0) {
                    subjects.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub;
                        option.textContent = sub;
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                } else {
                    subjectSelect.innerHTML = '<option value="">No Subject Available</option>';
                    subjectSelect.disabled = true;
                }
            } else {
                subjectSelect.innerHTML = '<option value="">Select Department and Semester First</option>';
                subjectSelect.disabled = true;
            }
        };

        updateSubjects();
        if (deptSelect) deptSelect.addEventListener('change', updateSubjects);
        if (semSelect) semSelect.addEventListener('change', updateSubjects);


    }
    
    if (viewId === 'my-profile') {
        const user = JSON.parse(localStorage.getItem('currentUser'));
        document.getElementById('my-profile-name').textContent = user.name;
        document.getElementById('my-profile-designation').textContent = user.role;
        document.getElementById('my-profile-id').textContent = user.id || 'FAC1001';
        document.getElementById('my-profile-dept').textContent = user.role;
        document.getElementById('my-profile-email').textContent = user.email;
        document.getElementById('my-profile-mobile').textContent = user.mobile || '+1 234 567 8900';
        document.getElementById('my-profile-img').src = user.avatar;
    }
    
    if (viewId === 'edit-profile') {
        const user = JSON.parse(localStorage.getItem('currentUser'));
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-mobile').value = user.mobile || '';
        document.getElementById('edit-picture').value = user.avatar;
    }
}

// --- Daily Attendance Logic ---
async function loadStudents() {
    const dept = document.getElementById('daily-dept').value;
    const sem = document.getElementById('daily-sem').value;
    const div = document.getElementById('daily-div').value;
    
    if(!dept || !sem || !div) {
        showToast('Please select Department, Semester, and Division', 'error');
        return;
    }
    
    try {
        const res = await fetch(`${API_BASE}?request=students&dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}`, { cache: 'no-store' });
        
        let responseData;
        const contentType = res.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            responseData = await res.json();
            console.log('Exact API Response:', responseData);
        } else {
            const text = await res.text();
            console.log('Exact API Response:', text);
            throw new Error(`Invalid API response: ${text.substring(0, 50)}...`);
        }
        
        if (!res.ok) {
            throw new Error(responseData.error || `HTTP Error: ${res.status}`);
        }
        
        if (responseData.error) {
            throw new Error(responseData.error);
        }
        
        const students = responseData;
        
        const container = document.getElementById('student-list-container');
        const tbody = document.getElementById('attendance-tbody');
        
        tbody.innerHTML = '';
        
        if (!Array.isArray(students) || students.length === 0) {
            showToast('No students found for this class', 'warning');
            container.style.display = 'none';
            return;
        }
        
        students.forEach(student => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${student.roll}</td>
                <td>
                    <div class="student-info">
                        <div class="student-avatar">${student.name.charAt(0)}</div>
                        <span>${student.name}</span>
                    </div>
                </td>
                <td>
                    <div class="switch-container">
                        <button class="switch-btn active present" onclick="toggleStatus(this, 'present')">Present</button>
                        <button class="switch-btn" onclick="toggleStatus(this, 'absent')">Absent</button>
                        <button class="switch-btn" onclick="toggleStatus(this, 'late')">Late</button>
                        <button class="switch-btn" onclick="toggleStatus(this, 'leave')">Leave</button>
                    </div>
                </td>
                <td>
                    <input type="text" class="glass-input" style="padding: 6px 12px; width: 150px;" placeholder="Optional remark">
                </td>
            `;
            tbody.appendChild(tr);
        });
        
        container.style.display = 'block';
        container.classList.add('fade-in');
        
        showToast('Students loaded successfully', 'success');
    } catch(err) {
        showToast(err.message, 'error');
        console.error('Student fetch error:', err);
    }
}

function toggleStatus(btn, status) {
    const container = btn.parentElement;
    const buttons = container.querySelectorAll('.switch-btn');
    
    buttons.forEach(b => {
        b.classList.remove('active', 'present', 'absent', 'late', 'leave');
    });
    
    btn.classList.add('active', status);
}

function markBulk(status) {
    const switches = document.querySelectorAll('.switch-container');
    switches.forEach(container => {
        const buttons = container.querySelectorAll('.switch-btn');
        buttons.forEach(b => b.classList.remove('active', 'present', 'absent', 'late', 'leave'));
        
        if (status === 'present') {
            container.children[0].classList.add('active', 'present');
        } else if (status === 'absent') {
            container.children[1].classList.add('active', 'absent');
        }
    });
}

async function saveAttendance() {
    const date = document.getElementById('daily-date').value;
    const dept = document.getElementById('daily-dept').value;
    const sem = document.getElementById('daily-sem').value;
    const div = document.getElementById('daily-div').value;
    const subject = document.getElementById('daily-subject').value;
    const lecture = document.getElementById('daily-lecture').value;

    if (!date || !dept || !sem || !div || !subject || !lecture) {
        showToast('Please fill all fields before saving', 'error');
        return;
    }

    const tbody = document.getElementById('attendance-tbody');
    const rows = tbody.querySelectorAll('tr');
    if (rows.length === 0) {
        showToast('No students loaded to mark attendance', 'error');
        return;
    }

    const attendanceData = {};
    const remarksData = {};

    rows.forEach(tr => {
        const roll = tr.cells[0].textContent;
        const activeBtn = tr.querySelector('.switch-btn.active');
        const status = activeBtn ? (
            activeBtn.classList.contains('present') ? 'present' :
            activeBtn.classList.contains('absent') ? 'absent' :
            activeBtn.classList.contains('late') ? 'late' :
            activeBtn.classList.contains('leave') ? 'on leave' : 'absent'
        ) : 'absent';
        
        const remarkInput = tr.querySelector('input[type="text"]');
        const remark = remarkInput ? remarkInput.value : '';

        attendanceData[roll] = status;
        if (remark) remarksData[roll] = remark;
    });

    const payload = {
        date, dept, sem, div, subject, lectNo: lecture,
        attendance: attendanceData,
        remarks: remarksData
    };

    try {
        showToast('Saving attendance...', 'info');
        const res = await fetch(`${API_BASE}?request=attendance`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        
        if (res.ok && result.success) {
            showToast('Attendance saved successfully!', 'success');
        } else {
            showToast(result.error || 'Failed to save attendance', 'error');
        }
    } catch(err) {
        showToast('Network error while saving attendance', 'error');
        console.error(err);
    }
}

// --- Edit Attendance Logic ---
async function searchAttendance() {
    const container = document.getElementById('edit-results');
    const tbody = document.getElementById('edit-tbody');
    
    const date = document.getElementById('edit-date').value;
    const dept = document.getElementById('edit-dept').value;
    const subject = document.getElementById('edit-subject').value;
    const lecture = document.getElementById('edit-lecture').value;
    
    if(!date || !dept || !subject || !lecture) {
        showToast('Please fill all fields to search', 'error');
        return;
    }
    
    try {
        const res = await fetch(`${API_BASE}?request=attendance&date=${encodeURIComponent(date)}&dept=${encodeURIComponent(dept)}&subject=${encodeURIComponent(subject)}&lectNo=${encodeURIComponent(lecture)}`, { cache: 'no-store' });
        
        if (!res.ok) {
            const result = await res.json().catch(() => ({}));
            showToast(result.error || 'No records found to edit', 'error');
            return;
        }
        
        const records = await res.json();
        
        tbody.innerHTML = '';
        records.forEach(student => {
            const tr = document.createElement('tr');
            tr.dataset.recordId = student.id;
            tr.dataset.originalStatus = student.status;
            
            tr.innerHTML = `
                <td>${student.roll}</td>
                <td>
                    <div class="student-info">
                        <div class="student-avatar">${student.studentName.charAt(0)}</div>
                        <span>${student.studentName}</span>
                    </div>
                </td>
                <td><span class="status-badge pending" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">${student.status}</span></td>
                <td>
                    <select class="glass-input status-select" style="padding: 6px 12px;">
                        <option value="Present" ${student.status === 'present' ? 'selected' : ''}>Present</option>
                        <option value="Absent" ${student.status === 'absent' ? 'selected' : ''}>Absent</option>
                        <option value="Late" ${student.status === 'late' ? 'selected' : ''}>Late</option>
                        <option value="Leave" ${student.status === 'on leave' ? 'selected' : ''}>Leave</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="glass-input edit-reason" style="padding: 6px 12px;" placeholder="Reason for change" value="">
                </td>
            `;
            tbody.appendChild(tr);
        });
        
        container.style.display = 'block';
        container.classList.add('fade-in');
    } catch(err) {
        showToast('Error searching records', 'error');
        console.error(err);
    }
}

async function submitEdit() {
    const tbody = document.getElementById('edit-tbody');
    const rows = tbody.querySelectorAll('tr');
    const requests = [];
    
    rows.forEach(tr => {
        const recordId = tr.dataset.recordId;
        const originalStatus = tr.dataset.originalStatus;
        const newStatus = tr.querySelector('.status-select').value.toLowerCase();
        const reason = tr.querySelector('.edit-reason').value;
        
        // Only submit if status changed
        if (originalStatus !== newStatus && reason.trim() !== '') {
            requests.push({
                recordId,
                originalStatus,
                newStatus,
                reason
            });
        }
    });
    
    if (requests.length === 0) {
        showToast('No valid changes with reasons found.', 'warning');
        return;
    }
    
    try {
        const res = await fetch(`${API_BASE}?request=validation-requests`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ requests })
        });
        const result = await res.json();
        
        if (res.ok && result.success) {
            showToast('Edit requests submitted for validation', 'success');
            // Reset table view
            document.getElementById('edit-results').style.display = 'none';
        } else {
            showToast(result.error || 'Failed to submit requests', 'error');
        }
    } catch(err) {
        showToast('Network error while submitting edits', 'error');
        console.error(err);
    }
}

// --- UI Utilities ---
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.innerHTML = `
        <i class="fa-solid ${icon}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// --- Top Navigation & Profile Features ---

function initTopNav() {
    updateTopNavProfile();
    initDropdowns();
    initSearch();
    renderNotifications();
}

function updateTopNavProfile() {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    if (!user) return;
    const nameEl = document.getElementById('nav-profile-name');
    const roleEl = document.getElementById('nav-profile-role');
    const imgEl = document.getElementById('nav-profile-img');
    
    if(nameEl) nameEl.textContent = user.name;
    if(roleEl) roleEl.textContent = user.role;
    if(imgEl) imgEl.src = user.avatar;
}

function initDropdowns() {
    const profileToggle = document.getElementById('profile-toggle');
    const profileMenu = document.getElementById('profile-menu');
    const notifToggle = document.getElementById('notification-toggle');
    const notifDropdown = document.getElementById('notification-dropdown');
    
    // Toggle Profile
    if(profileToggle && profileMenu) {
        profileToggle.addEventListener('click', (e) => {
            if (e.target.closest('.dropdown-panel')) return;
            e.stopPropagation();
            profileMenu.classList.toggle('show');
            if(notifDropdown) notifDropdown.classList.remove('show');
        });
    }
    
    // Toggle Notifications
    if(notifToggle && notifDropdown) {
        notifToggle.addEventListener('click', (e) => {
            if (e.target.closest('.dropdown-panel')) return;
            e.stopPropagation();
            notifDropdown.classList.toggle('show');
            if(profileMenu) profileMenu.classList.remove('show');
            
            if (notifDropdown.classList.contains('show')) {
                markAllNotificationsRead();
            }
        });
    }
    
    // Close dropdowns on outside click
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#profile-toggle') && profileMenu) {
            profileMenu.classList.remove('show');
        }
        if (!e.target.closest('#notification-toggle') && notifDropdown) {
            notifDropdown.classList.remove('show');
        }
        if (!e.target.closest('#global-search-container')) {
            const panel = document.getElementById('search-suggestions');
            if(panel) panel.classList.remove('show');
        }
    });

    if(profileMenu) {
        profileMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (item.classList.contains('text-danger')) return;
                e.preventDefault();
                const viewId = item.getAttribute('data-view');
                if (viewId) {
                    navigateTo(viewId);
                }
                profileMenu.classList.remove('show');
            });
        });
    }
}

// --- Notifications ---
function getNotifications() {
    return JSON.parse(localStorage.getItem('notifications')) || [];
}

function saveNotifications(notifs) {
    localStorage.setItem('notifications', JSON.stringify(notifs));
    renderNotifications();
}

function addNotification(title, message) {
    const notifs = getNotifications();
    notifs.unshift({
        id: Date.now().toString(),
        title,
        message,
        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
        read: false
    });
    saveNotifications(notifs);
}

function renderNotifications() {
    const notifs = getNotifications();
    const list = document.getElementById('notification-list');
    const badge = document.getElementById('notification-badge');
    
    if(!list || !badge) return;
    
    list.innerHTML = '';
    const unreadCount = notifs.filter(n => !n.read).length;
    
    if (unreadCount > 0) {
        badge.textContent = unreadCount;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    
    if (notifs.length === 0) {
        list.innerHTML = '<div style="padding: 15px; text-align: center; color: var(--text-secondary);">No notifications</div>';
        return;
    }
    
    notifs.forEach(n => {
        const item = document.createElement('div');
        item.className = `notification-item ${n.read ? '' : 'unread'}`;
        item.innerHTML = `
            <div class="notif-title">${n.title}</div>
            <div style="font-size: 0.85rem;">${n.message}</div>
            <div class="notif-time">${n.time}</div>
        `;
        list.appendChild(item);
    });
}

function markAllNotificationsRead() {
    const notifs = getNotifications();
    let changed = false;
    notifs.forEach(n => {
        if (!n.read) {
            n.read = true;
            changed = true;
        }
    });
    if (changed) saveNotifications(notifs);
}

window.clearAllNotifications = function() {
    saveNotifications([]);
    showToast('Notifications cleared');
}

function initSearch() {
    const input = document.getElementById('global-search-input');
    const panel = document.getElementById('search-suggestions');
    if(!input || !panel) return;
    
    input.addEventListener('input', async (e) => {
        const query = e.target.value.toLowerCase().trim();
        if (query.length < 2) {
            panel.style.display = 'none';
            return;
        }
        
        const results = await performSearch(query, true);
        renderSearchSuggestions(results);
        panel.classList.add('show');
    });
    
    input.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            const query = e.target.value.trim();
            if (query) {
                panel.classList.remove('show');
                showSearchResultsView(query);
            }
        }
    });
}

async function performSearch(query, limit = false) {
    let history = [];
    try {
        const res = await fetch(`${API_BASE}?request=history`);
        if (res.ok) {
            history = await res.json();
        }
    } catch (e) {
        console.error('Search error', e);
    }
    let results = [];
    
    history.forEach(record => {
        if (record.subject.toLowerCase().includes(query) || 
            record.dept.toLowerCase().includes(query) || 
            record.sem.toLowerCase().includes(query)) {
            results.push({ type: 'Class Record', details: `${record.subject} (${record.sem}) - ${record.date}` });
        }
        
        if(record.students) {
            record.students.forEach(student => {
                if (student.name.toLowerCase().includes(query) || student.roll.toLowerCase().includes(query)) {
                    results.push({ type: 'Student', details: `${student.name} (Roll: ${student.roll}) in ${record.subject}` });
                }
            });
        }
    });
    
    return limit ? results.slice(0, 5) : results;
}

function renderSearchSuggestions(results) {
    const panel = document.getElementById('search-suggestions');
    if(!panel) return;
    panel.innerHTML = '';
    
    if (results.length === 0) {
        panel.innerHTML = '<div style="padding: 12px; color: var(--text-secondary); font-size: 0.9rem;">No results found</div>';
        return;
    }
    
    results.forEach(res => {
        const item = document.createElement('div');
        item.className = 'search-suggestion-item';
        item.innerHTML = `<strong>${res.type}:</strong> ${res.details}`;
        item.addEventListener('click', () => {
            document.getElementById('global-search-input').value = res.details;
            panel.classList.remove('show');
        });
        panel.appendChild(item);
    });
}

async function showSearchResultsView(query) {
    navigateTo('search-results');
    document.getElementById('search-query-display').textContent = query;
    const tbody = document.getElementById('search-results-tbody');
    
    const allResults = await performSearch(query.toLowerCase());
    
    tbody.innerHTML = '';
    if (allResults.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">No results found for your query.</td></tr>';
        return;
    }
    
    allResults.forEach(res => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><span class="badge" style="background: rgba(255,255,255,0.1);">${res.type}</span></td>
            <td>${res.details}</td>
            <td><button class="btn btn-sm btn-outline">View</button></td>
        `;
        tbody.appendChild(tr);
    });
}

// --- Edit Attendance Extended Logic ---
let editCurrentFilters = {};
let editLoadedStudents = [];
let editHistoryStack = [];
let editPage = 1;
let editLimit = 10;

window.resetEditFilters = function() {
    document.getElementById('edit-dept').value = '';
    document.getElementById('edit-div').value = '';
    document.getElementById('edit-sem').value = '';
    document.getElementById('edit-lecture').value = '';
    document.getElementById('edit-date').value = new Date().toISOString().split('T')[0];
    
    const subjectSelect = document.getElementById('edit-subject');
    subjectSelect.innerHTML = '<option value="">Select Department and Semester First</option>';
    subjectSelect.disabled = true;
    
    document.getElementById('edit-workspace-main').style.display = 'none';
}

window.searchEditAttendance = async function() {
    const date = document.getElementById('edit-date').value;
    const dept = document.getElementById('edit-dept').value;
    const div = document.getElementById('edit-div').value; 
    const sem = document.getElementById('edit-sem').value;
    const subject = document.getElementById('edit-subject').value;
    const lecture = document.getElementById('edit-lecture').value;

    if (!date || !dept || !sem || !subject || !lecture) {
        alert('Validation Error: Please select Date, Department, Semester, Subject, and Lecture Number.');
        return;
    }

    editCurrentFilters = { date, dept, div, sem, subject, lecture };
    
    try {
        const res = await fetch(`${API_BASE}?request=attendance&date=${encodeURIComponent(date)}&dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}&subject=${encodeURIComponent(subject)}&lectNo=${encodeURIComponent(lecture)}`);
        
        if (!res.ok) {
            const result = await res.json().catch(() => ({}));
            alert(result.error || 'No records found for the selected criteria.');
            document.getElementById('edit-workspace-main').style.display = 'none';
            return;
        }
        
        const records = await res.json();
        
        editLoadedStudents = records.map((student) => ({
            id: student.id,
            roll: student.roll,
            name: student.studentName,
            dept: dept,
            div: div || 'A',
            sem: sem,
            subject: subject,
            lecture: lecture,
            status: student.status === 'present' ? 'Present' : 
                    student.status === 'absent' ? 'Absent' : 
                    student.status === 'late' ? 'Late' : 
                    student.status === 'on leave' ? 'On-Leave' : 'Not-Marked',
            remarks: student.remarks || '',
            date: date,
            updatedBy: (typeof currentUser !== 'undefined' && currentUser) ? currentUser.name : 'Unknown',
            updatedTime: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
        }));
        
        editHistoryStack = [];
        editPage = 1;
        document.getElementById('edit-workspace-main').style.display = 'block';
        
        renderEditTable();
        updateEditSummaries();
        if(typeof renderModHistory === 'function') renderModHistory();
        
    } catch(err) {
        alert('Error fetching attendance records from database.');
        console.error(err);
    }
}

window.renderEditTable = function() {
    const tbody = document.getElementById('edit-attendance-tbody');
    tbody.innerHTML = '';
    
    const start = (editPage - 1) * editLimit;
    const end = start + editLimit;
    const paginated = editLoadedStudents.slice(start, end);
    
    paginated.forEach((student, index) => {
        const globalIndex = start + index;
        const tr = document.createElement('tr');
        
        let statusOptions = ['Present', 'Absent', 'Late', 'On-Leave', 'Not-Marked'].map(opt => {
            const display = opt.replace('-', ' ');
            return `<option value="${opt}" ${student.status.replace(' ', '-') === opt ? 'selected' : ''}>${display}</option>`;
        }).join('');

        tr.innerHTML = `
            <td>
                <label class="custom-checkbox">
                    <input type="checkbox" class="edit-student-chk" data-index="${globalIndex}">
                    <span class="checkmark"></span>
                </label>
            </td>
            <td>${student.roll}</td>
            <td>${student.name}</td>
            <td>${student.dept}</td>
            <td>${student.div}</td>
            <td>${student.sem}</td>
            <td style="white-space: normal; word-wrap: break-word; min-width: 160px; max-width: 220px;">${student.subject}</td>
            <td>${student.lecture}</td>
            <td>
                <select class="status-select ${student.status.replace(' ', '-')}" onchange="changeSingleStudentStatus(${globalIndex}, this.value, this)">
                    ${statusOptions}
                </select>
            </td>
            <td>
                <input type="text" class="glass-input edit-remarks-input" style="width: 150px; padding: 4px;" value="${student.remarks}" onchange="changeSingleStudentRemark(${globalIndex}, this.value)">
            </td>
            <td>${student.date}</td>
            <td>${student.updatedBy}</td>
            <td>${student.updatedTime}</td>
        `;
        tbody.appendChild(tr);
    });
    
    document.getElementById('edit-page-start').textContent = start + 1;
    document.getElementById('edit-page-end').textContent = Math.min(end, editLoadedStudents.length);
    document.getElementById('edit-total-students').textContent = editLoadedStudents.length;
    
    document.getElementById('edit-top-page-start').textContent = start + 1;
    document.getElementById('edit-top-page-end').textContent = Math.min(end, editLoadedStudents.length);
    document.getElementById('edit-top-total-students').textContent = editLoadedStudents.length;
    
    document.getElementById('edit-select-all').checked = false;
    renderPaginationControls();
}

window.changeEditRowsPerPage = function(val) {
    editLimit = parseInt(val);
    editPage = 1;
    renderEditTable();
}

window.renderPaginationControls = function() {
    const container = document.getElementById('edit-pagination');
    container.innerHTML = '';
    
    const totalPages = Math.ceil(editLoadedStudents.length / editLimit);
    if(totalPages <= 1) return;
    
    const prev = document.createElement('button');
    prev.className = 'page-btn';
    prev.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
    prev.disabled = editPage === 1;
    prev.onclick = () => { editPage--; renderEditTable(); };
    container.appendChild(prev);
    
    const addPageBtn = (pageNum) => {
        const btn = document.createElement('button');
        btn.className = 'page-btn' + (pageNum === editPage ? ' active' : '');
        btn.textContent = pageNum;
        btn.onclick = () => { editPage = pageNum; renderEditTable(); };
        container.appendChild(btn);
    };

    const addDots = () => {
        const span = document.createElement('span');
        span.className = 'page-dots text-muted';
        span.style.padding = '0 5px';
        span.textContent = '...';
        container.appendChild(span);
    };
    
    if (totalPages <= 5) {
        for (let i = 1; i <= totalPages; i++) {
            addPageBtn(i);
        }
    } else {
        if (editPage <= 3) {
            for (let i = 1; i <= 3; i++) addPageBtn(i);
            addDots();
            addPageBtn(totalPages - 1);
            addPageBtn(totalPages);
        } else if (editPage >= totalPages - 2) {
            addPageBtn(1);
            addPageBtn(2);
            addDots();
            for (let i = totalPages - 2; i <= totalPages; i++) addPageBtn(i);
        } else {
            addPageBtn(1);
            addDots();
            addPageBtn(editPage - 1);
            addPageBtn(editPage);
            addPageBtn(editPage + 1);
            addDots();
            addPageBtn(totalPages);
        }
    }
    
    const next = document.createElement('button');
    next.className = 'page-btn';
    next.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
    next.disabled = editPage === totalPages;
    next.onclick = () => { editPage++; renderEditTable(); };
    container.appendChild(next);
}

window.toggleEditSelectAll = function() {
    const master = document.getElementById('edit-select-all').checked;
    document.querySelectorAll('.edit-student-chk').forEach(chk => {
        chk.checked = master;
    });
}

window.changeSingleStudentStatus = function(index, newStatus, selectElement) {
    const student = editLoadedStudents[index];
    const oldStatus = student.status;
    if(oldStatus === newStatus) return;
    
    pushEditHistory([{
        index, oldStatus, newStatus, student
    }]);
    
    student.status = newStatus;
    student.updatedTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    selectElement.className = 'status-select ' + newStatus.replace(' ', '-');
    updateEditSummaries();
    logModification(student, oldStatus, newStatus);
}

window.bulkActionEdit = function(newStatus) {
    const selected = document.querySelectorAll('.edit-student-chk:checked');
    if (selected.length === 0) {
        alert('Select at least one student.');
        return;
    }
    
    const changes = [];
    selected.forEach(chk => {
        const idx = parseInt(chk.getAttribute('data-index'));
        const student = editLoadedStudents[idx];
        if (student.status !== newStatus) {
            changes.push({
                index: idx,
                oldStatus: student.status,
                newStatus: newStatus,
                student: student
            });
            student.status = newStatus;
            student.updatedTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            logModification(student, changes[changes.length-1].oldStatus, newStatus);
        }
    });
    
    if (changes.length > 0) {
        pushEditHistory(changes);
        renderEditTable();
        updateEditSummaries();
    }
}

window.changeSingleStudentRemark = function(index, newRemark) {
    const student = editLoadedStudents[index];
    if (student.remarks !== newRemark) {
        pushEditHistory([{
            index: index,
            oldStatus: student.status,
            newStatus: student.status,
            oldRemark: student.remarks,
            newRemark: newRemark,
            student: student
        }]);
        student.remarks = newRemark;
        student.updatedTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        // Optionally update summaries or log modification if needed
    }
}

window.pushEditHistory = function(changes) {
    editHistoryStack.push(changes);
}

window.undoLastEditChange = function() {
    if (editHistoryStack.length === 0) {
        alert('No recent changes to undo.');
        return;
    }
    
    const lastChanges = editHistoryStack.pop();
    lastChanges.forEach(change => {
        editLoadedStudents[change.index].status = change.oldStatus;
        if (change.oldRemark !== undefined) {
            editLoadedStudents[change.index].remarks = change.oldRemark;
        }
    });
    
    renderEditTable();
    updateEditSummaries();
}

window.saveEditChanges = async function() {
    if (editLoadedStudents.length === 0) {
        alert('No data to save.');
        return;
    }
    
    if (editHistoryStack.length === 0) {
        alert('No changes made to save.');
        return;
    }
    
    const requests = [];
    const changeMap = new Map();
    
    editHistoryStack.forEach(changes => {
        changes.forEach(change => {
            if (!changeMap.has(change.index)) {
                changeMap.set(change.index, {
                    originalStatus: change.oldStatus,
                    currentStatus: change.newStatus,
                    student: change.student
                });
            } else {
                changeMap.get(change.index).currentStatus = change.newStatus;
            }
        });
    });
    
    changeMap.forEach(info => {
        if (info.originalStatus !== info.currentStatus || info.student.remarks !== info.originalRemark) {
            const toDbStatus = (status) => {
                if (status === 'Present') return 'present';
                if (status === 'Absent') return 'absent';
                if (status === 'Late') return 'late';
                if (status === 'On-Leave') return 'on leave';
                return 'absent';
            };
            
            let reason = prompt(`Reason for changing ${info.student.name} (${info.student.roll}) from ${info.originalStatus} to ${info.currentStatus}:`);
            if (reason === null || reason.trim() === '') {
                reason = "Correction by faculty";
            }
            
            requests.push({
                recordId: info.student.id,
                originalStatus: toDbStatus(info.originalStatus),
                newStatus: toDbStatus(info.currentStatus),
                remarks: info.student.remarks,
                reason: reason
            });
        }
    });
    
    if (requests.length === 0) {
        alert('No actual changes left to save.');
        return;
    }
    
    showToast('Saving edits directly to database...', 'info');
    try {
        const res = await fetch(`${API_BASE}?request=attendance-edit-direct`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ requests })
        });
        const result = await res.json();
        
        if (res.ok && result.success) {
            alert('Attendance records updated successfully!');
            editHistoryStack = [];
            searchEditAttendance();
        } else {
            alert(result.error || 'Failed to update attendance records.');
        }
    } catch(err) {
        alert('Network error while saving edits.');
        console.error(err);
    }
}

window.cancelEditChanges = function() {
    if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
        searchEditAttendance();
    }
}

window.updateEditSummaries = function() {
    const total = editLoadedStudents.length;
    const present = editLoadedStudents.filter(s => s.status === 'Present').length;
    const absent = editLoadedStudents.filter(s => s.status === 'Absent').length;
    const pct = total > 0 ? ((present / total) * 100).toFixed(2) : 0;
    
    document.getElementById('edit-summary-total').textContent = total;
    document.getElementById('edit-summary-present').textContent = present;
    document.getElementById('edit-summary-absent').textContent = absent;
    document.getElementById('edit-summary-percent').textContent = pct + '%';
}

// Validation Action Handler
window.handleValidationAction = async function(action, recordId) {
    if (action === 'view') {
        alert('Viewing details is not fully implemented in this demo.');
        return;
    }

    try {
        const res = await fetch(`${API_BASE}?request=validation-requests/${recordId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: action === 'approve' ? 'approved' : 'rejected'
            })
        });
        
        const result = await res.json();
        if (res.ok && result.success) {
            showToast(`Validation request ${action}d successfully.`, 'success');
            if(window.renderValidationList) window.renderValidationList();
        } else {
            showToast(result.error || 'Failed to update validation request.', 'error');
        }
    } catch(err) {
        showToast('Network error while updating validation.', 'error');
        console.error(err);
    }
}

window.toggleExportDropdown = function(event) {
    if(event) {
        event.stopPropagation();
    }
    const dropdown = document.getElementById('export-dropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('export-dropdown');
    const button = document.querySelector('.dropdown-container button');
    
    if (dropdown && dropdown.classList.contains('show')) {
        if (!dropdown.contains(event.target) && event.target !== button && !button.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
});

window.exportAttendance = function(format) {
    document.getElementById('export-dropdown').classList.remove('show');
    if (editLoadedStudents.length === 0) {
        alert('No data to export.');
        return;
    }
    
    // Calculate summary
    const total = editLoadedStudents.length;
    const present = editLoadedStudents.filter(s => s.status === 'Present').length;
    const absent = editLoadedStudents.filter(s => s.status === 'Absent').length;
    const late = editLoadedStudents.filter(s => s.status === 'Late').length;
    const leave = editLoadedStudents.filter(s => s.status === 'On Leave').length;
    const notMarked = editLoadedStudents.filter(s => !s.status || s.status === 'Not Marked').length;
    const pct = total > 0 ? ((present / total) * 100).toFixed(2) : 0;
    
    const summaryData = [
        ['Total Students', total],
        ['Present', present],
        ['Absent', absent],
        ['Late', late],
        ['On Leave', leave],
        ['Not Marked', notMarked],
        ['Attendance Percentage', pct + '%']
    ];

    if (format === 'pdf') {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            alert('PDF library not loaded. Please try again later.');
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        // Title
        doc.setFontSize(18);
        doc.text('Faculty Attendance Portal', 14, 20);
        
        // Header Info
        doc.setFontSize(11);
        doc.text(`Date of Export: ${new Date().toLocaleDateString()}`, 14, 30);
        doc.text(`Faculty Name: ${currentUser.name}`, 14, 37);
        doc.text(`Total Students: ${total}`, 14, 44);
        
        // Summary Table
        doc.autoTable({
            startY: 50,
            head: [['Summary', 'Value']],
            body: summaryData,
            theme: 'grid',
            headStyles: { fillColor: [59, 130, 246] },
            margin: { top: 10 },
            tableWidth: 100
        });

        // Data Table
        const tableData = editLoadedStudents.map(s => [
            s.roll, s.name, s.dept, s.div, s.sem, s.subject, s.lecture, s.status, s.date, s.updatedBy, s.updatedTime
        ]);
        
        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 15,
            head: [['Roll No', 'Name', 'Dept', 'Div', 'Sem', 'Subject', 'Lecture', 'Status', 'Date', 'Updated By', 'Time']],
            body: tableData,
            theme: 'grid',
            headStyles: { fillColor: [74, 58, 255] },
            styles: { fontSize: 8 }
        });
        
        doc.save('Attendance_Export.pdf');
    } else if (format === 'excel') {
        if (!window.XLSX) {
            alert('Excel library not loaded.');
            return;
        }
        const wb = XLSX.utils.book_new();
        
        // Summary sheet
        const wsSummary = XLSX.utils.aoa_to_sheet([['Summary', 'Value'], ...summaryData, [], ['Faculty Name', currentUser.name], ['Export Date', new Date().toLocaleDateString()]]);
        XLSX.utils.book_append_sheet(wb, wsSummary, 'Summary');
        
        // Data sheet
        const dataHeader = ['Roll No', 'Name', 'Department', 'Division', 'Semester', 'Subject', 'Lecture', 'Status', 'Date', 'Last Updated By', 'Last Updated Time'];
        const dataRows = editLoadedStudents.map(s => [s.roll, s.name, s.dept, s.div, s.sem, s.subject, s.lecture, s.status, s.date, s.updatedBy, s.updatedTime]);
        
        const wsData = XLSX.utils.aoa_to_sheet([dataHeader, ...dataRows]);
        XLSX.utils.book_append_sheet(wb, wsData, 'Attendance Records');
        
        XLSX.writeFile(wb, 'Attendance_Export.xlsx');
    } else if (format === 'csv') {
        const dataHeader = ['Roll No', 'Name', 'Department', 'Division', 'Semester', 'Subject', 'Lecture', 'Status', 'Date', 'Last Updated By', 'Last Updated Time'];
        const dataRows = editLoadedStudents.map(s => [s.roll, s.name, s.dept, s.div, s.sem, s.subject, s.lecture, s.status, s.date, s.updatedBy, s.updatedTime]);
        
        const csvRows = [dataHeader, ...dataRows].map(row => 
            row.map(cell => {
                const cellStr = String(cell).replace(/"/g, '""');
                return `"${cellStr}"`;
            }).join(",")
        );
        
        const csvContent = "\uFEFF" + csvRows.join("\n");
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", "Attendance_Export.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
}

window.logModification = function(student, oldStatus, newStatus) {
    const tbody = document.getElementById('mod-history-tbody');
    if (!tbody) return;
    const tr = document.createElement('tr');
    const timestamp = new Date().toLocaleTimeString();
    
    tr.innerHTML = `
        <td>${student.roll}</td>
        <td>${student.name}</td>
        <td>${student.subject}</td>
        <td>${student.dept}</td>
        <td>${student.div}</td>
        <td>${editCurrentFilters.sem}</td>
        <td>${student.lecture}</td>
        <td><span class="status-badge ${oldStatus.toLowerCase()}">${oldStatus}</span></td>
        <td><span class="status-badge ${newStatus.toLowerCase()}">${newStatus}</span></td>
        <td>${currentUser.name}</td>
        <td>${student.date}</td>
        <td>${timestamp}</td>
    `;
    
    tbody.insertBefore(tr, tbody.firstChild);
}

window.renderModHistory = function() {
    const tbody = document.getElementById('mod-history-tbody');
    if (tbody) tbody.innerHTML = '';
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-container')) {
        const drop = document.getElementById('export-dropdown');
        if(drop) drop.style.display = 'none';
    }
});

// --- Profile Forms ---
window.saveProfile = function(e) {
    e.preventDefault();
    const user = JSON.parse(localStorage.getItem('currentUser'));
    user.name = document.getElementById('edit-name').value;
    user.email = document.getElementById('edit-email').value;
    user.mobile = document.getElementById('edit-mobile').value;
    
    const newPic = document.getElementById('edit-picture').value;
    if (newPic) user.avatar = newPic;
    
    localStorage.setItem('currentUser', JSON.stringify(user));
    updateTopNavProfile();
    showToast('Profile updated successfully');
    addNotification('Profile Updated', 'Your profile details were updated.');
    navigateTo('my-profile');
}

window.changePassword = function(e) {
    e.preventDefault();
    const current = document.getElementById('pwd-current').value;
    const newPwd = document.getElementById('pwd-new').value;
    const confirmPwd = document.getElementById('pwd-confirm').value;
    
    if (newPwd !== confirmPwd) {
        showToast('New passwords do not match!', 'error');
        return;
    }
    
    const user = JSON.parse(localStorage.getItem('currentUser'));
    if (current !== user.password) {
        showToast('Current password is incorrect', 'error');
        return;
    }
    
    user.password = newPwd;
    localStorage.setItem('currentUser', JSON.stringify(user));
    showToast('Password changed successfully');
    addNotification('Security Alert', 'Your password was changed.');
    document.getElementById('change-password-form').reset();
    navigateTo('my-profile');
}

window.logout = function(e) {
    if (e) e.preventDefault();
    
    // Clear stored data
    localStorage.removeItem('currentUser');
    localStorage.removeItem('attendanceHistory');
    localStorage.removeItem('notifications');
    
    // Redirect to login
    window.location.href = 'login.php';
}

async function loadDashboardStats() {
    try {
        const res = await fetch(`${API_BASE}?request=dashboard-stats`);
        const result = await res.json();
        
        if (res.ok) {
            const elTotalStudents = document.getElementById('dash-total-students');
            const elClassesToday = document.getElementById('dash-classes-today');
            const elAvgAttendance = document.getElementById('dash-avg-attendance');
            const elPendingValidation = document.getElementById('dash-pending-validation');
            const elScheduleList = document.getElementById('dash-schedule-list');

            if(elTotalStudents) elTotalStudents.textContent = result.totalStudents || 0;
            if(elClassesToday) elClassesToday.textContent = result.classesToday || 0;
            if(elAvgAttendance) elAvgAttendance.textContent = (result.avgAttendance || 0) + '%';
            if(elPendingValidation) elPendingValidation.textContent = result.pendingValidation || 0;

            if(elScheduleList) {
                elScheduleList.innerHTML = '';
                if(result.schedule && result.schedule.length > 0) {
                    result.schedule.forEach(item => {
                        const statusClass = item.status === 'Completed' ? 'completed' : 'pending';
                        elScheduleList.innerHTML += `
                            <div class="schedule-item">
                                <div class="time-block">${item.timeBlock}</div>
                                <div class="class-info">
                                    <h4>${item.subjectName}</h4>
                                    <span>${item.classInfo}</span>
                                </div>
                                <div class="status-badge ${statusClass}">${item.status}</div>
                            </div>
                        `;
                    });
                } else {
                    elScheduleList.innerHTML = '<div style="padding: 1rem; color: var(--text-secondary);">No classes scheduled for today.</div>';
                }
            }
        } else {
            console.error('Failed to fetch dashboard stats:', result.error);
        }
    } catch(err) {
        console.error('Error fetching dashboard stats:', err);
    }
}
