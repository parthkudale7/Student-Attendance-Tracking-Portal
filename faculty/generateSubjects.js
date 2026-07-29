
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

const subjects = {
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

let appJs = fs.readFileSync('app.js', 'utf8');

// The block to replace starts with "const subjectData = {" and ends right before "const facultyDB ="
const startPattern = "const subjectData = {";
const endPattern = "const facultyDB = [";

const startIndex = appJs.indexOf(startPattern);
const endIndex = appJs.indexOf(endPattern);

if (startIndex !== -1 && endIndex !== -1) {
    const newContent = "const subjectData = " + JSON.stringify(subjects, null, 4) + ";\n";
    appJs = appJs.substring(0, startIndex) + newContent + appJs.substring(endIndex);
    fs.writeFileSync('app.js', appJs);
    console.log("Updated app.js successfully!");
} else {
    console.log("Could not find the target block in app.js");
}
