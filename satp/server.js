const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

const PORT = 3000;
const PUBLIC_DIR = __dirname;

const MIME_TYPES = {
    '.html': 'text/html; charset=utf-8',
    '.css': 'text/css',
    '.js': 'application/javascript',
    '.json': 'application/json',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.svg': 'image/svg+xml',
    '.ico': 'image/x-icon'
};

const server = http.createServer((req, res) => {
    const parsedUrl = url.parse(req.url, true);
    let pathname = parsedUrl.pathname;

    if (pathname === '/' || pathname === '/index.php' || pathname === '/monthly_report.php') {
        pathname = '/monthly_report.html';
    } else if (pathname === '/student_report.php') {
        pathname = '/student_report.html';
    } else if (pathname === '/department_report.php') {
        pathname = '/department_report.html';
    } else if (pathname === '/low_attendance.php') {
        pathname = '/low_attendance.html';
    } else if (pathname === '/faculty_profile.php') {
        pathname = '/faculty_profile.html';
    } else if (pathname.startsWith('/api/reports_api.php')) {
        // Forward API requests to mock JSON generator logic
        return handleMockApi(req, res, parsedUrl.query);
    }

    const filePath = path.join(PUBLIC_DIR, pathname);
    const ext = path.extname(filePath).toLowerCase();

    fs.readFile(filePath, (err, content) => {
        if (err) {
            if (err.code === 'ENOENT') {
                res.writeHead(404, { 'Content-Type': 'text/plain' });
                res.end('404 Not Found');
            } else {
                res.writeHead(500);
                res.end(`Server Error: ${err.code}`);
            }
        } else {
            res.writeHead(200, { 'Content-Type': MIME_TYPES[ext] || 'text/html' });
            res.end(content, 'utf-8');
        }
    });
});

function handleMockApi(req, res, query) {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    const action = query.action || '';

    if (action === 'get_filters') {
        return res.end(JSON.stringify({
            departments: [
                { id: 1, dept_code: 'CE', dept_name: 'Computer Engineering' },
                { id: 2, dept_code: 'AIDS', dept_name: 'AI & Data Science' },
                { id: 3, dept_code: 'EE', dept_name: 'Electrical Engineering' },
                { id: 4, dept_code: 'BT', dept_name: 'Biotechnology' },
                { id: 5, dept_code: 'ME', dept_name: 'Mechanical Engineering' }
            ],
            months: [
                { id: '01', name: 'January' },
                { id: '02', name: 'February' },
                { id: '03', name: 'March' },
                { id: '04', name: 'April' },
                { id: '05', name: 'May' },
                { id: '06', name: 'June' },
                { id: '07', name: 'July' },
                { id: '08', name: 'August' },
                { id: '09', name: 'September' },
                { id: '10', name: 'October' },
                { id: '11', name: 'November' },
                { id: '12', name: 'December' }
            ],
            years: Array.from({ length: 27 }, (_, i) => 2001 + i),
            semesters: [1, 2, 3, 4, 5, 6, 7, 8],
            divisions: ['A', 'B', 'C', 'D', 'E'],
            subjects: [
                { id: 1, subject_code: 'CE501', subject_name: 'Advanced Data Structures & Algorithms', dept_id: 1 },
                { id: 2, subject_code: 'CE502', subject_name: 'Database Management Systems', dept_id: 1 },
                { id: 3, subject_code: 'CE503', subject_name: 'Operating Systems Core', dept_id: 1 },
                { id: 4, subject_code: 'CE504', subject_name: 'Computer Networks', dept_id: 1 },
                { id: 5, subject_code: 'AD501', subject_name: 'Machine Learning Foundations', dept_id: 2 },
                { id: 6, subject_code: 'AD502', subject_name: 'Neural Networks & Deep Learning', dept_id: 2 },
                { id: 7, subject_code: 'AD503', subject_name: 'Big Data Analytics & Processing', dept_id: 2 },
                { id: 8, subject_code: 'EE501', subject_name: 'Electrical Machines & Power Systems', dept_id: 3 },
                { id: 9, subject_code: 'EE502', subject_name: 'Control Systems Engineering', dept_id: 3 },
                { id: 10, subject_code: 'EE503', subject_name: 'Microcontrollers & Embedded Systems', dept_id: 3 },
                { id: 11, subject_code: 'BT501', subject_name: 'Genetic Engineering & Recombinant DNA', dept_id: 4 },
                { id: 12, subject_code: 'BT502', subject_name: 'Bioprocess Engineering & Fermentation', dept_id: 4 },
                { id: 13, subject_code: 'BT503', subject_name: 'Bioinformatics & Computational Biology', dept_id: 4 },
                { id: 14, subject_code: 'ME501', subject_name: 'Thermodynamics & Heat Transfer', dept_id: 5 },
                { id: 15, subject_code: 'ME502', subject_name: 'Fluid Mechanics & Hydraulic Machinery', dept_id: 5 },
                { id: 16, subject_code: 'ME503', subject_name: 'Computer-Aided Design & Manufacturing (CAD/CAM)', dept_id: 5 }
            ],
            faculties: [
                { id: 1, name: 'Dr. Robert Vance' },
                { id: 2, name: 'Prof. Elena Rostova' },
                { id: 3, name: 'Dr. Marcus Sterling' },
                { id: 4, name: 'Prof. Sarah Jenkins' },
                { id: 5, name: 'Dr. Alan Turing' }
            ],
            students: [
                { id: 1, roll_no: 'CE2026-001', name: 'Alex Mercer', prn: 'PRN2024001', dept: 'CE' },
                { id: 2, roll_no: 'CE2026-002', name: 'Sophia Chen', prn: 'PRN2024002', dept: 'CE' },
                { id: 3, roll_no: 'CE2026-003', name: 'David Miller', prn: 'PRN2024003', dept: 'CE' },
                { id: 4, roll_no: 'CE2026-004', name: 'Emma Watson', prn: 'PRN2024004', dept: 'CE' },
                { id: 5, roll_no: 'CE2026-005', name: 'Liam Gallagher', prn: 'PRN2024005', dept: 'CE' },
                { id: 6, roll_no: 'EE2026-010', name: 'Zoe Kravitz', prn: 'PRN2024010', dept: 'EE' },
                { id: 7, roll_no: 'BT2026-011', name: 'Lucas Scott', prn: 'PRN2024011', dept: 'BT' },
                { id: 8, roll_no: 'AD2026-020', name: 'Aria Montgomery', prn: 'PRN2024020', dept: 'AIDS' },
                { id: 9, roll_no: 'AD2026-021', name: 'Noah Vance', prn: 'PRN2024021', dept: 'AIDS' },
                { id: 10, roll_no: 'ME2026-030', name: 'Ethan Hunt', prn: 'PRN2024030', dept: 'ME' }
            ]
        }));
    }

    if (action === 'monthly_report') {
        const dept_id = query.dept_id || '';
        const semester = query.semester || '';
        const division = query.division || '';
        const subject_id = query.subject_id || '';
        const faculty_id = query.faculty_id || '';
        const month = query.month || '07';
        const year = query.year || '2026';

        const deptMap = {
            '1': { code: 'CE', name: 'Computer Engineering' },
            '2': { code: 'AIDS', name: 'AI & Data Science' },
            '3': { code: 'EE', name: 'Electrical Engineering' },
            '4': { code: 'BT', name: 'Biotechnology' },
            '5': { code: 'ME', name: 'Mechanical Engineering' }
        };

        const allStudentsList = [
            { id: 1, base_roll: '001', name: 'Alex Mercer', default_dept: '1', default_sem: '5', default_div: 'A', base_p: 19 },
            { id: 2, base_roll: '002', name: 'Sophia Chen', default_dept: '1', default_sem: '5', default_div: 'A', base_p: 17 },
            { id: 3, base_roll: '003', name: 'David Miller', default_dept: '1', default_sem: '5', default_div: 'A', base_p: 13 },
            { id: 4, base_roll: '004', name: 'Emma Watson', default_dept: '1', default_sem: '5', default_div: 'B', base_p: 16 },
            { id: 5, base_roll: '005', name: 'Liam Gallagher', default_dept: '1', default_sem: '5', default_div: 'B', base_p: 10 },
            { id: 6, base_roll: '010', name: 'Zoe Kravitz', default_dept: '3', default_sem: '5', default_div: 'A', base_p: 18 },
            { id: 7, base_roll: '011', name: 'Lucas Scott', default_dept: '4', default_sem: '5', default_div: 'A', base_p: 14 },
            { id: 8, base_roll: '020', name: 'Aria Montgomery', default_dept: '2', default_sem: '5', default_div: 'A', base_p: 19 },
            { id: 9, base_roll: '021', name: 'Noah Vance', default_dept: '2', default_sem: '5', default_div: 'B', base_p: 15 },
            { id: 10, base_roll: '030', name: 'Ethan Hunt', default_dept: '5', default_sem: '5', default_div: 'A', base_p: 12 }
        ];

        let filteredStudents = allStudentsList.filter(s => {
            if (dept_id && s.default_dept !== String(dept_id)) return false;
            if (semester && s.default_sem !== String(semester)) return false;
            if (division && s.default_div !== String(division)) return false;
            return true;
        });

        if (filteredStudents.length === 0) {
            const count = 6;
            filteredStudents = Array.from({ length: count }, (_, i) => ({
                id: 100 + i,
                base_roll: String(i + 1).padStart(3, '0'),
                name: `Student ${i + 1}`,
                default_dept: dept_id || '1',
                default_sem: semester || '5',
                default_div: division || 'A',
                base_p: 14 + (i % 5)
            }));
        }

        const monthNum = parseInt(month, 10) || 7;
        const yearNum = parseInt(year, 10) || 2026;
        const subjNum = parseInt(subject_id, 10) || 0;
        const facNum = parseInt(faculty_id, 10) || 0;

        const totalSessions = 20 + (monthNum % 4) + (subjNum % 3);

        const table = filteredStudents.map((s, idx) => {
            const targetDeptId = dept_id || s.default_dept;
            const targetDeptCode = deptMap[targetDeptId]?.code || 'CE';
            const targetSem = semester || s.default_sem;
            const targetDiv = division || s.default_div;

            const rollNo = `${targetDeptCode}${yearNum}-S${targetSem}${targetDiv}-${s.base_roll}`;
            
            const seed = (s.id * 17) + (monthNum * 13) + (yearNum * 7) + (subjNum * 19) + (facNum * 23) + (idx * 3);
            const variance = (seed % 7) - 3;
            const present = Math.max(0, Math.min(totalSessions, s.base_p + variance));
            const absent = totalSessions - present;
            const attendance_pct = parseFloat(((present / totalSessions) * 100).toFixed(1));

            let status = 'Safe';
            if (attendance_pct < 60) status = 'Critical';
            else if (attendance_pct < 75) status = 'Warning';

            return {
                roll_no: rollNo,
                name: s.name,
                dept: targetDeptCode,
                dept_id: targetDeptId,
                semester: targetSem,
                division: targetDiv,
                total_sessions: totalSessions,
                present: present,
                absent: absent,
                attendance_pct: attendance_pct,
                status: status
            };
        });

        const totalStudents = table.length;
        const totalPresent = table.reduce((acc, curr) => acc + curr.present, 0);
        const totalAbsent = table.reduce((acc, curr) => acc + curr.absent, 0);
        const avgPct = totalStudents > 0 ? (table.reduce((acc, curr) => acc + curr.attendance_pct, 0) / totalStudents).toFixed(1) : 0;

        const safeCount = table.filter(s => s.attendance_pct >= 75).length;
        const warnCount = table.filter(s => s.attendance_pct >= 60 && s.attendance_pct < 75).length;
        const critCount = table.filter(s => s.attendance_pct < 60).length;

        const monthlyTrendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const monthlyTrendPercentages = monthlyTrendLabels.map((m, idx) => {
            const mVal = parseFloat(avgPct) + Math.sin(idx + monthNum + subjNum + facNum) * 5;
            return parseFloat(Math.max(50, Math.min(99, mVal)).toFixed(1));
        });

        return res.end(JSON.stringify({
            stats: { total_students: totalStudents, present_count: totalPresent, absent_count: totalAbsent, overall_pct: avgPct },
            trend: { labels: monthlyTrendLabels, percentages: monthlyTrendPercentages },
            distribution: { labels: ['≥75% (Safe)', '60-74% (Warning)', '<60% (Critical)'], data: [safeCount, warnCount, critCount] },
            table: table
        }));
    }

    if (action === 'student_report') {
        const studentId = parseInt(query.student_id || '1', 10);
        
        const mockStudentDb = {
            1: {
                profile: { id: 1, name: 'Alex Mercer', roll_no: 'CS2026-001', prn: 'PRN2024001', dept: 'Computer Science & Engineering', semester: 5, division: 'A', overall_pct: 95.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Alex' },
                subjects: [
                    { code: 'CS501', name: 'Advanced Data Structures & Algorithms', faculty: 'Dr. Robert Vance', total: 12, present: 12, absent: 0, pct: 100.0 },
                    { code: 'CS502', name: 'Database Management Systems', faculty: 'Prof. Elena Rostova', total: 10, present: 9, absent: 1, pct: 90.0 },
                    { code: 'CS503', name: 'Operating Systems Core', faculty: 'Dr. Robert Vance', total: 8, present: 7, absent: 1, pct: 87.5 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'CS503 - Operating Systems', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' },
                    { date: '2026-07-20', subject: 'CS501 - Data Structures', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' },
                    { date: '2026-07-19', subject: 'CS502 - DBMS', faculty: 'Prof. Rostova', status: 'Present', remarks: 'On time' },
                    { date: '2026-07-11', subject: 'CS503 - Operating Systems', faculty: 'Dr. Vance', status: 'Absent', remarks: 'Sick leave notified' }
                ],
                charts: {
                    subject_bar: { labels: ['CS501', 'CS502', 'CS503'], percentages: [100.0, 90.0, 87.5] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [92.0, 94.0, 95.0] },
                    pie: { labels: ['Present', 'Absent'], data: [28, 2] }
                }
            },
            2: {
                profile: { id: 2, name: 'Sophia Chen', roll_no: 'CS2026-002', prn: 'PRN2024002', dept: 'Computer Science & Engineering', semester: 5, division: 'A', overall_pct: 88.5, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Sophia' },
                subjects: [
                    { code: 'CS501', name: 'Advanced Data Structures & Algorithms', faculty: 'Dr. Robert Vance', total: 12, present: 11, absent: 1, pct: 91.6 },
                    { code: 'CS502', name: 'Database Management Systems', faculty: 'Prof. Elena Rostova', total: 10, present: 8, absent: 2, pct: 80.0 },
                    { code: 'CS503', name: 'Operating Systems Core', faculty: 'Dr. Robert Vance', total: 8, present: 8, absent: 0, pct: 100.0 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'CS503 - Operating Systems', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' },
                    { date: '2026-07-20', subject: 'CS501 - Data Structures', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' },
                    { date: '2026-07-15', subject: 'CS502 - DBMS', faculty: 'Prof. Rostova', status: 'Absent', remarks: 'Out of town' }
                ],
                charts: {
                    subject_bar: { labels: ['CS501', 'CS502', 'CS503'], percentages: [91.6, 80.0, 100.0] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [85.0, 87.0, 88.5] },
                    pie: { labels: ['Present', 'Absent'], data: [27, 3] }
                }
            },
            3: {
                profile: { id: 3, name: 'David Miller', roll_no: 'CS2026-003', prn: 'PRN2024003', dept: 'Computer Science & Engineering', semester: 5, division: 'A', overall_pct: 65.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=David' },
                subjects: [
                    { code: 'CS501', name: 'Advanced Data Structures & Algorithms', faculty: 'Dr. Robert Vance', total: 10, present: 6, absent: 4, pct: 60.0 },
                    { code: 'CS502', name: 'Database Management Systems', faculty: 'Prof. Elena Rostova', total: 10, present: 7, absent: 3, pct: 70.0 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'CS501 - Data Structures', faculty: 'Dr. Vance', status: 'Absent', remarks: 'Unexcused' },
                    { date: '2026-07-19', subject: 'CS502 - DBMS', faculty: 'Prof. Rostova', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['CS501', 'CS502'], percentages: [60.0, 70.0] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [72.0, 68.0, 65.0] },
                    pie: { labels: ['Present', 'Absent'], data: [13, 7] }
                }
            },
            4: {
                profile: { id: 4, name: 'Emma Watson', roll_no: 'CS2026-004', prn: 'PRN2024004', dept: 'Computer Science & Engineering', semester: 5, division: 'B', overall_pct: 92.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Emma' },
                subjects: [
                    { code: 'CS501', name: 'Advanced Data Structures & Algorithms', faculty: 'Dr. Vance', total: 12, present: 11, absent: 1, pct: 91.6 },
                    { code: 'CS502', name: 'DBMS', faculty: 'Prof. Rostova', total: 13, present: 12, absent: 1, pct: 92.3 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'CS501', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['CS501', 'CS502'], percentages: [91.6, 92.3] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [90.0, 91.0, 92.0] },
                    pie: { labels: ['Present', 'Absent'], data: [23, 2] }
                }
            },
            5: {
                profile: { id: 5, name: 'Liam Gallagher', roll_no: 'CS2026-005', prn: 'PRN2024005', dept: 'Computer Science & Engineering', semester: 5, division: 'B', overall_pct: 50.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Liam' },
                subjects: [
                    { code: 'CS501', name: 'Advanced Data Structures', faculty: 'Dr. Vance', total: 10, present: 5, absent: 5, pct: 50.0 },
                    { code: 'CS502', name: 'DBMS', faculty: 'Prof. Rostova', total: 10, present: 5, absent: 5, pct: 50.0 }
                ],
                history: [
                    { date: '2026-07-17', subject: 'CS503', faculty: 'Dr. Vance', status: 'Absent', remarks: 'No notice' },
                    { date: '2026-07-16', subject: 'CS501', faculty: 'Dr. Vance', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['CS501', 'CS502'], percentages: [50.0, 50.0] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [60.0, 55.0, 50.0] },
                    pie: { labels: ['Present', 'Absent'], data: [10, 10] }
                }
            },
            6: {
                profile: { id: 6, name: 'Zoe Kravitz', roll_no: 'IT2026-010', prn: 'PRN2024010', dept: 'Information Technology', semester: 5, division: 'A', overall_pct: 82.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Zoe' },
                subjects: [
                    { code: 'IT501', name: 'Cloud Infrastructure & Security', faculty: 'Prof. Sarah Jenkins', total: 12, present: 10, absent: 2, pct: 83.3 },
                    { code: 'IT502', name: 'Web Architecture', faculty: 'Dr. Marcus Sterling', total: 10, present: 8, absent: 2, pct: 80.0 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'IT501 - Cloud', faculty: 'Prof. Jenkins', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['IT501', 'IT502'], percentages: [83.3, 80.0] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [80.0, 81.0, 82.0] },
                    pie: { labels: ['Present', 'Absent'], data: [18, 4] }
                }
            },
            7: {
                profile: { id: 7, name: 'Lucas Scott', roll_no: 'IT2026-011', prn: 'PRN2024011', dept: 'Information Technology', semester: 5, division: 'A', overall_pct: 78.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Lucas' },
                subjects: [
                    { code: 'IT501', name: 'Cloud Infrastructure & Security', faculty: 'Prof. Sarah Jenkins', total: 11, present: 8, absent: 3, pct: 72.7 },
                    { code: 'IT502', name: 'Web Architecture', faculty: 'Dr. Marcus Sterling', total: 11, present: 9, absent: 2, pct: 81.8 }
                ],
                history: [
                    { date: '2026-07-20', subject: 'IT502', faculty: 'Dr. Sterling', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['IT501', 'IT502'], percentages: [72.7, 81.8] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [76.0, 77.0, 78.0] },
                    pie: { labels: ['Present', 'Absent'], data: [17, 5] }
                }
            },
            8: {
                profile: { id: 8, name: 'Aria Montgomery', roll_no: 'AD2026-020', prn: 'PRN2024020', dept: 'AI & Data Science', semester: 5, division: 'A', overall_pct: 96.5, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Aria' },
                subjects: [
                    { code: 'AD501', name: 'Machine Learning Foundations', faculty: 'Dr. Alan Turing', total: 14, present: 14, absent: 0, pct: 100.0 },
                    { code: 'AD502', name: 'Neural Networks & Deep Learning', faculty: 'Dr. Alan Turing', total: 12, present: 11, absent: 1, pct: 91.6 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'AD501 - Machine Learning', faculty: 'Dr. Turing', status: 'Present', remarks: 'Excellent participation' }
                ],
                charts: {
                    subject_bar: { labels: ['AD501', 'AD502'], percentages: [100.0, 91.6] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [95.0, 96.0, 96.5] },
                    pie: { labels: ['Present', 'Absent'], data: [25, 1] }
                }
            },
            9: {
                profile: { id: 9, name: 'Noah Vance', roll_no: 'AD2026-021', prn: 'PRN2024021', dept: 'AI & Data Science', semester: 5, division: 'A', overall_pct: 89.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Noah' },
                subjects: [
                    { code: 'AD501', name: 'Machine Learning Foundations', faculty: 'Dr. Alan Turing', total: 11, present: 10, absent: 1, pct: 90.9 },
                    { code: 'AD502', name: 'Neural Networks & Deep Learning', faculty: 'Dr. Alan Turing', total: 11, present: 10, absent: 1, pct: 90.9 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'AD502', faculty: 'Dr. Turing', status: 'Present', remarks: 'On time' }
                ],
                charts: {
                    subject_bar: { labels: ['AD501', 'AD502'], percentages: [90.9, 90.9] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [87.0, 88.0, 89.0] },
                    pie: { labels: ['Present', 'Absent'], data: [20, 2] }
                }
            },
            10: {
                profile: { id: 10, name: 'Ethan Hunt', roll_no: 'EC2026-030', prn: 'PRN2024030', dept: 'Electronics & Comm. Eng.', semester: 5, division: 'A', overall_pct: 58.0, avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=Ethan' },
                subjects: [
                    { code: 'EC501', name: 'Digital Signal Processing', faculty: 'Prof. Sarah Jenkins', total: 10, present: 5, absent: 5, pct: 50.0 },
                    { code: 'EC502', name: 'VLSI Design', faculty: 'Dr. Marcus Sterling', total: 10, present: 6, absent: 4, pct: 60.0 }
                ],
                history: [
                    { date: '2026-07-21', subject: 'EC501', faculty: 'Prof. Jenkins', status: 'Absent', remarks: 'Unexcused' }
                ],
                charts: {
                    subject_bar: { labels: ['EC501', 'EC502'], percentages: [50.0, 60.0] },
                    monthly_trend: { labels: ['May', 'Jun', 'Jul'], percentages: [65.0, 60.0, 58.0] },
                    pie: { labels: ['Present', 'Absent'], data: [11, 9] }
                }
            }
        };

        const resData = mockStudentDb[studentId] || mockStudentDb[1];
        return res.end(JSON.stringify(resData));
    }

    if (action === 'department_report') {
        return res.end(JSON.stringify({
            stats: { avg_attendance: 83.7, highest_dept: 'AI & Data Science (88.5%)', lowest_dept: 'Mechanical Eng. (78.5%)', total_students: 1450 },
            dept_comparison: { labels: ['CE', 'AIDS', 'EE', 'BT', 'ME'], percentages: [84.2, 88.5, 81.0, 86.4, 78.5] },
            sem_comparison: { labels: ['Sem 1', 'Sem 3', 'Sem 5', 'Sem 7'], percentages: [86.5, 83.1, 81.4, 79.8] },
            subject_comparison: { labels: ['CE501 (DS)', 'AD501 (ML)', 'EE501 (Power)', 'BT501 (Genetics)', 'ME501 (Thermo)'], percentages: [88.0, 91.2, 81.0, 86.4, 78.5] },
            table: [
                { dept: 'Computer Engineering', code: 'CE', total_students: 450, avg_pct: 84.2, status: 'Excellent' },
                { dept: 'AI & Data Science', code: 'AIDS', total_students: 240, avg_pct: 88.5, status: 'Excellent' },
                { dept: 'Electrical Engineering', code: 'EE', total_students: 310, avg_pct: 81.0, status: 'Good' },
                { dept: 'Biotechnology', code: 'BT', total_students: 220, avg_pct: 86.4, status: 'Excellent' },
                { dept: 'Mechanical Engineering', code: 'ME', total_students: 230, avg_pct: 78.5, status: 'Good' }
            ]
        }));
    }

    if (action === 'low_attendance') {
        return res.end(JSON.stringify({
            summary: { threshold: 75, total_flagged: 3, critical_count: 1, warning_count: 2 },
            students: [
                { id: 3, roll_no: 'CS2026-003', name: 'David Miller', dept: 'CS', semester: 5, division: 'A', parent_phone: '+1 555-9003', attendance_pct: 65.0, status: 'Warning', color: 'orange' },
                { id: 5, roll_no: 'CS2026-005', name: 'Liam Gallagher', dept: 'CS', semester: 5, division: 'B', parent_phone: '+1 555-9005', attendance_pct: 50.0, status: 'Critical', color: 'red' }
            ]
        }));
    }

    res.end(JSON.stringify({ success: true }));
}

server.listen(PORT, '0.0.0.0', () => {
    console.log(`SATP Report Module Server running on http://localhost:${PORT}`);
});
