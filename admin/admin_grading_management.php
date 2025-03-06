<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_grade':
                try {
                    $stmt = $conn->prepare("SELECT full_marks FROM assignment WHERE AID = :aid");
                    $stmt->execute([':aid' => $_POST['assignment_id']]);
                    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($_POST['grade'] > $assignment['full_marks']) {
                        echo json_encode([
                            'status' => 'error', 
                            'message' => 'Grade cannot be greater than full marks'
                        ]);
                        exit();
                    }

                    $stmt = $conn->prepare("
                        INSERT INTO grade (SID, AID, graded_mark)
                        VALUES (:student_id, :assignment_id, :grade)
                        ON DUPLICATE KEY UPDATE graded_mark = :grade
                    ");
                    
                    $stmt->execute([
                        ':student_id' => $_POST['student_id'],
                        ':assignment_id' => $_POST['assignment_id'],
                        ':grade' => $_POST['grade']
                    ]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

            case 'generate_transcript':
                try {
                    $studentId = $_POST['student_id'];
                    $courseCode = $_POST['course_code'];
                    
                    $stmt = $conn->prepare("
                        SELECT 
                            s.ID,
                            s.full_name,
                            c.Code as course_code,
                            c.Title as course_title,
                            a.AID,
                            a.Title as assignment_title,
                            a.full_marks,
                            g.graded_mark
                        FROM personal_details s
                        JOIN enrollment e ON s.ID = e.SID
                        JOIN courses c ON e.Code = c.Code
                        JOIN assignment a ON c.Code = a.Code
                        LEFT JOIN grade g ON a.AID = g.AID AND s.ID = g.SID
                        WHERE s.ID = :student_id AND c.Code = :course_code
                        ORDER BY a.deadline
                    ");
                    
                    $stmt->execute([
                        ':student_id' => $studentId,
                        ':course_code' => $courseCode
                    ]);
                    
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode(['status' => 'success', 'data' => $results]);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <h2>Grade Management</h2>

            <div class="filter-section">
                <select id="courseSelect" onchange="loadAssignments()">
                    <option value="">Select Course</option>
                    <?php
                    $stmt = $conn->query("SELECT Code, Title FROM courses ORDER BY Code");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['Code']) . "'>" . 
                            htmlspecialchars($row['Code'] . ' - ' . $row['Title']) . "</option>";
                    }
                    ?>
                </select>

                <select id="assignmentSelect" onchange="loadStudentGrades()" disabled>
                    <option value="">Select Assignment</option>
                </select>
            </div>

            <div class="search-filter-container">
                <div class="search-box">
                    <input type="text" id="studentSearch" placeholder="Search by Student ID or Name...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="filter-options">
                    <select id="gradeFilter" class="grade-filter">
                        <option value="all">All Students</option>
                        <option value="graded">Graded</option>
                        <option value="ungraded">Ungraded</option>
                    </select>
                    <button onclick="resetFilters()" class="reset-btn">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="table-section">
                <h3>Student Grades</h3>
                <table id="gradesTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="gradesTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="gradeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('gradeModal')">&times;</span>
            <h2>Update Grade</h2>
            <form id="gradeForm" onsubmit="handleGradeSubmit(event)">
                <input type="hidden" name="action" value="update_grade">
                <input type="hidden" id="gradeStudentId" name="student_id">
                <input type="hidden" id="gradeAssignmentId" name="assignment_id">
                
                <div class="form-group">
                    <label for="grade">Grade (Max: <span id="maxGrade">100</span>)</label>
                    <input type="number" id="grade" name="grade" required min="0">
                </div>
                
                <button type="submit" class="submit-btn">Save Grade</button>
            </form>
        </div>
    </div>

    <div id="transcriptModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('transcriptModal')">&times;</span>
            <h2>Student Transcript</h2>
            <div id="transcriptContent"></div>
        </div>
    </div>

    <script>
    let currentCourse = '';
    let currentAssignment = '';

    async function loadAssignments() {
        currentCourse = document.getElementById('courseSelect').value;
        const assignmentSelect = document.getElementById('assignmentSelect');
        
        assignmentSelect.disabled = !currentCourse;
        assignmentSelect.innerHTML = '<option value="">Select Assignment</option>';
        
        if (!currentCourse) return;

        try {
            const response = await fetch(`get_assignments.php?course=${currentCourse}`);
            const assignments = await response.json();
            
            assignments.forEach(assignment => {
                const option = document.createElement('option');
                option.value = assignment.AID;
                option.textContent = assignment.Title;
                assignmentSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function loadStudentGrades() {
        currentAssignment = document.getElementById('assignmentSelect').value;
        if (!currentAssignment) return;

        try {
            const response = await fetch(`get_grades.php?assignment=${currentAssignment}`);
            const grades = await response.json();
            
            document.getElementById('gradesTableBody').innerHTML = grades.map(grade => `
                <tr>
                    <td>${grade.ID}</td>
                    <td>${grade.full_name}</td>
                    <td>${grade.graded_mark || '-'}</td>
                    <td>
                        <button onclick="showGradeModal('${grade.ID}', ${grade.full_marks})" class="action-btn grade-btn">
                            <i class="fas fa-star"></i>
                        </button>
                        <button onclick="generateTranscript('${grade.ID}')" class="action-btn transcript-btn">
                            <i class="fas fa-file-alt"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function showGradeModal(studentId, fullMarks) {
        document.getElementById('gradeStudentId').value = studentId;
        document.getElementById('gradeAssignmentId').value = currentAssignment;
        document.getElementById('maxGrade').textContent = fullMarks;
        document.getElementById('grade').max = fullMarks;
        document.getElementById('gradeModal').style.display = 'block';
    }

    async function handleGradeSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_grading_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('gradeModal');
                loadStudentGrades();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function generateTranscript(studentId) {
        try {
            const response = await fetch('admin_grading_management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=generate_transcript&student_id=${studentId}&course_code=${currentCourse}`
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                displayTranscript(result.data);
                document.getElementById('transcriptModal').style.display = 'block';
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayTranscript(data) {
        if (!data || data.length === 0) {
            document.getElementById('transcriptContent').innerHTML = '<p>No data available</p>';
            return;
        }

        const student = data[0];
        const totalMarks = data.reduce((sum, item) => sum + (item.graded_mark || 0), 0);
        const totalPossible = data.reduce((sum, item) => sum + parseInt(item.full_marks), 0);
                // Calculate individual percentages for each graded assignment
                const percentages = data
            .filter(item => item.graded_mark !== null)
            .map(item => (item.graded_mark / item.full_marks) * 100);
        
        // Calculate overall percentage as average of individual percentages
        const averagePercentage = percentages.length > 0 
            ? (percentages.reduce((sum, percentage) => sum + percentage, 0) / percentages.length).toFixed(2)
            : 0;

        const html = `
            <div class="transcript-print">
                <div class="transcript-header">
                    <img src="../images/logo2.png" alt="University Logo" class="transcript-logo">
                    <h2>PTH University</h2>
                    <h3>Academic Transcript</h3>
                    <div class="transcript-info">
                        <div class="info-left">
                            <p><strong>Student ID:</strong> ${student.ID}</p>
                            <p><strong>Full Name:</strong> ${student.full_name}</p>
                        </div>
                        <div class="info-right">
                            <p><strong>Course:</strong> ${student.course_code}</p>
                            <p><strong>Course Title:</strong> ${student.course_title}</p>
                            <p><strong>Date Issued:</strong> ${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                </div>

                <div class="transcript-body">
                    <table class="transcript-table">
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Full Marks</th>
                                <th>Grade</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(item => `
                                <tr>
                                    <td>${item.assignment_title}</td>
                                    <td>${item.full_marks}</td>
                                    <td>${item.graded_mark || '-'}</td>
                                    <td>${item.graded_mark ? ((item.graded_mark / item.full_marks) * 100).toFixed(2) + '%' : '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Overall Performance</strong></td>
                                <td><strong>${averagePercentage}%</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="transcript-footer">
                    <div class="signature-line">
                        <div class="signature-box">
                            <p>_____________________</p>
                            <p>Academic Registrar</p>
                        </div>
                        <div class="signature-box">
                            <p>_____________________</p>
                            <p>Dean of Faculty</p>
                        </div>
                    </div>
                    <div class="transcript-note">
                        <p>This transcript is not valid without the university seal and authorized signatures.</p>
                        <p>Date Generated: ${new Date().toLocaleString()}</p>
                    </div>
                </div>
            </div>
        `;
            const downloadBtn = `
            <div class="transcript-actions">
                <button onclick="downloadTranscriptPDF('${student.ID}', '${student.course_code}')" class="action-btn download-btn">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        `;
        
        document.getElementById('transcriptContent').innerHTML = html + downloadBtn;
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function printTranscript() {
        const content = document.getElementById('transcriptContent').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Student Transcript</title>');
        printWindow.document.write('<link rel="stylesheet" href="admin_style.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

        // Add the download function
    function downloadTranscriptPDF(studentId, courseCode) {
        window.location.href = `generate_transcript_pdf.php?student_id=${studentId}&course_code=${courseCode}`;
    }

    
    let allGrades = []; // Store all grades for filtering

    async function loadStudentGrades() {
        currentAssignment = document.getElementById('assignmentSelect').value;
        if (!currentAssignment) return;

        try {
            const response = await fetch(`get_grades.php?assignment=${currentAssignment}`);
            allGrades = await response.json();
            filterAndDisplayGrades();
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function filterAndDisplayGrades() {
        const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
        const filterValue = document.getElementById('gradeFilter').value;
        
        let filteredGrades = allGrades.filter(grade => {
            const matchesSearch = (grade.ID.toLowerCase().includes(searchTerm) ||
                                grade.full_name.toLowerCase().includes(searchTerm));
            
            if (filterValue === 'graded') {
                return matchesSearch && grade.graded_mark != null;
            } else if (filterValue === 'ungraded') {
                return matchesSearch && grade.graded_mark == null;
            }
            return matchesSearch;
        });

        displayGrades(filteredGrades);
    }

    function displayGrades(grades) {
        document.getElementById('gradesTableBody').innerHTML = grades.map(grade => `
            <tr>
                <td>${grade.ID}</td>
                <td>${grade.full_name}</td>
                <td>${grade.graded_mark || '-'}</td>
                <td>
                    <button onclick="showGradeModal('${grade.ID}', ${grade.full_marks})" class="action-btn grade-btn">
                        <i class="fas fa-star"></i>
                    </button>
                    <button onclick="generateTranscript('${grade.ID}')" class="action-btn transcript-btn">
                        <i class="fas fa-file-alt"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function resetFilters() {
        document.getElementById('studentSearch').value = '';
        document.getElementById('gradeFilter').value = 'all';
        filterAndDisplayGrades();
    }

    // Add event listeners
    document.getElementById('studentSearch').addEventListener('input', filterAndDisplayGrades);
    document.getElementById('gradeFilter').addEventListener('change', filterAndDisplayGrades);


    </script>
</body>
</html>