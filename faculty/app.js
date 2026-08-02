// app.js
const API_BASE = '../api/index.php';

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

let currentUser = {
    name: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.name) ? window.PHP_USER.name : 'Faculty Member',
    email: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.email) ? window.PHP_USER.email : '',
    role: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.designation) ? window.PHP_USER.designation : 'Faculty Member',
    designation: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.designation) ? window.PHP_USER.designation : 'Faculty Member',
    department: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.department) ? window.PHP_USER.department : 'Computer Science',
    qualification: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.qualification) ? window.PHP_USER.qualification : '',
    id: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.id) ? window.PHP_USER.id : 'FAC1001',
    mobile: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.mobile) ? window.PHP_USER.mobile : '',
    avatar: (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.avatar) ? window.PHP_USER.avatar : 'https://ui-avatars.com/api/?name=Faculty&background=4F7CFF&color=fff',
    subjects: ['Data Structures', 'Database Systems', 'Algorithms']
};

document.addEventListener('DOMContentLoaded', () => {
    // 1. High priority: Check PHP_USER from database session
    if (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.name) {
        currentUser = {
            id: window.PHP_USER.id || 'FAC1001',
            name: window.PHP_USER.name,
            email: window.PHP_USER.email,
            role: window.PHP_USER.designation || 'Faculty Member',
            designation: window.PHP_USER.designation || 'Faculty Member',
            department: window.PHP_USER.department || 'Computer Science',
            qualification: window.PHP_USER.qualification || '',
            mobile: window.PHP_USER.mobile || '',
            avatar: window.PHP_USER.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(window.PHP_USER.name)}&background=4F7CFF&color=fff`,
            subjects: ['Data Structures', 'Database Systems', 'Algorithms']
        };
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
    } else {
        // Fallback to local storage if available
        const savedUser = localStorage.getItem('currentUser');
        if (savedUser) {
            try {
                const parsed = JSON.parse(savedUser);
                if (parsed && parsed.name) {
                    currentUser = parsed;
                }
            } catch (e) {
                console.error("Invalid user data", e);
            }
        }
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
    'student-management': 'Student Directory & CRUD',
    'student-registration': 'Student Registration Portal',
    'student-profiles': 'Student Profile Management & Photo Upload',
    'monthly-report': 'Monthly Attendance Report',
    'student-report': 'Student-wise Attendance Report',
    'department-report': 'Department-wise Attendance Report',
    'low-attendance': 'Low Attendance Alerts & Defaulter List',
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
        pageTitle.textContent = pageTitles[viewId] || 'Faculty Portal';
        
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
    } else if (viewId === 'student-management') {
        loadStudentManagement();
    } else if (viewId === 'student-registration') {
        initStudentRegistrationView();
    } else if (viewId === 'student-profiles') {
        initStudentProfilesView();
    } else if (viewId === 'monthly-report') {
        initMonthlyReportView();
    } else if (viewId === 'student-report') {
        initStudentReportView();
    } else if (viewId === 'department-report') {
        initDepartmentReportView();
    } else if (viewId === 'low-attendance') {
        initLowAttendanceView();
    } else if (viewId === 'daily-attendance') {
        initDailyAttendanceView();
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
        const user = (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.name)
            ? window.PHP_USER 
            : (JSON.parse(localStorage.getItem('currentUser')) || currentUser);
            
        const nameEl = document.getElementById('my-profile-name');
        const desigEl = document.getElementById('my-profile-designation');
        const gridDesigEl = document.getElementById('my-profile-grid-desig');
        const idEl = document.getElementById('my-profile-id');
        const deptEl = document.getElementById('my-profile-dept');
        const qualEl = document.getElementById('my-profile-qualification');
        const emailEl = document.getElementById('my-profile-email');
        const mobileEl = document.getElementById('my-profile-mobile');
        const imgEl = document.getElementById('my-profile-img');

        if (nameEl) nameEl.textContent = user.name;
        if (desigEl) desigEl.textContent = user.designation || user.role || 'Faculty Member';
        if (gridDesigEl) gridDesigEl.textContent = user.designation || user.role || 'Faculty Member';
        if (idEl) idEl.textContent = user.id || 'FAC1001';
        if (deptEl) deptEl.textContent = user.department || 'Computer Science';
        if (qualEl) qualEl.textContent = user.qualification || 'Not Specified';
        if (emailEl) emailEl.textContent = user.email || '-';
        if (mobileEl) mobileEl.textContent = user.mobile || user.phone || 'Not Specified';
        if (imgEl && user.avatar) imgEl.src = user.avatar;
    }
    
    if (viewId === 'edit-profile') {
        const user = (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.name)
            ? window.PHP_USER 
            : (JSON.parse(localStorage.getItem('currentUser')) || currentUser);
            
        const editName = document.getElementById('edit-name');
        const editDept = document.getElementById('edit-department');
        const editDesig = document.getElementById('edit-designation');
        const editQual = document.getElementById('edit-qualification');
        const editEmail = document.getElementById('edit-email');
        const editMobile = document.getElementById('edit-mobile');
        const editPicture = document.getElementById('edit-picture');

        if (editName) editName.value = user.name || '';
        if (editDept) editDept.value = user.department || '';
        if (editDesig) editDesig.value = user.designation || user.role || '';
        if (editQual) editQual.value = user.qualification || '';
        if (editEmail) editEmail.value = user.email || '';
        if (editMobile) editMobile.value = user.mobile || user.phone || '';
        if (editPicture) editPicture.value = user.avatar || '';
    }
}

// ==========================================================================
// 2. DAILY ATTENDANCE LOGIC & CONTROLS
// ==========================================================================

function initDailyAttendanceView() {
    const dateInput = document.getElementById('daily-date');
    if (dateInput && !dateInput.value) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }

    const deptSelect = document.getElementById('daily-dept');
    const semSelect = document.getElementById('daily-sem');
    const divSelect = document.getElementById('daily-div');
    const lectSelect = document.getElementById('daily-lecture');

    if (currentUser && currentUser.department && deptSelect) {
        // Match user department if option exists
        for (let opt of deptSelect.options) {
            if (opt.value === currentUser.department) {
                deptSelect.value = currentUser.department;
                break;
            }
        }
    }
    if (deptSelect && !deptSelect.value) deptSelect.value = 'CE';
    if (semSelect && !semSelect.value) semSelect.value = 'Semester 3';
    if (divSelect && !divSelect.value) divSelect.value = 'Div A';
    if (lectSelect && !lectSelect.value) lectSelect.value = 'Lecture 1';

    updateDailySubjects();

    if (deptSelect) deptSelect.onchange = updateDailySubjects;
    if (semSelect) semSelect.onchange = updateDailySubjects;
}

function updateDailySubjects() {
    const deptSelect = document.getElementById('daily-dept');
    const semSelect = document.getElementById('daily-sem');
    const subjectSelect = document.getElementById('daily-subject');
    if (!subjectSelect) return;

    const dept = deptSelect ? deptSelect.value : '';
    const sem = semSelect ? semSelect.value : '';

    if (dept && sem && subjectData && subjectData[dept] && subjectData[dept][sem]) {
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        const subjects = subjectData[dept][sem];
        subjects.forEach((sub, idx) => {
            const opt = document.createElement('option');
            opt.value = sub;
            opt.textContent = sub;
            if (idx === 0) opt.selected = true; // Pre-select first subject by default
            subjectSelect.appendChild(opt);
        });
        subjectSelect.disabled = false;
    } else {
        subjectSelect.innerHTML = '<option value="">Select Department and Semester First</option>';
        subjectSelect.disabled = true;
    }
}

function resetDailyAttendanceFilters() {
    const dateInput = document.getElementById('daily-date');
    if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];

    const deptSelect = document.getElementById('daily-dept');
    const semSelect = document.getElementById('daily-sem');
    const divSelect = document.getElementById('daily-div');
    const lectSelect = document.getElementById('daily-lecture');

    if (deptSelect) deptSelect.value = (currentUser && currentUser.department) ? currentUser.department : 'CE';
    if (semSelect) semSelect.value = 'Semester 3';
    if (divSelect) divSelect.value = 'Div A';
    if (lectSelect) lectSelect.value = 'Lecture 1';

    updateDailySubjects();

    const container = document.getElementById('student-list-container');
    if (container) container.style.display = 'none';

    const searchInput = document.getElementById('attendance-search-input');
    if (searchInput) searchInput.value = '';

    showToast('Daily attendance filters reset to defaults', 'info');
}

async function loadStudents() {
    const date = document.getElementById('daily-date') ? document.getElementById('daily-date').value : '';
    const dept = document.getElementById('daily-dept') ? document.getElementById('daily-dept').value : '';
    const sem = document.getElementById('daily-sem') ? document.getElementById('daily-sem').value : '';
    const div = document.getElementById('daily-div') ? document.getElementById('daily-div').value : '';
    const subject = document.getElementById('daily-subject') ? document.getElementById('daily-subject').value : '';
    const lecture = document.getElementById('daily-lecture') ? document.getElementById('daily-lecture').value : '';

    if (!dept || !sem || !div) {
        showToast('Please select Department, Semester, and Division', 'error');
        return;
    }
    if (!subject) {
        showToast('Please select a Subject for this lecture', 'error');
        return;
    }

    try {
        showToast('Fetching enrolled students roster...', 'info');
        const res = await fetch(`${API_BASE}?request=students&dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}`, { cache: 'no-store' });

        let responseData;
        const contentType = res.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            responseData = await res.json();
        } else {
            const text = await res.text();
            throw new Error(`Invalid API response: ${text.substring(0, 50)}...`);
        }

        if (!res.ok) {
            throw new Error(responseData.error || `HTTP Error: ${res.status}`);
        }

        if (responseData.error) {
            throw new Error(responseData.error);
        }

        const students = Array.isArray(responseData) ? responseData : (responseData.data || []);

        const container = document.getElementById('student-list-container');
        const tbody = document.getElementById('attendance-tbody');
        const badgeContainer = document.getElementById('active-session-badge');

        if (!tbody || !container) return;

        tbody.innerHTML = '';

        if (!Array.isArray(students) || students.length === 0) {
            showToast('No students found for ' + dept + ' ' + sem + ' ' + div, 'warning');
            container.style.display = 'none';
            return;
        }

        // Populate session summary badges
        if (badgeContainer) {
            badgeContainer.innerHTML = `
                <span class="badge" style="background: rgba(79, 124, 255, 0.15); color: #93C5FD; border: 1px solid rgba(79, 124, 255, 0.3); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-calendar-day"></i> ${date || 'Today'}
                </span>
                <span class="badge" style="background: rgba(79, 124, 255, 0.15); color: #93C5FD; border: 1px solid rgba(79, 124, 255, 0.3); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-building-columns"></i> ${dept} &bull; ${sem} &bull; ${div}
                </span>
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-book-open"></i> ${subject}
                </span>
                <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-clock"></i> ${lecture}
                </span>
            `;
        }

        // Render Student Rows with Status Buttons & Remarks
        students.forEach((student, index) => {
            const roll = student.roll || student.roll_no || `R${index + 1}`;
            const name = student.name || student.student_name || 'Student';
            const initial = name.charAt(0).toUpperCase();

            const tr = document.createElement('tr');
            tr.dataset.roll = roll;
            tr.dataset.name = name.toLowerCase();

            tr.innerHTML = `
                <td style="font-weight: 700; color: #FFFFFF; font-family: monospace; font-size: 0.95rem;">${roll}</td>
                <td>
                    <div class="student-info">
                        <div class="student-avatar">${initial}</div>
                        <span>${name}</span>
                    </div>
                </td>
                <td>
                    <div class="switch-container" data-roll="${roll}">
                        <button type="button" class="switch-btn active present" onclick="toggleStatus(this, 'present')">
                            <i class="fa-solid fa-check"></i> Present
                        </button>
                        <button type="button" class="switch-btn" onclick="toggleStatus(this, 'absent')">
                            <i class="fa-solid fa-xmark"></i> Absent
                        </button>
                        <button type="button" class="switch-btn" onclick="toggleStatus(this, 'late')">
                            <i class="fa-solid fa-clock"></i> Late
                        </button>
                        <button type="button" class="switch-btn" onclick="toggleStatus(this, 'leave')">
                            <i class="fa-solid fa-plane-departure"></i> Leave
                        </button>
                    </div>
                </td>
                <td>
                    <input type="text" class="glass-input remark-input" style="padding: 7px 12px; width: 100%; max-width: 220px; font-size: 0.85rem;" placeholder="Optional remark (e.g. medical, sports)...">
                </td>
            `;
            tbody.appendChild(tr);
        });

        container.style.display = 'block';
        container.classList.add('fade-in');

        // Reset search input
        const searchInput = document.getElementById('attendance-search-input');
        if (searchInput) searchInput.value = '';

        updateLiveAttendanceCounters();

        showToast(`Loaded ${students.length} students successfully`, 'success');
    } catch(err) {
        showToast(err.message || 'Failed to load students roster', 'error');
        console.error('Student fetch error:', err);
    }
}

function toggleStatus(btn, status) {
    const container = btn.closest('.switch-container');
    if (!container) return;

    const buttons = container.querySelectorAll('.switch-btn');
    buttons.forEach(b => {
        b.classList.remove('active', 'present', 'absent', 'late', 'leave');
    });

    btn.classList.add('active', status);
    updateLiveAttendanceCounters();
}

function markBulk(status) {
    const switches = document.querySelectorAll('#attendance-tbody .switch-container');
    if (switches.length === 0) {
        showToast('No student records loaded to mark', 'warning');
        return;
    }

    switches.forEach(container => {
        const buttons = container.querySelectorAll('.switch-btn');
        buttons.forEach(b => b.classList.remove('active', 'present', 'absent', 'late', 'leave'));

        const targetBtn = Array.from(buttons).find(b => {
            const text = b.textContent.trim().toLowerCase();
            return text.includes(status.toLowerCase());
        });

        if (targetBtn) {
            targetBtn.classList.add('active', status);
        }
    });

    updateLiveAttendanceCounters();
    const statusLabels = { present: 'Present', absent: 'Absent', late: 'Late', leave: 'Leave' };
    showToast(`Marked all ${switches.length} students as ${statusLabels[status] || status}`, 'info');
}

function updateLiveAttendanceCounters() {
    const rows = document.querySelectorAll('#attendance-tbody tr');
    const total = rows.length;

    let present = 0;
    let absent = 0;
    let late = 0;
    let leave = 0;

    rows.forEach(tr => {
        const activeBtn = tr.querySelector('.switch-btn.active');
        if (!activeBtn) return;

        if (activeBtn.classList.contains('present')) present++;
        else if (activeBtn.classList.contains('absent')) absent++;
        else if (activeBtn.classList.contains('late')) late++;
        else if (activeBtn.classList.contains('leave')) leave++;
    });

    const totalEl = document.getElementById('counter-total');
    const presentEl = document.getElementById('counter-present');
    const absentEl = document.getElementById('counter-absent');
    const lateEl = document.getElementById('counter-late');
    const leaveEl = document.getElementById('counter-leave');
    const rateEl = document.getElementById('counter-rate');
    const statusEl = document.getElementById('attendance-save-status');

    if (totalEl) totalEl.textContent = total;
    if (presentEl) presentEl.textContent = present;
    if (absentEl) absentEl.textContent = absent;
    if (lateEl) lateEl.textContent = late;
    if (leaveEl) leaveEl.textContent = leave;

    const rate = total > 0 ? (((present + late) / total) * 100).toFixed(1) : '0.0';
    if (rateEl) rateEl.textContent = `${rate}%`;

    if (statusEl) {
        statusEl.innerHTML = `<strong>${total}</strong> Students enrolled &bull; Present: <strong style="color:#10B981">${present}</strong> &bull; Absent: <strong style="color:#EF4444">${absent}</strong> &bull; Late: <strong style="color:#F59E0B">${late}</strong>`;
    }
}

function filterAttendanceStudentRows(query) {
    const term = (query || '').trim().toLowerCase();
    const rows = document.querySelectorAll('#attendance-tbody tr');

    rows.forEach(tr => {
        const roll = (tr.dataset.roll || '').toLowerCase();
        const name = (tr.dataset.name || '').toLowerCase();

        if (!term || roll.includes(term) || name.includes(term)) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    });
}

async function saveAttendance() {
    const date = document.getElementById('daily-date') ? document.getElementById('daily-date').value : '';
    const dept = document.getElementById('daily-dept') ? document.getElementById('daily-dept').value : '';
    const sem = document.getElementById('daily-sem') ? document.getElementById('daily-sem').value : '';
    const div = document.getElementById('daily-div') ? document.getElementById('daily-div').value : '';
    const subject = document.getElementById('daily-subject') ? document.getElementById('daily-subject').value : '';
    const lecture = document.getElementById('daily-lecture') ? document.getElementById('daily-lecture').value : '';

    if (!date || !dept || !sem || !div || !subject || !lecture) {
        showToast('Please fill all academic and session fields before saving', 'error');
        return;
    }

    const tbody = document.getElementById('attendance-tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    if (rows.length === 0) {
        showToast('No students loaded to mark attendance', 'error');
        return;
    }

    const attendanceData = {};
    const remarksData = {};

    rows.forEach(tr => {
        const roll = tr.dataset.roll || tr.cells[0].textContent.trim();
        const activeBtn = tr.querySelector('.switch-btn.active');
        const status = activeBtn ? (
            activeBtn.classList.contains('present') ? 'present' :
            activeBtn.classList.contains('absent') ? 'absent' :
            activeBtn.classList.contains('late') ? 'late' :
            activeBtn.classList.contains('leave') ? 'on leave' : 'absent'
        ) : 'absent';
        
        const remarkInput = tr.querySelector('input.remark-input') || tr.querySelector('input[type="text"]');
        const remark = remarkInput ? remarkInput.value.trim() : '';

        attendanceData[roll] = status;
        if (remark) remarksData[roll] = remark;
    });

    const payload = {
        date, dept, sem, div, subject, lectNo: lecture,
        attendance: attendanceData,
        remarks: remarksData
    };

    try {
        showToast('Submitting attendance records to database...', 'info');
        const res = await fetch(`${API_BASE}?request=attendance`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        
        if (res.ok && result.success) {
            showToast('Attendance recorded and saved successfully!', 'success');
            const statusEl = document.getElementById('attendance-save-status');
            if (statusEl) {
                statusEl.innerHTML = `<span style="color: #10B981;"><i class="fa-solid fa-circle-check"></i> Attendance saved at ${new Date().toLocaleTimeString()}</span>`;
            }
        } else {
            showToast(result.error || 'Failed to save attendance records', 'error');
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

// --- UI Utilities & In-App Toast System ---
function showToast(message, type = 'success', title = '') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const safeType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';
    toast.className = `toast ${safeType}`;

    const icons = {
        success: 'fa-solid fa-circle-check',
        error: 'fa-solid fa-circle-xmark',
        warning: 'fa-solid fa-triangle-exclamation',
        info: 'fa-solid fa-circle-info'
    };

    const defaultTitles = {
        success: 'Success',
        error: 'Error',
        warning: 'Notice',
        info: 'Information'
    };

    const displayTitle = title || defaultTitles[safeType] || 'Notification';
    const duration = safeType === 'error' ? 4500 : 3500;

    toast.innerHTML = `
        <div class="toast-icon">
            <i class="${icons[safeType]}"></i>
        </div>
        <div class="toast-content">
            <span class="toast-title">${displayTitle}</span>
            <span class="toast-message">${message}</span>
        </div>
        <button class="toast-close" type="button" aria-label="Close notification">&times;</button>
        <div class="toast-progress" style="animation-duration: ${duration}ms;"></div>
    `;

    let dismissTimeout;
    const dismiss = () => {
        if (toast.classList.contains('hiding')) return;
        toast.classList.add('hiding');
        clearTimeout(dismissTimeout);
        setTimeout(() => {
            if (toast.parentElement) toast.parentElement.removeChild(toast);
        }, 320);
    };

    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) closeBtn.onclick = (e) => {
        e.stopPropagation();
        dismiss();
    };

    toast.onclick = (e) => {
        if (!e.target.closest('.toast-close')) {
            dismiss();
        }
    };

    container.appendChild(toast);
    dismissTimeout = setTimeout(dismiss, duration);
}

// Override native window.alert to seamlessly redirect all alerts to in-app toast
window.alert = function(msg) {
    if (typeof msg === 'string') {
        const lower = msg.toLowerCase();
        if (lower.includes('success') || lower.includes('enrolled') || lower.includes('updated') || lower.includes('saved')) {
            showToast(msg, 'success');
        } else if (lower.includes('error') || lower.includes('failed') || lower.includes('cannot') || lower.includes('invalid')) {
            showToast(msg, 'error');
        } else if (lower.includes('warn') || lower.includes('select') || lower.includes('please fill') || lower.includes('required')) {
            showToast(msg, 'warning');
        } else {
            showToast(msg, 'info');
        }
    } else {
        showToast(String(msg), 'info');
    }
};

// --- Top Navigation & Profile Features ---

function initTopNav() {
    updateTopNavProfile();
    initDropdowns();
    initSearch();
    renderNotifications();
}

function updateTopNavProfile() {
    const user = (typeof window.PHP_USER !== 'undefined' && window.PHP_USER && window.PHP_USER.name)
        ? window.PHP_USER 
        : (JSON.parse(localStorage.getItem('currentUser')) || currentUser);
    if (!user) return;
    const nameEl = document.getElementById('nav-profile-name');
    const roleEl = document.getElementById('nav-profile-role');
    const imgEl = document.getElementById('nav-profile-img');
    
    if (nameEl) nameEl.textContent = user.name;
    if (roleEl) {
        const desig = user.designation || user.role || 'Faculty';
        const dept = user.department ? ` • ${user.department}` : '';
        roleEl.textContent = `${desig}${dept}`;
    }
    if (imgEl && user.avatar) imgEl.src = user.avatar;
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
        showToast('Please select Date, Department, Semester, Subject, and Lecture Number.', 'warning');
        return;
    }

    editCurrentFilters = { date, dept, div, sem, subject, lecture };
    
    try {
        const res = await fetch(`${API_BASE}?request=attendance&date=${encodeURIComponent(date)}&dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}&subject=${encodeURIComponent(subject)}&lectNo=${encodeURIComponent(lecture)}`);
        
        if (!res.ok) {
            const result = await res.json().catch(() => ({}));
            showToast(result.error || 'No records found for the selected criteria.', 'info');
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
        showToast('Error fetching attendance records from database.', 'error');
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
        showToast('Select at least one student.', 'warning');
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
        showToast('No recent changes to undo.', 'info');
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
        showToast('No data to save.', 'warning');
        return;
    }
    
    if (editHistoryStack.length === 0) {
        showToast('No changes made to save.', 'info');
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
        showToast('No actual changes left to save.', 'info');
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
            showToast('Attendance records updated successfully!', 'success');
            editHistoryStack = [];
            searchEditAttendance();
        } else {
            showToast(result.error || 'Failed to update attendance records.', 'error');
        }
    } catch(err) {
        showToast('Network error while saving edits.', 'error');
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
        showToast('Viewing details is not fully implemented in this demo.', 'info');
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
        showToast('No data to export.', 'warning');
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
            showToast('PDF library not loaded. Please try again later.', 'error');
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
        showToast('Attendance report PDF generated!', 'success');
    } else if (format === 'excel') {
        if (!window.XLSX) {
            showToast('Excel library not loaded.', 'error');
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
    const user = JSON.parse(localStorage.getItem('currentUser')) || currentUser;
    user.name = document.getElementById('edit-name').value;
    user.email = document.getElementById('edit-email').value;
    user.mobile = document.getElementById('edit-mobile').value;
    const qualEl = document.getElementById('edit-qualification');
    if (qualEl) user.qualification = qualEl.value;
    
    const newPic = document.getElementById('edit-picture').value;
    if (newPic) user.avatar = newPic;
    
    localStorage.setItem('currentUser', JSON.stringify(user));
    if (typeof window.PHP_USER !== 'undefined' && window.PHP_USER) {
        window.PHP_USER.name = user.name;
        window.PHP_USER.email = user.email;
        window.PHP_USER.mobile = user.mobile;
        window.PHP_USER.qualification = user.qualification;
        window.PHP_USER.avatar = user.avatar;
    }
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
    const logoutModal = document.getElementById('logout-modal');
    if (logoutModal) {
        logoutModal.classList.add('active');
    }
}

// Setup logout modal listeners
document.addEventListener('DOMContentLoaded', () => {
    const logoutModal = document.getElementById('logout-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');

    if (logoutModal) {
        const closeLogoutModal = () => logoutModal.classList.remove('active');
        
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeLogoutModal);
        if (cancelLogoutBtn) cancelLogoutBtn.addEventListener('click', closeLogoutModal);
        
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                closeLogoutModal();
            }
        });

        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', () => {
                // Clear stored data
                localStorage.removeItem('currentUser');
                localStorage.removeItem('attendanceHistory');
                localStorage.removeItem('notifications');
                
                // Redirect to logout script
                window.location.href = '../auth/logout.php';
            });
        }
    }
});

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

// ==========================================================================
// 4. STUDENT MANAGEMENT MODULE (Issue #4 - Registration, CRUD, Profiles, Photo Upload)
// ==========================================================================

let currentLoadedStudents = [];
let targetQuickPhotoStudentId = null;

// --- 4.1 Student Directory & CRUD ---
async function loadStudentManagement() {
    const dept = document.getElementById('filter-dept')?.value || '';
    const sem = document.getElementById('filter-sem')?.value || '';
    const div = document.getElementById('filter-div')?.value || '';
    const search = document.getElementById('filter-search')?.value || '';
    
    const tbody = document.getElementById('student-management-list');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 24px; color: var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin"></i> Loading students...</td></tr>';
    
    try {
        const res = await fetch(`../api/students_crud.php?dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}&search=${encodeURIComponent(search)}`);
        const result = await res.json();
        
        if (result.success) {
            currentLoadedStudents = result.data || [];
            
            // Calculate Stats
            const total = currentLoadedStudents.length;
            const safeCount = currentLoadedStudents.filter(s => parseFloat(s.attendance_percentage || 0) >= 75).length;
            const defaultersCount = total - safeCount;
            
            const totalEl = document.getElementById('crud-stat-total');
            const safeEl = document.getElementById('crud-stat-safe');
            const defEl = document.getElementById('crud-stat-defaulters');
            if (totalEl) totalEl.textContent = total;
            if (safeEl) safeEl.textContent = safeCount;
            if (defEl) defEl.textContent = defaultersCount;

            if (total === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 24px; color: var(--text-muted);">No students found matching current filters.</td></tr>';
                return;
            }
            
            tbody.innerHTML = currentLoadedStudents.map(st => {
                const pct = parseFloat(st.attendance_percentage || 0);
                const color = pct >= 75 ? 'var(--success)' : (pct >= 60 ? 'var(--warning)' : 'var(--danger)');
                return `
                <tr>
                    <td>
                        <img src="${st.profile_photo}" alt="${st.student_name}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);">
                    </td>
                    <td style="font-weight: 600; color: #fff;">${st.roll_no}</td>
                    <td style="font-weight: 500;">${st.student_name}</td>
                    <td><span class="status-badge" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6; font-size: 0.75rem; padding: 3px 8px; border-radius: 6px;">${st.department}</span></td>
                    <td>${st.semester}</td>
                    <td>${st.division}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div class="progress-bar" style="flex: 1; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; min-width: 60px;">
                                <div style="height: 100%; width: ${Math.min(pct, 100)}%; background: ${color};"></div>
                            </div>
                            <span style="font-size: 0.85em; font-weight: 600; color: ${color};">${pct.toFixed(1)}%</span>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button class="btn btn-sm" style="padding: 5px 9px; background: rgba(59, 130, 246, 0.15); color: #3B82F6;" onclick="viewStudentProfile(${st.student_id})" title="View Profile"><i class="fa-solid fa-eye"></i></button>
                            <button class="btn btn-sm" style="padding: 5px 9px; background: rgba(245, 158, 11, 0.15); color: #F59E0B;" onclick="editStudent(${st.student_id})" title="Edit Details"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm" style="padding: 5px 9px; background: rgba(168, 85, 247, 0.15); color: #A855F7;" onclick="triggerQuickPhotoUpload(${st.student_id})" title="Change Photo"><i class="fa-solid fa-camera"></i></button>
                            <button class="btn btn-sm" style="padding: 5px 9px; background: rgba(239, 68, 68, 0.15); color: #EF4444;" onclick="deleteStudent(${st.student_id}, '${st.student_name}')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `}).join('');
        }
    } catch (err) {
        console.error('Error loading students:', err);
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 20px; color: var(--danger);">Failed to load student roster.</td></tr>';
    }
}

// Export Student Roster to Excel
function exportStudentRosterExcel() {
    if (!currentLoadedStudents || currentLoadedStudents.length === 0) {
        showToast('No student records available to export.', 'warning');
        return;
    }
    if (typeof XLSX === 'undefined') {
        showToast('Excel Export library is loading, please try again.', 'info');
        return;
    }
    const data = currentLoadedStudents.map(st => ({
        'Roll No': st.roll_no,
        'Student Name': st.student_name,
        'Department': st.department,
        'Semester': st.semester,
        'Division': st.division,
        'Attendance %': (parseFloat(st.attendance_percentage) || 0).toFixed(1) + '%',
        'Total Lectures': st.total_lectures || 0,
        'Attended Lectures': st.attended_lectures || 0
    }));

    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Students_Roster');
    XLSX.writeFile(wb, `Student_Roster_${new Date().toISOString().slice(0,10)}.xlsx`);
    showToast('Student roster exported to Excel successfully!', 'success');
}

// --- 4.2 Student Registration Portal Logic ---
function initStudentRegistrationView() {
    resetRegForm();
    loadRecentRegistrations();
}

function handleRegPhotoSelect(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(evt) {
        const preview = document.getElementById('reg-photo-preview');
        if (preview) preview.src = evt.target.result;
    };
    reader.readAsDataURL(file);
}

function updateRegPhotoPlaceholder() {
    const name = document.getElementById('reg-student-name')?.value.trim();
    const fileInput = document.getElementById('reg-student-photo');
    if (!fileInput || fileInput.files.length === 0) {
        const preview = document.getElementById('reg-photo-preview');
        if (preview) {
            preview.src = name ? `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=3B82F6&color=fff&size=200` : 'https://ui-avatars.com/api/?name=New+Student&background=3B82F6&color=fff&size=200';
        }
    }
}

function resetRegForm() {
    const form = document.getElementById('reg-student-form');
    if (form) form.reset();
    const preview = document.getElementById('reg-photo-preview');
    if (preview) preview.src = 'https://ui-avatars.com/api/?name=New+Student&background=3B82F6&color=fff&size=200';
}

async function handleRegistrationSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('reg-student-name')?.value.trim();
    const roll = document.getElementById('reg-student-roll')?.value.trim();
    const email = document.getElementById('reg-student-email')?.value.trim();
    const dept = document.getElementById('reg-student-dept')?.value;
    const sem = document.getElementById('reg-student-sem')?.value;
    const div = document.getElementById('reg-student-div')?.value;
    const photoInput = document.getElementById('reg-student-photo');
    const submitBtn = document.getElementById('reg-submit-btn');

    if (!name || !roll || !email || !dept || !sem || !div) {
        showToast('Please fill in all required fields.', 'warning');
        return;
    }

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Registering Student...';
    }

    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('name', name);
    formData.append('roll', roll);
    formData.append('email', email);
    formData.append('dept', dept);
    formData.append('sem', sem);
    formData.append('div', div);
    if (photoInput && photoInput.files.length > 0) {
        formData.append('photo', photoInput.files[0]);
    }

    try {
        const res = await fetch('../api/students_crud.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showToast(`🎉 Success! ${name} enrolled (Roll: ${roll}, Dept: ${dept})`, 'success');
            resetRegForm();
            loadRecentRegistrations();
            if (typeof loadDashboardStats === 'function') loadDashboardStats();
        } else {
            showToast('Registration Failed: ' + (data.message || 'Unknown error occurred'), 'error');
        }
    } catch (err) {
        console.error('Registration Error:', err);
        showToast('An error occurred while submitting student registration.', 'error');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-user-check"></i> Register Student';
        }
    }
}

async function loadRecentRegistrations() {
    const tbody = document.getElementById('reg-recent-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:15px; color:var(--text-secondary);">Loading recent registrations...</td></tr>';
    try {
        const res = await fetch('../api/students_crud.php');
        const result = await res.json();
        if (result.success && result.data) {
            const recent = result.data.slice(-5).reverse();
            if (recent.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:15px; color:var(--text-muted);">No student registrations found.</td></tr>';
                return;
            }
            tbody.innerHTML = recent.map(st => `
                <tr>
                    <td><img src="${st.profile_photo}" alt="${st.student_name}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;"></td>
                    <td style="font-weight:600; color:#fff;">${st.roll_no}</td>
                    <td>${st.student_name}</td>
                    <td><span class="status-badge" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6; font-size:0.75rem; padding:2px 7px; border-radius:4px;">${st.department}</span></td>
                    <td>${st.semester}</td>
                    <td>${st.division}</td>
                    <td>
                        <button class="btn btn-sm btn-outline" onclick="viewStudentProfile(${st.student_id})" style="padding: 3px 8px;"><i class="fa-solid fa-eye"></i> View</button>
                    </td>
                </tr>
            `).join('');
        }
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:15px; color:var(--danger);">Failed to load recent registrations.</td></tr>';
    }
}

// --- 4.3 Student Profiles Gallery & Photo Upload Logic ---
function initStudentProfilesView() {
    loadStudentProfilesGallery();
}

async function loadStudentProfilesGallery() {
    const dept = document.getElementById('profile-filter-dept')?.value || '';
    const sem = document.getElementById('profile-filter-sem')?.value || '';
    const div = document.getElementById('profile-filter-div')?.value || '';
    const search = document.getElementById('profile-filter-search')?.value || '';
    const grid = document.getElementById('student-profiles-grid');
    if (!grid) return;

    grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><p style="margin-top: 10px;">Loading profile gallery...</p></div>';

    try {
        const res = await fetch(`../api/students_crud.php?dept=${encodeURIComponent(dept)}&sem=${encodeURIComponent(sem)}&div=${encodeURIComponent(div)}&search=${encodeURIComponent(search)}`);
        const result = await res.json();
        if (result.success && result.data) {
            if (result.data.length === 0) {
                grid.innerHTML = '<div class="glass-card" style="grid-column: 1/-1; text-align: center; padding: 40px; border-radius: 12px; color: var(--text-muted);"><i class="fa-solid fa-users-slash fa-2x"></i><p style="margin-top: 12px;">No student profiles match your search filters.</p></div>';
                return;
            }

            grid.innerHTML = result.data.map(st => {
                const pct = parseFloat(st.attendance_percentage || 0);
                const color = pct >= 75 ? 'var(--success)' : (pct >= 60 ? 'var(--warning)' : 'var(--danger)');
                return `
                <div class="glass-card" style="padding: 22px; border-radius: 16px; display: flex; flex-direction: column; align-items: center; text-align: center; position: relative; transition: transform 0.2s, box-shadow 0.2s;">
                    <div style="position: relative; margin-bottom: 14px;">
                        <img src="${st.profile_photo}" alt="${st.student_name}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid ${color}; box-shadow: 0 6px 16px rgba(0,0,0,0.3);">
                        <button onclick="triggerQuickPhotoUpload(${st.student_id})" title="Change Profile Photo" style="position: absolute; bottom: 0; right: 0; background: var(--primary-color); color: #fff; border: 2px solid #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.75rem; box-shadow: 0 2px 6px rgba(0,0,0,0.4);">
                            <i class="fa-solid fa-camera"></i>
                        </button>
                    </div>

                    <h3 style="font-size: 1.1rem; margin: 0 0 4px 0; color: #fff;">${st.student_name}</h3>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 12px; font-weight: 600;">${st.roll_no}</div>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; margin-bottom: 14px;">
                        <span style="background: rgba(59, 130, 246, 0.15); color: #3B82F6; font-size: 0.75rem; padding: 3px 8px; border-radius: 6px; font-weight: 500;">${st.department}</span>
                        <span style="background: rgba(255, 255, 255, 0.08); color: var(--text-secondary); font-size: 0.75rem; padding: 3px 8px; border-radius: 6px;">${st.semester}</span>
                        <span style="background: rgba(255, 255, 255, 0.08); color: var(--text-secondary); font-size: 0.75rem; padding: 3px 8px; border-radius: 6px;">${st.division}</span>
                    </div>

                    <div style="width: 100%; background: rgba(0,0,0,0.2); padding: 10px 14px; border-radius: 10px; margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 6px;">
                            <span style="color: var(--text-muted);">Attendance Rate</span>
                            <span style="font-weight: 700; color: ${color};">${pct.toFixed(1)}%</span>
                        </div>
                        <div class="progress-bar" style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                            <div style="height: 100%; width: ${Math.min(pct, 100)}%; background: ${color};"></div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; width: 100%;">
                        <button class="btn btn-outline btn-sm" style="flex: 1;" onclick="viewStudentProfile(${st.student_id})"><i class="fa-solid fa-id-card"></i> Profile</button>
                        <button class="btn btn-outline btn-sm" style="flex: 1;" onclick="editStudent(${st.student_id})"><i class="fa-solid fa-pen"></i> Edit</button>
                    </div>
                </div>
            `}).join('');
        }
    } catch (err) {
        grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--danger);">Failed to load profile cards.</div>';
    }
}

function triggerQuickPhotoUpload(studentId) {
    targetQuickPhotoStudentId = studentId;
    const fileInput = document.getElementById('quick-photo-input');
    if (fileInput) {
        fileInput.value = '';
        fileInput.click();
    }
}

async function handleQuickPhotoUpload(e) {
    const file = e.target.files[0];
    if (!file || !targetQuickPhotoStudentId) return;

    showToast('Uploading profile photo...', 'info');

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('student_id', targetQuickPhotoStudentId);
    formData.append('photo', file);

    // Fetch existing details for name/roll requirements
    try {
        const getRes = await fetch(`../api/students_crud.php?action=get&id=${targetQuickPhotoStudentId}`);
        const getResult = await getRes.json();
        if (getResult.success && getResult.data) {
            const st = getResult.data;
            formData.append('name', st.student_name);
            formData.append('roll', st.roll_no);
            formData.append('dept', st.department);
            formData.append('sem', st.semester);
            formData.append('div', st.division);

            const uploadRes = await fetch('../api/students_crud.php', {
                method: 'POST',
                body: formData
            });
            const uploadResult = await uploadRes.json();
            if (uploadResult.success) {
                showToast('Profile photo updated successfully!', 'success');
                loadStudentManagement();
                loadStudentProfilesGallery();
            } else {
                showToast(uploadResult.message || 'Failed to update profile photo.', 'error');
            }
        }
    } catch (err) {
        console.error(err);
        showToast('An error occurred during photo upload.', 'error');
    }
}

// --- 4.4 Modals and Shared Student CRUD Actions ---
function openAddStudentModal() {
    const form = document.getElementById('student-form') || document.getElementById('add-student-form');
    if (form) form.reset();
    const idEl = document.getElementById('student-id');
    if (idEl) idEl.value = '';
    const titleEl = document.getElementById('student-modal-title');
    if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-user-plus"></i> Add New Student';
    const btnEl = document.getElementById('save-student-btn');
    if (btnEl) btnEl.textContent = 'Add Student';
    const modal = document.getElementById('add-student-modal');
    if (modal) modal.classList.add('show');
}

function closeAddStudentModal() {
    const modal = document.getElementById('add-student-modal');
    if (modal) modal.classList.remove('show');
}

async function submitAddStudent() {
    const form = document.getElementById('student-form') || document.getElementById('add-student-form');
    if (form && !form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const id = document.getElementById('student-id')?.value || '';
    const name = document.getElementById('new-student-name')?.value || '';
    const roll = document.getElementById('new-student-roll')?.value || '';
    const email = document.getElementById('new-student-email')?.value || '';
    const dept = document.getElementById('new-student-dept')?.value || '';
    const sem = document.getElementById('new-student-sem')?.value || '';
    const div = document.getElementById('new-student-div')?.value || '';
    const photoInput = document.getElementById('new-student-photo');

    const btn = document.getElementById('save-student-btn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Saving...';
    }

    const formData = new FormData();
    formData.append('action', id ? 'update' : 'create');
    if (id) formData.append('student_id', id);
    formData.append('name', name);
    formData.append('roll', roll);
    if (!id) formData.append('email', email);
    formData.append('dept', dept);
    formData.append('sem', sem);
    formData.append('div', div);
    if (photoInput && photoInput.files.length > 0) {
        formData.append('photo', photoInput.files[0]);
    }

    try {
        const res = await fetch('../api/students_crud.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        
        if (data.success) {
            showToast(data.message || (id ? 'Student updated successfully!' : 'Student added successfully!'), 'success');
            closeAddStudentModal();
            loadStudentManagement();
            loadStudentProfilesGallery();
            if (typeof loadDashboardStats === 'function') {
                loadDashboardStats();
            }
        } else {
            showToast(data.message || 'Failed to save student', 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('An error occurred while saving the student.', 'error');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = id ? 'Save Changes' : 'Add Student';
        }
    }
}

async function editStudent(id) {
    try {
        const res = await fetch(`../api/students_crud.php?action=get&id=${id}`);
        const result = await res.json();
        
        if (result.success) {
            const st = result.data;
            document.getElementById('student-id').value = st.student_id;
            document.getElementById('new-student-name').value = st.student_name;
            document.getElementById('new-student-roll').value = st.roll_no;
            document.getElementById('new-student-email').value = st.email || '';
            document.getElementById('new-student-dept').value = st.department;
            document.getElementById('new-student-sem').value = st.semester;
            document.getElementById('new-student-div').value = st.division;
            document.getElementById('new-student-photo').value = '';
            
            document.getElementById('student-modal-title').innerHTML = '<i class="fa-solid fa-user-pen"></i> Edit Student';
            document.getElementById('save-student-btn').textContent = 'Save Changes';
            document.getElementById('add-student-modal').classList.add('show');
        }
    } catch (err) {
        console.error('Error fetching student details:', err);
        showToast('Could not load student details.', 'error');
    }
}

async function deleteStudent(id, name) {
    if (!confirm(`Are you sure you want to delete ${name}?\n\nThis will also remove all their attendance records and login access.`)) {
        return;
    }
    
    try {
        showToast('Deleting student record...', 'info');
        const res = await fetch(`../api/students_crud.php?action=delete&student_id=${id}`, {
            method: 'POST'
        });
        const result = await res.json();
        
        if (result.success) {
            showToast('Student deleted successfully.', 'success');
            loadStudentManagement();
            loadStudentProfilesGallery();
        } else {
            showToast(result.message || 'Failed to delete student.', 'error');
        }
    } catch (err) {
        console.error('Error deleting student:', err);
        showToast('An error occurred while deleting the student.', 'error');
    }
}

async function viewStudentProfile(id) {
    try {
        const res = await fetch(`../api/students_crud.php?action=get&id=${id}`);
        const result = await res.json();
        
        if (result.success) {
            const st = result.data;
            document.getElementById('profile-modal-photo').src = st.profile_photo;
            document.getElementById('profile-modal-name').textContent = st.student_name;
            document.getElementById('profile-modal-roll').textContent = st.roll_no;
            document.getElementById('profile-modal-dept').textContent = st.department;
            document.getElementById('profile-modal-email').textContent = st.email || '-';
            document.getElementById('profile-modal-sem').textContent = st.semester;
            document.getElementById('profile-modal-div').textContent = st.division;
            
            const bar = document.getElementById('profile-modal-attendance-bar');
            if (bar) {
                const pct = parseFloat(st.attendance_percentage || 0);
                bar.style.width = pct + '%';
                bar.style.background = pct >= 75 ? 'var(--success)' : (pct >= 60 ? 'var(--warning)' : 'var(--danger)');
            }
            const textEl = document.getElementById('profile-modal-attendance-text');
            if (textEl) textEl.textContent = (parseFloat(st.attendance_percentage) || 0) + '%';
            
            document.getElementById('student-profile-modal').classList.add('show');
        }
    } catch (err) {
        console.error('Error fetching student profile:', err);
        showToast('Could not load student profile.', 'error');
    }
}

function closeStudentProfileModal() {
    const modal = document.getElementById('student-profile-modal');
    if (modal) modal.classList.remove('show');
}

// Global window bindings
window.openAddStudentModal = openAddStudentModal;
window.closeAddStudentModal = closeAddStudentModal;
window.submitAddStudent = submitAddStudent;
window.loadStudentManagement = loadStudentManagement;
window.exportStudentRosterExcel = exportStudentRosterExcel;
window.initStudentRegistrationView = initStudentRegistrationView;
window.handleRegPhotoSelect = handleRegPhotoSelect;
window.updateRegPhotoPlaceholder = updateRegPhotoPlaceholder;
window.resetRegForm = resetRegForm;
window.handleRegistrationSubmit = handleRegistrationSubmit;
window.loadRecentRegistrations = loadRecentRegistrations;
window.initStudentProfilesView = initStudentProfilesView;
window.loadStudentProfilesGallery = loadStudentProfilesGallery;
window.triggerQuickPhotoUpload = triggerQuickPhotoUpload;
window.handleQuickPhotoUpload = handleQuickPhotoUpload;
window.editStudent = editStudent;
window.deleteStudent = deleteStudent;
window.viewStudentProfile = viewStudentProfile;
window.closeStudentProfileModal = closeStudentProfileModal;

// ==========================================================================
// REPORTS & NOTIFICATIONS ENGINE FOR FACULTY ROLE
// ==========================================================================

let globalMonthlyRecords = [];
let globalDefaulterRecords = [];
let currentSelectedStudent = null;
let alertsSentCount = 0;

// Helper: Format Percentage
function formatPct(val) {
    const num = parseFloat(val);
    return isNaN(num) ? '0.0%' : num.toFixed(1) + '%';
}

// ----------------------------------------------------
// 1. Monthly Report Logic
// ----------------------------------------------------
function initMonthlyReportView() {
    const deptSel = document.getElementById('rep-dept');
    const semSel = document.getElementById('rep-sem');
    const subSel = document.getElementById('rep-subject');

    const updateMonthlySubjects = () => {
        if (!subSel) return;
        const d = deptSel ? deptSel.value : 'CE';
        const s = semSel ? semSel.value : 'Semester 5';
        subSel.innerHTML = '<option value="">All Subjects</option>';
        if (subjectData[d] && subjectData[d][s]) {
            subjectData[d][s].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub;
                opt.textContent = sub;
                subSel.appendChild(opt);
            });
        }
    };

    if (deptSel) deptSel.addEventListener('change', updateMonthlySubjects);
    if (semSel) semSel.addEventListener('change', updateMonthlySubjects);
    updateMonthlySubjects();

    // Auto generate initial report
    generateMonthlyReport();
}

async function generateMonthlyReport() {
    const month = document.getElementById('rep-month')?.value || '07';
    const year = document.getElementById('rep-year')?.value || '2026';
    const dept = document.getElementById('rep-dept')?.value || '';
    const sem = document.getElementById('rep-sem')?.value || '';
    const div = document.getElementById('rep-div')?.value || '';
    const sub = document.getElementById('rep-subject')?.value || '';

    const tbody = document.getElementById('monthly-report-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center" style="padding: 25px;"><i class="fa-solid fa-spinner fa-spin text-primary"></i> Generating Monthly Attendance Report...</td></tr>';
    }

    const monthNames = {
        '01':'January','02':'February','03':'March','04':'April','05':'May','06':'June',
        '07':'July','08':'August','09':'September','10':'October','11':'November','12':'December'
    };
    const titleMonth = document.getElementById('monthly-table-month-name');
    if (titleMonth) titleMonth.textContent = `${monthNames[month] || month} ${year}`;

    try {
        // Fetch students from student management API to compile realistic records
        const res = await fetch(`../api/students_crud.php?action=list&department=${encodeURIComponent(dept)}&semester=${encodeURIComponent(sem)}&division=${encodeURIComponent(div)}`);
        const result = await res.json();
        
        let students = [];
        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            students = result.data;
        } else {
            // Fallback seed students for rich report demo
            students = [
                { student_id: 1, roll_no: 'CE5A01', student_name: 'Aarav Sharma', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'aarav@college.edu', attendance_percentage: 88.5 },
                { student_id: 2, roll_no: 'CE5A02', student_name: 'Diya Patel', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'diya@college.edu', attendance_percentage: 92.0 },
                { student_id: 3, roll_no: 'CE5A03', student_name: 'Rohan Gupta', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'rohan@college.edu', attendance_percentage: 58.0 },
                { student_id: 4, roll_no: 'CE5A04', student_name: 'Ananya Deshmukh', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'ananya@college.edu', attendance_percentage: 71.5 },
                { student_id: 5, roll_no: 'CE5A05', student_name: 'Kabir Joshi', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'kabir@college.edu', attendance_percentage: 96.0 },
                { student_id: 6, roll_no: 'CE5A06', student_name: 'Isha Kulkarni', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'isha@college.edu', attendance_percentage: 64.0 },
                { student_id: 7, roll_no: 'CE5A07', student_name: 'Varun Nair', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'varun@college.edu', attendance_percentage: 82.5 }
            ];
        }

        const totalConducted = 26; // sessions in the month
        let totalPctSum = 0;
        let safeCount = 0;
        let defaulterCount = 0;

        globalMonthlyRecords = students.map((st, idx) => {
            const pct = parseFloat(st.attendance_percentage || (70 + (idx * 5) % 28));
            const attended = Math.round((pct / 100) * totalConducted);
            const absent = totalConducted - attended;
            
            totalPctSum += pct;
            if (pct >= 75) safeCount++;
            else defaulterCount++;

            return {
                id: st.student_id || idx + 1,
                roll_no: st.roll_no,
                name: st.student_name,
                department: st.department || dept || 'CE',
                semester: st.semester || sem || 'Semester 5',
                division: st.division || div || 'Div A',
                email: st.email || `${st.roll_no.toLowerCase()}@college.edu`,
                total_classes: totalConducted,
                attended: attended,
                absent: absent,
                percentage: pct
            };
        });

        // Update Stat Cards
        document.getElementById('rep-total-sessions').textContent = totalConducted;
        const avgPct = students.length ? (totalPctSum / students.length).toFixed(1) : '0.0';
        document.getElementById('rep-avg-attendance').textContent = avgPct + '%';
        document.getElementById('rep-safe-count').textContent = safeCount;
        document.getElementById('rep-defaulter-count').textContent = defaulterCount;
        
        const countBadge = document.getElementById('monthly-record-count-badge');
        if (countBadge) countBadge.textContent = `${globalMonthlyRecords.length} Students`;

        // Render Table
        if (tbody) {
            tbody.innerHTML = globalMonthlyRecords.map(r => {
                let badgeClass = 'var(--success)';
                let badgeText = 'Safe (&ge;75%)';
                let bgTint = 'rgba(16,185,129,0.15)';
                let borderTint = 'rgba(16,185,129,0.3)';

                if (r.percentage < 60) {
                    badgeClass = 'var(--danger)';
                    badgeText = 'Critical (<60%)';
                    bgTint = 'rgba(239,68,68,0.15)';
                    borderTint = 'rgba(239,68,68,0.3)';
                } else if (r.percentage < 75) {
                    badgeClass = 'var(--warning)';
                    badgeText = 'Moderate (60-74%)';
                    bgTint = 'rgba(245,158,11,0.15)';
                    borderTint = 'rgba(245,158,11,0.3)';
                }

                return `
                    <tr>
                        <td style="font-weight: 700; color: #fff;">${r.roll_no}</td>
                        <td style="font-weight: 600;">${r.name}</td>
                        <td><span class="badge" style="background: rgba(79,124,255,0.15); color: var(--primary);">${r.department}</span></td>
                        <td>${r.semester} - ${r.division}</td>
                        <td><strong>${r.total_classes}</strong></td>
                        <td style="color: var(--success); font-weight: 600;">${r.attended}</td>
                        <td style="color: var(--danger); font-weight: 600;">${r.absent}</td>
                        <td style="font-weight: 700; color: ${badgeClass}; font-size: 1.05rem;">${r.percentage.toFixed(1)}%</td>
                        <td>
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; background: ${bgTint}; color: ${badgeClass}; border: 1px solid ${borderTint};">
                                ${badgeText}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-outline btn-sm" onclick="quickJumpToStudent('${r.roll_no}')" title="View Full Report"><i class="fa-solid fa-chart-user"></i></button>
                                ${r.percentage < 75 ? `<button class="btn btn-danger btn-sm" onclick='openSendAlertModalForStudentData(${JSON.stringify(r)})' title="Send Alert"><i class="fa-solid fa-bell"></i></button>` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

    } catch (err) {
        console.error('Error generating monthly report:', err);
        if (tbody) tbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger" style="padding: 20px;"><i class="fa-solid fa-triangle-exclamation"></i> Error loading report data.</td></tr>';
    }
}

function resetMonthlyFilters() {
    if (document.getElementById('rep-month')) document.getElementById('rep-month').value = '07';
    if (document.getElementById('rep-year')) document.getElementById('rep-year').value = '2026';
    if (document.getElementById('rep-dept')) document.getElementById('rep-dept').value = 'CE';
    if (document.getElementById('rep-sem')) document.getElementById('rep-sem').value = 'Semester 5';
    if (document.getElementById('rep-div')) document.getElementById('rep-div').value = 'Div A';
    if (document.getElementById('rep-subject')) document.getElementById('rep-subject').value = '';
    generateMonthlyReport();
}

function exportMonthlyReportPDF() {
    if (!window.jspdf) {
        window.print();
        return;
    }
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');

        doc.setFillColor(8, 17, 31);
        doc.rect(0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight(), 'F');
        doc.setTextColor(255, 255, 255);

        doc.setFontSize(18);
        doc.text('Student Attendance Tracking Portal', 14, 18);
        doc.setFontSize(12);
        doc.setTextColor(148, 163, 184);
        doc.text(`Monthly Attendance Report | Generated on: ${new Date().toLocaleDateString()}`, 14, 26);

        const tableData = globalMonthlyRecords.map(r => [
            r.roll_no, r.name, r.department, `${r.semester} (${r.division})`, r.total_classes, r.attended, r.absent, `${r.percentage.toFixed(1)}%`, r.percentage >= 75 ? 'Safe' : (r.percentage >= 60 ? 'Moderate Risk' : 'Critical Risk')
        ]);

        doc.autoTable({
            head: [['Roll No', 'Name', 'Dept', 'Sem & Div', 'Total', 'Present', 'Absent', 'Percentage', 'Status']],
            body: tableData,
            startY: 32,
            theme: 'grid',
            headStyles: { fillColor: [79, 124, 255], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 9, cellPadding: 3, textColor: [240, 240, 240], fillColor: [15, 29, 58] },
            alternateRowStyles: { fillColor: [11, 23, 48] }
        });

        doc.save(`Monthly_Attendance_Report_${Date.now()}.pdf`);
        showToast('PDF Report downloaded successfully!', 'success');
    } catch (e) {
        console.error('PDF export error:', e);
        window.print();
    }
}

function exportMonthlyReportExcel() {
    if (window.XLSX) {
        const exportData = globalMonthlyRecords.map(r => ({
            'Roll Number': r.roll_no,
            'Student Name': r.name,
            'Department': r.department,
            'Semester': r.semester,
            'Division': r.division,
            'Total Classes': r.total_classes,
            'Attended': r.attended,
            'Absent': r.absent,
            'Attendance %': `${r.percentage.toFixed(1)}%`,
            'Defaulter Status': r.percentage >= 75 ? 'Safe' : (r.percentage >= 60 ? 'Moderate Risk' : 'Critical Risk')
        }));

        const ws = XLSX.utils.json_to_sheet(exportData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Monthly Report');
        XLSX.writeFile(wb, `Monthly_Attendance_Report_${Date.now()}.xlsx`);
        showToast('Excel Report downloaded successfully!', 'success');
    } else {
        // Fallback CSV
        let csv = 'Roll No,Name,Department,Semester,Division,Total Classes,Present,Absent,Percentage,Status\n';
        globalMonthlyRecords.forEach(r => {
            csv += `"${r.roll_no}","${r.name}","${r.department}","${r.semester}","${r.division}",${r.total_classes},${r.attended},${r.absent},"${r.percentage.toFixed(1)}%","${r.percentage >= 75 ? 'Safe' : 'Defaulter'}"\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Monthly_Report_${Date.now()}.csv`;
        link.click();
        showToast('CSV Report downloaded successfully!', 'success');
    }
}

// ----------------------------------------------------
// 2. Student-wise Report Logic
// ----------------------------------------------------
function initStudentReportView() {
    filterStudentReportList();
}

async function filterStudentReportList() {
    const dept = document.getElementById('sr-dept')?.value || 'CE';
    const sem = document.getElementById('sr-sem')?.value || 'Semester 5';
    const div = document.getElementById('sr-div')?.value || 'Div A';
    const sel = document.getElementById('sr-student-select');

    if (!sel) return;
    sel.innerHTML = '<option value="">Loading students...</option>';

    try {
        const res = await fetch(`../api/students_crud.php?action=list&department=${encodeURIComponent(dept)}&semester=${encodeURIComponent(sem)}&division=${encodeURIComponent(div)}`);
        const result = await res.json();
        
        let students = [];
        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            students = result.data;
        } else {
            students = [
                { student_id: 1, roll_no: 'CE5A01', student_name: 'Aarav Sharma', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'aarav@college.edu', attendance_percentage: 88.5 },
                { student_id: 2, roll_no: 'CE5A02', student_name: 'Diya Patel', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'diya@college.edu', attendance_percentage: 92.0 },
                { student_id: 3, roll_no: 'CE5A03', student_name: 'Rohan Gupta', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'rohan@college.edu', attendance_percentage: 58.0 }
            ];
        }

        sel.innerHTML = '<option value="">-- Choose a Student --</option>';
        students.forEach(st => {
            const opt = document.createElement('option');
            opt.value = JSON.stringify(st);
            opt.textContent = `${st.roll_no} - ${st.student_name} (${st.attendance_percentage || 85}%)`;
            sel.appendChild(opt);
        });

        if (students.length > 0) {
            sel.selectedIndex = 1;
            loadSelectedStudentReport();
        }
    } catch (err) {
        console.error('Error fetching students for student report:', err);
    }
}

function loadSelectedStudentReport() {
    const sel = document.getElementById('sr-student-select');
    if (!sel || !sel.value) return;

    try {
        const st = JSON.parse(sel.value);
        currentSelectedStudent = st;

        const detailsCard = document.getElementById('student-report-details');
        if (detailsCard) detailsCard.style.display = 'block';

        document.getElementById('sr-student-name').textContent = st.student_name;
        document.getElementById('sr-roll-no').textContent = st.roll_no;
        document.getElementById('sr-dept-name').textContent = st.department || 'CE';
        document.getElementById('sr-sem-div').textContent = `${st.semester || 'Semester 5'} - ${st.division || 'Div A'}`;
        document.getElementById('sr-email').textContent = st.email || `${st.roll_no.toLowerCase()}@college.edu`;
        if (st.profile_photo) {
            document.getElementById('sr-avatar').src = st.profile_photo;
        }

        const pct = parseFloat(st.attendance_percentage || 85.0);
        const pctEl = document.getElementById('sr-overall-pct');
        const pillEl = document.getElementById('sr-status-pill');

        if (pctEl) {
            pctEl.textContent = pct.toFixed(1) + '%';
            pctEl.style.color = pct >= 75 ? 'var(--success)' : (pct >= 60 ? 'var(--warning)' : 'var(--danger)');
        }

        if (pillEl) {
            if (pct >= 75) {
                pillEl.textContent = 'Regular / Safe Standard';
                pillEl.style.background = 'rgba(16,185,129,0.2)';
                pillEl.style.color = '#10B981';
                pillEl.style.borderColor = 'rgba(16,185,129,0.4)';
            } else if (pct >= 60) {
                pillEl.textContent = 'Moderate Defaulter Warning';
                pillEl.style.background = 'rgba(245,158,11,0.2)';
                pillEl.style.color = '#F59E0B';
                pillEl.style.borderColor = 'rgba(245,158,11,0.4)';
            } else {
                pillEl.textContent = 'Critical Risk Debarment Notice';
                pillEl.style.background = 'rgba(239,68,68,0.2)';
                pillEl.style.color = '#EF4444';
                pillEl.style.borderColor = 'rgba(239,68,68,0.4)';
            }
        }

        // Generate subject-wise table
        const dept = st.department || 'CE';
        const sem = st.semester || 'Semester 5';
        const subjects = (subjectData[dept] && subjectData[dept][sem]) ? subjectData[dept][sem] : [
            '310241 - Database Management Systems',
            '310242 - Theory of Computation',
            '310243 - Systems Programming and Operating System',
            '310244 - Computer Networks and Security',
            '310245 - Elective I'
        ];

        const facultyList = ['Dr. Smith', 'Prof. Davis', 'Dr. Wilson', 'Prof. Taylor', 'Dr. Anderson'];
        const tbody = document.getElementById('sr-subject-tbody');
        if (tbody) {
            tbody.innerHTML = subjects.map((sub, i) => {
                const total = 24 + (i * 2);
                // Vary sub percentage near student overall
                const variance = ((i % 3) - 1) * 6;
                const subPct = Math.min(100, Math.max(35, pct + variance));
                const attended = Math.round((subPct / 100) * total);
                const missed = total - attended;
                const isLow = subPct < 75;

                return `
                    <tr>
                        <td style="font-weight: 600; color: #fff;">${sub}</td>
                        <td class="text-muted"><i class="fa-solid fa-user-tie text-primary"></i> ${facultyList[i % facultyList.length]}</td>
                        <td><strong>${total}</strong></td>
                        <td style="color: var(--success); font-weight: 600;">${attended}</td>
                        <td style="color: var(--danger); font-weight: 600;">${missed}</td>
                        <td style="font-weight: 700; color: ${isLow ? (subPct < 60 ? 'var(--danger)' : 'var(--warning)') : 'var(--success)'}; font-size: 1.05rem;">
                            ${subPct.toFixed(1)}%
                        </td>
                        <td>
                            <span style="padding: 3px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; background: ${isLow ? 'rgba(239,68,68,0.15)' : 'rgba(16,185,129,0.15)'}; color: ${isLow ? 'var(--danger)' : 'var(--success)'};">
                                ${isLow ? 'Defaulter' : 'Compliant'}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

    } catch (e) {
        console.error('Error loading student report:', e);
    }
}

function quickJumpToStudent(rollNo) {
    navigateTo('student-report');
    setTimeout(() => {
        const sel = document.getElementById('sr-student-select');
        if (sel) {
            for (let i = 0; i < sel.options.length; i++) {
                if (sel.options[i].text.includes(rollNo)) {
                    sel.selectedIndex = i;
                    loadSelectedStudentReport();
                    break;
                }
            }
        }
    }, 150);
}

function exportStudentReportPDF() {
    if (!currentSelectedStudent) return;
    if (!window.jspdf) {
        window.print();
        return;
    }
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.setFillColor(8, 17, 31);
        doc.rect(0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight(), 'F');
        doc.setTextColor(255, 255, 255);

        doc.setFontSize(16);
        doc.text('Student Attendance Report', 14, 18);
        doc.setFontSize(11);
        doc.setTextColor(148, 163, 184);
        doc.text(`Student: ${currentSelectedStudent.student_name} (${currentSelectedStudent.roll_no})`, 14, 26);
        doc.text(`Department: ${currentSelectedStudent.department} | ${currentSelectedStudent.semester} ${currentSelectedStudent.division}`, 14, 32);

        doc.autoTable({
            html: '#sr-subject-table',
            startY: 40,
            theme: 'grid',
            headStyles: { fillColor: [79, 124, 255], textColor: 255 },
            styles: { fontSize: 9, cellPadding: 3, textColor: [240, 240, 240], fillColor: [15, 29, 58] },
            alternateRowStyles: { fillColor: [11, 23, 48] }
        });

        doc.save(`Student_Report_${currentSelectedStudent.roll_no}.pdf`);
        showToast('Student PDF Report exported successfully!', 'success');
    } catch (e) {
        window.print();
    }
}

function exportStudentReportExcel() {
    if (!currentSelectedStudent) return;
    const table = document.getElementById('sr-subject-table');
    if (window.XLSX && table) {
        const wb = XLSX.utils.table_to_book(table, { sheet: 'Subject Attendance' });
        XLSX.writeFile(wb, `Student_Attendance_${currentSelectedStudent.roll_no}.xlsx`);
        showToast('Student Excel Report downloaded!', 'success');
    }
}

// ----------------------------------------------------
// 3. Department-wise Report Logic
// ----------------------------------------------------
function initDepartmentReportView() {
    const deptStats = [
        { dept: 'Computer Engineering (CE)', sem: 'Sem 5 (Div A & B)', enrolled: 120, conducted: 135, avgPct: 84.2, defaulters: 9, indicator: 'Excellent' },
        { dept: 'AI & Data Science (AIDS)', sem: 'Sem 5 (Div A)', enrolled: 95, conducted: 128, avgPct: 82.8, defaulters: 8, indicator: 'Good' },
        { dept: 'Electrical Engineering (EE)', sem: 'Sem 5 (Div A)', enrolled: 80, conducted: 110, avgPct: 76.5, defaulters: 14, indicator: 'Average' },
        { dept: 'Mechanical Engineering (ME)', sem: 'Sem 5 (Div A & B)', enrolled: 110, conducted: 122, avgPct: 79.1, defaulters: 12, indicator: 'Good' },
        { dept: 'Biotechnology (BT)', sem: 'Sem 5 (Div A)', enrolled: 65, conducted: 118, avgPct: 86.4, defaulters: 4, indicator: 'Excellent' }
    ];

    const tbody = document.getElementById('dept-report-tbody');
    if (tbody) {
        tbody.innerHTML = deptStats.map(d => {
            const barWidth = d.avgPct + '%';
            const color = d.avgPct >= 80 ? 'var(--success)' : (d.avgPct >= 75 ? 'var(--info)' : 'var(--warning)');
            return `
                <tr>
                    <td style="font-weight: 700; color: #fff;">${d.dept}</td>
                    <td>${d.sem}</td>
                    <td><strong>${d.enrolled}</strong></td>
                    <td>${d.conducted}</td>
                    <td style="font-weight: 700; color: ${color}; font-size: 1.05rem;">${d.avgPct}%</td>
                    <td style="color: var(--danger); font-weight: 700;"><i class="fa-solid fa-triangle-exclamation"></i> ${d.defaulters}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                <div style="width: ${barWidth}; height: 100%; background: ${color};"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: ${color};">${d.indicator}</span>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
}

function exportDeptReportPDF() {
    if (!window.jspdf) {
        window.print();
        return;
    }
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFillColor(8, 17, 31);
        doc.rect(0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight(), 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.text('Department-wise Institutional Attendance Report', 14, 18);
        doc.autoTable({
            html: '#dept-report-table',
            startY: 28,
            theme: 'grid',
            headStyles: { fillColor: [79, 124, 255], textColor: 255 },
            styles: { fontSize: 9, cellPadding: 3, textColor: [240, 240, 240], fillColor: [15, 29, 58] }
        });
        doc.save(`Department_Attendance_Report_${Date.now()}.pdf`);
        showToast('Department PDF exported successfully!', 'success');
    } catch (e) {
        window.print();
    }
}

function exportDeptReportExcel() {
    const table = document.getElementById('dept-report-table');
    if (window.XLSX && table) {
        const wb = XLSX.utils.table_to_book(table, { sheet: 'Dept Attendance' });
        XLSX.writeFile(wb, `Department_Attendance_${Date.now()}.xlsx`);
        showToast('Department Excel Report downloaded!', 'success');
    }
}

// ----------------------------------------------------
// 4. Low Attendance Alerts & Notifications Engine
// ----------------------------------------------------
function initLowAttendanceView() {
    applyLowAttendanceThreshold();
}

async function applyLowAttendanceThreshold() {
    const thresholdInput = document.getElementById('low-att-threshold');
    const threshold = thresholdInput ? parseFloat(thresholdInput.value) || 75 : 75;

    const tbody = document.getElementById('low-attendance-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center" style="padding: 20px;"><i class="fa-solid fa-spinner fa-spin text-primary"></i> Filtering defaulter students below ' + threshold + '%...</td></tr>';
    }

    try {
        const res = await fetch('../api/students_crud.php?action=list');
        const result = await res.json();
        
        let allStudents = [];
        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            allStudents = result.data;
        } else {
            allStudents = [
                { student_id: 3, roll_no: 'CE5A03', student_name: 'Rohan Gupta', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'rohan@college.edu', attendance_percentage: 58.0 },
                { student_id: 4, roll_no: 'CE5A04', student_name: 'Ananya Deshmukh', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'ananya@college.edu', attendance_percentage: 71.5 },
                { student_id: 6, roll_no: 'CE5A06', student_name: 'Isha Kulkarni', department: 'CE', semester: 'Semester 5', division: 'Div A', email: 'isha@college.edu', attendance_percentage: 64.0 },
                { student_id: 8, roll_no: 'AD5A02', student_name: 'Tanmay Patil', department: 'AIDS', semester: 'Semester 5', division: 'Div A', email: 'tanmay@college.edu', attendance_percentage: 54.2 },
                { student_id: 9, roll_no: 'EE5A05', student_name: 'Siddharth Rao', department: 'EE', semester: 'Semester 5', division: 'Div A', email: 'siddharth@college.edu', attendance_percentage: 69.0 }
            ];
        }

        // Filter defaulters
        globalDefaulterRecords = allStudents.filter(st => {
            const pct = parseFloat(st.attendance_percentage || 80);
            return pct < threshold;
        });

        let criticalCount = 0;
        let moderateCount = 0;

        globalDefaulterRecords.forEach(st => {
            const pct = parseFloat(st.attendance_percentage);
            if (pct < 60) criticalCount++;
            else moderateCount++;
        });

        // Update Stats
        document.getElementById('low-stat-total').textContent = globalDefaulterRecords.length;
        document.getElementById('low-stat-critical').textContent = criticalCount;
        document.getElementById('low-stat-moderate').textContent = moderateCount;
        document.getElementById('low-stat-alerts-sent').textContent = alertsSentCount;
        
        const badge = document.getElementById('low-att-count-badge');
        if (badge) badge.textContent = `${globalDefaulterRecords.length} Students Flagged`;

        if (tbody) {
            if (globalDefaulterRecords.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-success" style="padding: 30px;"><i class="fa-solid fa-circle-check fa-2x mb-2 d-block"></i> No students below ${threshold}% attendance cutoff! Excellent compliance.</td></tr>`;
            } else {
                tbody.innerHTML = globalDefaulterRecords.map(st => {
                    const pct = parseFloat(st.attendance_percentage || 65);
                    const isCritical = pct < 60;
                    const total = 26;
                    const attended = Math.round((pct / 100) * total);

                    return `
                        <tr>
                            <td style="font-weight: 700; color: #fff;">${st.roll_no}</td>
                            <td style="font-weight: 600;">${st.student_name}</td>
                            <td><span class="badge" style="background: rgba(79,124,255,0.15); color: var(--primary);">${st.department}</span></td>
                            <td>${st.semester} - ${st.division}</td>
                            <td><strong>${attended} / ${total}</strong></td>
                            <td style="font-weight: 800; color: ${isCritical ? 'var(--danger)' : 'var(--warning)'}; font-size: 1.1rem;">${pct.toFixed(1)}%</td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; background: ${isCritical ? 'rgba(239,68,68,0.2)' : 'rgba(245,158,11,0.2)'}; color: ${isCritical ? '#EF4444' : '#F59E0B'}; border: 1px solid ${isCritical ? 'rgba(239,68,68,0.4)' : 'rgba(245,158,11,0.4)'};">
                                    <i class="fa-solid ${isCritical ? 'fa-triangle-exclamation' : 'fa-bell'}"></i> ${isCritical ? 'Critical Risk (<60%)' : 'Moderate Risk (60-74%)'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick='openSendAlertModalForStudentData(${JSON.stringify(st)})' style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-paper-plane"></i> Send Alert
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        }

    } catch (e) {
        console.error('Error calculating defaulters:', e);
    }
}

function exportLowAttendancePDF() {
    if (!window.jspdf) {
        window.print();
        return;
    }
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFillColor(8, 17, 31);
        doc.rect(0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight(), 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.text('Low Attendance Defaulters Log & Action List', 14, 18);
        doc.autoTable({
            html: '#low-attendance-table',
            startY: 26,
            theme: 'grid',
            headStyles: { fillColor: [239, 68, 68], textColor: 255 },
            styles: { fontSize: 9, cellPadding: 3, textColor: [240, 240, 240], fillColor: [15, 29, 58] }
        });
        doc.save(`Low_Attendance_Defaulters_${Date.now()}.pdf`);
        showToast('Defaulters list exported to PDF!', 'success');
    } catch (e) {
        window.print();
    }
}

function exportLowAttendanceExcel() {
    const table = document.getElementById('low-attendance-table');
    if (window.XLSX && table) {
        const wb = XLSX.utils.table_to_book(table, { sheet: 'Defaulters' });
        XLSX.writeFile(wb, `Defaulters_Attendance_${Date.now()}.xlsx`);
        showToast('Defaulters Excel download complete!', 'success');
    }
}

// ----------------------------------------------------
// 5. Alert / Notification Dispatching Dialog & Handlers
// ----------------------------------------------------
function openSendAlertModalForStudent() {
    if (!currentSelectedStudent) return;
    openSendAlertModalForStudentData(currentSelectedStudent);
}

function openSendAlertModalForStudentData(st) {
    const modal = document.getElementById('send-alert-modal');
    if (!modal) return;

    document.getElementById('alert-recipient-type').value = 'single';
    document.getElementById('alert-student-id').value = st.student_id || st.id || '';
    document.getElementById('alert-student-roll').value = st.roll_no || '';
    document.getElementById('alert-student-email').value = st.email || '';
    document.getElementById('alert-recipient-display').value = `${st.student_name || st.name} (${st.roll_no}) - Current Attendance: ${parseFloat(st.attendance_percentage || st.percentage || 0).toFixed(1)}%`;
    
    document.getElementById('alert-subject').value = `Urgent Attendance Warning: ${st.roll_no} below statutory threshold`;
    document.getElementById('alert-message').value = `Dear ${st.student_name || st.name} and Parent/Guardian, your attendance has dropped to ${parseFloat(st.attendance_percentage || st.percentage || 0).toFixed(1)}% in Department of ${st.department}. A minimum of 75% is strictly mandatory. Please contact your Faculty Advisor immediately.`;

    modal.classList.add('show');
}

function openBulkAlertModal() {
    const modal = document.getElementById('send-alert-modal');
    if (!modal) return;

    if (globalDefaulterRecords.length === 0) {
        showToast('No defaulters currently flagged to notify.', 'warning');
        return;
    }

    document.getElementById('alert-recipient-type').value = 'bulk';
    document.getElementById('alert-recipient-display').value = `ALL ${globalDefaulterRecords.length} Flagged Defaulter Students & Parents`;
    document.getElementById('alert-subject').value = `Official Notification: Mandatory Attendance Warning (${globalDefaulterRecords.length} Students)`;
    document.getElementById('alert-message').value = `Official Warning Notice: Your recorded attendance is currently below the mandatory 75% requirement. You are required to submit leave justifications or meet the Department Head within 3 working days to avoid examination debarment.`;

    modal.classList.add('show');
}

function closeSendAlertModal() {
    const modal = document.getElementById('send-alert-modal');
    if (modal) modal.classList.remove('show');
}

async function submitSendAlert() {
    const btn = document.getElementById('btn-dispatch-alert');
    const origText = btn ? btn.innerHTML : 'Send Alert';
    if (btn) {
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Dispatching...';
        btn.disabled = true;
    }

    const type = document.getElementById('alert-recipient-type').value;
    const subject = document.getElementById('alert-subject').value;
    const message = document.getElementById('alert-message').value;
    const roll = document.getElementById('alert-student-roll').value;

    const emailChan = document.getElementById('chan-email').checked;
    const inappChan = document.getElementById('chan-inapp').checked;
    const smsChan = document.getElementById('chan-sms').checked;

    // Simulate API call to satp/api/reports_api.php or internal alert dispatch
    try {
        await new Promise(resolve => setTimeout(resolve, 600));

        const count = type === 'bulk' ? globalDefaulterRecords.length : 1;
        alertsSentCount += count;

        const lowStatAlerts = document.getElementById('low-stat-alerts-sent');
        if (lowStatAlerts) lowStatAlerts.textContent = alertsSentCount;

        // Add to live notification list
        addTopNotification({
            id: Date.now(),
            icon: 'fa-paper-plane',
            color: 'var(--primary)',
            title: `Alert Dispatched (${type === 'bulk' ? 'Bulk Defaulters' : roll})`,
            time: 'Just now',
            message: `${subject} sent via ${[emailChan ? 'Email' : '', inappChan ? 'Portal' : '', smsChan ? 'SMS' : ''].filter(Boolean).join(', ')}`
        });

        closeSendAlertModal();
        showToast(`Attendance alert successfully dispatched to ${type === 'bulk' ? count + ' students' : roll}!`, 'success');

    } catch (e) {
        console.error('Error dispatching alert:', e);
        showToast('Error dispatching alert notification.', 'error');
    } finally {
        if (btn) {
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    }
}

// ----------------------------------------------------
// 6. Top Navigation Notification Live Feed
// ----------------------------------------------------
const facultyLiveNotifications = [
    { id: 1, icon: 'fa-triangle-exclamation', color: '#EF4444', title: 'Low Attendance Alert', time: '10m ago', message: '3 students in CE Sem 5 Div A have dropped below 75% cutoff.' },
    { id: 2, icon: 'fa-clipboard-check', color: '#F59E0B', title: 'Pending Validation', time: '1h ago', message: 'Attendance for Lecture 2 (Algorithms) awaits your confirmation.' },
    { id: 3, icon: 'fa-bullhorn', color: '#10B981', title: 'Department Notice', time: 'Yesterday', message: 'Monthly attendance reports for July 2026 are ready for review.' }
];

function initTopNotificationsFeed() {
    const list = document.getElementById('notification-list');
    const badge = document.getElementById('notification-badge');
    if (!list) return;

    if (facultyLiveNotifications.length > 0) {
        if (badge) {
            badge.textContent = facultyLiveNotifications.length;
            badge.style.display = 'block';
        }
        list.innerHTML = facultyLiveNotifications.map(n => `
            <div class="notification-item" style="padding: 10px 14px; border-bottom: 1px solid var(--border-color); display: flex; gap: 12px; align-items: flex-start; cursor: pointer;" onclick="handleNotificationClick('${n.title}')">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: ${n.color}; flex-shrink: 0;">
                    <i class="fa-solid ${n.icon}"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <strong style="font-size: 0.85rem; color: #fff;">${n.title}</strong>
                        <small style="color: var(--text-muted); font-size: 0.75rem;">${n.time}</small>
                    </div>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">${n.message}</p>
                </div>
            </div>
        `).join('');
    } else {
        if (badge) badge.style.display = 'none';
        list.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">No new notifications</div>';
    }
}

function addTopNotification(notif) {
    facultyLiveNotifications.unshift(notif);
    initTopNotificationsFeed();
}

function clearAllNotifications() {
    facultyLiveNotifications.length = 0;
    initTopNotificationsFeed();
    showToast('All notifications cleared.', 'info');
}

function handleNotificationClick(title) {
    if (title.includes('Low Attendance')) {
        navigateTo('low-attendance');
    } else if (title.includes('Validation')) {
        navigateTo('attendance-validation');
    } else if (title.includes('Report') || title.includes('Department')) {
        navigateTo('monthly-report');
    }
}

// Global Bindings for Reports & Alerts
window.initMonthlyReportView = initMonthlyReportView;
window.generateMonthlyReport = generateMonthlyReport;
window.resetMonthlyFilters = resetMonthlyFilters;
window.exportMonthlyReportPDF = exportMonthlyReportPDF;
window.exportMonthlyReportExcel = exportMonthlyReportExcel;

window.initStudentReportView = initStudentReportView;
window.filterStudentReportList = filterStudentReportList;
window.loadSelectedStudentReport = loadSelectedStudentReport;
window.quickJumpToStudent = quickJumpToStudent;
window.exportStudentReportPDF = exportStudentReportPDF;
window.exportStudentReportExcel = exportStudentReportExcel;

window.initDepartmentReportView = initDepartmentReportView;
window.exportDeptReportPDF = exportDeptReportPDF;
window.exportDeptReportExcel = exportDeptReportExcel;

window.initLowAttendanceView = initLowAttendanceView;
window.applyLowAttendanceThreshold = applyLowAttendanceThreshold;
window.exportLowAttendancePDF = exportLowAttendancePDF;
window.exportLowAttendanceExcel = exportLowAttendanceExcel;

window.openSendAlertModalForStudent = openSendAlertModalForStudent;
window.openSendAlertModalForStudentData = openSendAlertModalForStudentData;
window.openBulkAlertModal = openBulkAlertModal;
window.closeSendAlertModal = closeSendAlertModal;
window.submitSendAlert = submitSendAlert;
window.clearAllNotifications = clearAllNotifications;
window.handleNotificationClick = handleNotificationClick;

// Initialize notifications on load
document.addEventListener('DOMContentLoaded', () => {
    initTopNotificationsFeed();
});



