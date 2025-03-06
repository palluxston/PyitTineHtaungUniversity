<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}

// Handle CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_course':
                try {
                    $conn->beginTransaction();
            
                    // Check if course code already exists
                    $stmt = $conn->prepare("SELECT Code FROM courses WHERE Code = :code");
                    $stmt->execute([':code' => $_POST['course_id']]);
                    if ($stmt->fetch()) {
                        echo json_encode(['status' => 'error', 'message' => 'Course code already exists']);
                        exit();
                    }
            
                    $stmt = $conn->prepare("
                        INSERT INTO courses (Code, Title, Credits, semester, FID) 
                        VALUES (:course_id, :course_name, :credits, :status, :faculty_id)
                    ");
                    
                    $stmt->execute([
                        ':course_id' => $_POST['course_id'],
                        ':course_name' => $_POST['course_name'],
                        ':credits' => $_POST['credits'],
                        ':status' => $_POST['status'],
                        ':faculty_id' => !empty($_POST['faculty_id']) ? $_POST['faculty_id'] : null
                    ]);
            
                    $conn->commit();
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    $conn->rollBack();
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

                case 'assign_faculty':
                try {
                    $stmt = $conn->prepare("
                        UPDATE courses 
                        SET FID = :faculty_id 
                        WHERE Code = :course_id
                    ");
                    
                    $stmt->execute([
                        ':course_id' => $_POST['course_id'],
                        ':faculty_id' => $_POST['faculty_id']
                    ]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

                case 'enroll_student':
                    try {
                        $conn->beginTransaction();
                        $currentDate = date('Y-m-d');
                        $studentIds = $_POST['student_ids'];
                        
                        $stmt = $conn->prepare("
                            INSERT INTO enrollment (SID, Code, date_enrolled) 
                            VALUES (:student_id, :code, :date_enrolled)
                        ");
                        
                        foreach ($studentIds as $studentId) {
                            $stmt->execute([
                                ':student_id' => $studentId,
                                ':code' => $_POST['course_id'],
                                ':date_enrolled' => $currentDate
                            ]);
                        }
                        
                        $conn->commit();
                        echo json_encode(['status' => 'success']);
                        exit();
                    } catch(PDOException $e) {
                        $conn->rollBack();
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        exit();
                    }
                    break;
                
                case 'remove_enrollment':
                try {
                    $stmt = $conn->prepare("
                        DELETE FROM enrollment 
                        WHERE SID = :student_id 
                        AND Code = :course_id
                    ");
                    
                    $stmt->execute([
                        ':student_id' => $_POST['student_id'],
                        ':course_id' => $_POST['course_id']
                    ]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

                case 'delete_course':
                    try {
                        $conn->beginTransaction();
                
                        // Delete enrollments first (cascade will handle this automatically)
                        $stmt = $conn->prepare("DELETE FROM courses WHERE Code = :course_id");
                        $stmt->execute([':course_id' => $_POST['course_id']]);
                        
                        $conn->commit();
                        echo json_encode(['status' => 'success']);
                        exit();
                    } catch(PDOException $e) {
                        $conn->rollBack();
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        exit();
                    }
                    break;
                case 'edit_course':
                    try {
                        $stmt = $conn->prepare("
                            UPDATE courses 
                            SET Title = :course_name,
                                Credits = :credits,
                                semester = :status
                            WHERE Code = :course_id
                        ");
                        
                        $stmt->execute([
                            ':course_id' => $_POST['course_id'],
                            ':course_name' => $_POST['course_name'],
                            ':credits' => $_POST['credits'],
                            ':status' => $_POST['status']
                        ]);
                        
                        echo json_encode(['status' => 'success']);
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
    <title>Course Management - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <h2>Course Management</h2>
                <button class="add-btn" onclick="showModal('courseModal', 'add')">
                    <i class="fas fa-plus"></i> Add New Course
                </button>
            </div>

            <div class="search-filter-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search courses...">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="statusFilter">
                <option value="">Sort By</option>
                <option value="code_asc">Course Code (A-Z)</option>
                <option value="code_desc">Course Code (Z-A)</option>
                <option value="students_asc">Students (Low to High)</option>
                <option value="students_desc">Students (High to Low)</option>
                </select>
            </div>

            <div class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Status</th>
                                <th>Faculty</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="coursesTableBody">
                            <?php
                                $stmt = $conn->query("
                                SELECT c.Code, c.Title as course_name, c.Credits, c.semester,
                                    p1.full_name as faculty_name,
                                    COUNT(DISTINCT e.SID) as student_count
                                FROM courses c
                                LEFT JOIN personal_details p1 ON c.FID = p1.ID
                                LEFT JOIN enrollment e ON c.Code = e.Code
                                GROUP BY c.Code
                                ORDER BY c.Code DESC
                            ");
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr data-id='" . htmlspecialchars($row['Code']) . "'>";
                                echo "<td>" . htmlspecialchars($row['Code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Credits']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['semester']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['faculty_name'] ?? 'Not Assigned') . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_count']) . "</td>";
                                echo "<td>
                                    <button class='action-btn edit-btn' onclick='editCourse(\"" . htmlspecialchars($row['Code']) . "\")'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='action-btn assign-btn' onclick='showAssignModal(\"" . htmlspecialchars($row['Code']) . "\")'>
                                        <i class='fas fa-user-plus'></i>
                                    </button>
                                    <button class='action-btn enroll-btn' onclick='showEnrollModal(\"" . htmlspecialchars($row['Code']) . "\")'>
                                        <i class='fas fa-users'></i>
                                    </button>
                                    <button class='action-btn delete-btn' onclick='deleteCourse(\"" . htmlspecialchars($row['Code']) . "\")'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Course ModalS-->
        <div id="courseModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('courseModal')">&times;</span>
            <h2 id="modalTitle">Add New Course</h2>
            <form id="courseForm" onsubmit="handleCourseSubmit(event)">
                <input type="hidden" id="action" name="action" value="add_course">
                
                <div class="form-group">
                    <label for="courseId">Course Code</label>
                    <input type="text" id="courseId" name="course_id" required>
                </div>
                
                <div class="form-group">
                    <label for="courseName">Course Title</label>
                    <input type="text" id="courseName" name="course_name" required>
                </div>
                
                <div class="form-group">
                    <label for="credits">Credits</label>
                    <input type="number" id="credits" name="credits" required min="1" max="20">
                </div>
                
                <div class="form-group">
                    <label for="status">Semester</label>
                    <select id="status" name="status" required>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="facultyId">Assign Faculty (Optional)</label>
                    <select id="facultyId" name="faculty_id">
                        <option value="">Select Faculty</option>
                        <?php
                        $stmt = $conn->query("
                            SELECT ID, full_name 
                            FROM personal_details 
                            WHERE Role = 'Faculty'
                            ORDER BY full_name
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($row['ID']) . "'>" . 
                                htmlspecialchars($row['full_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Save</button>
            </form>
        </div>
        </div>
    <!-- Assign Faculty Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('assignModal')">&times;</span>
            <h2>Assign Faculty</h2>
            <div id="currentFaculty" class="current-assignment">
                <h3>Current Faculty Member</h3>
                <p id="currentFacultyName">None Assigned</p>
            </div>
            <form id="assignForm" onsubmit="handleAssignSubmit(event)">
                <input type="hidden" id="assignCourseId" name="course_id">
                <input type="hidden" name="action" value="assign_faculty">
                
                <div class="form-group">
                    <label for="facultyId">Select New Faculty</label>
                    <select id="facultyId" name="faculty_id" required>
                        <option value="">Select Faculty</option>
                        <?php
                        $stmt = $conn->query("
                            SELECT ID, full_name 
                            FROM personal_details 
                            WHERE Role = 'Faculty'
                            ORDER BY full_name
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($row['ID']) . "'>" . 
                                htmlspecialchars($row['full_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Assign</button>
            </form>
        </div>
    </div>

    <!-- Enroll Students Modal -->
    <div id="enrollModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('enrollModal')">&times;</span>
            <h2>Enroll Students</h2>
            <div class="enrolled-students">
                <h3>Currently Enrolled Students</h3>
                <div class="table-container" id="enrolledStudentsTable"></div>
            </div>
            <form id="enrollForm" onsubmit="handleEnrollSubmit(event)">
                <input type="hidden" id="enrollCourseId" name="course_id">
                <input type="hidden" name="action" value="enroll_student">
                
                <div class="form-group">
                    <h3>Available Students</h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody id="availableStudentsBody"></tbody>
                        </table>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Enroll Selected</button>
            </form>
        </div>
    </div>

    <script>

    async function showAssignModal(courseId) {
        document.getElementById('assignCourseId').value = courseId;
        document.getElementById('assignModal').style.display = 'block';
        
        try {
            const response = await fetch(`get_course_faculty.php?code=${courseId}`);
            const data = await response.json();
            
            document.getElementById('currentFacultyName').textContent = 
                data.faculty_name ? data.faculty_name : 'None Assigned';
            
            // Update faculty dropdown to exclude current faculty
            const facultySelect = document.getElementById('facultyId');
            facultySelect.innerHTML = '<option value="">Select Faculty</option>';
            
            data.available_faculty.forEach(faculty => {
                facultySelect.innerHTML += `
                    <option value="${faculty.ID}">${faculty.full_name}</option>
                `;
            });
        } catch (error) {
            alert('Error loading faculty data: ' + error.message);
        }
    }

    async function showEnrollModal(courseId) {
        document.getElementById('enrollCourseId').value = courseId;
        document.getElementById('enrollModal').style.display = 'block';
        
        try {
            const response = await fetch(`get_course_students.php?code=${courseId}`);
            const data = await response.json();
            
            // Check if search box already exists
            let searchBox = document.querySelector('#enrollModal .search-filter-container');
            if (!searchBox) {
                // Add single search bar at the top of the modal
                const modalContent = document.querySelector('#enrollModal .modal-content');
                searchBox = document.createElement('div');
                searchBox.className = 'search-filter-container';
                searchBox.innerHTML = `
                    <div class="search-box">
                        <input type="text" id="studentSearch" placeholder="Search all students...">
                        <i class="fas fa-search"></i>
                    </div>
                `;
                modalContent.insertBefore(searchBox, modalContent.firstChild.nextSibling);
            }
            
            // Display enrolled students
            const enrolledTable = document.getElementById('enrolledStudentsTable');
            enrolledTable.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date Enrolled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="enrolled-students-body">
                        ${data.enrolled_students.map(student => `
                            <tr>
                                <td>${student.ID}</td>
                                <td>${student.full_name}</td>
                                <td>${student.date_enrolled}</td>
                                <td>
                                    <button type="button" class="action-btn delete-btn" 
                                        onclick="removeEnrollment('${student.ID}', '${courseId}')">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            // Update available students table
            const availableStudentsBody = document.getElementById('availableStudentsBody');
            availableStudentsBody.innerHTML = data.available_students.map(student => `
                <tr>
                    <td>${student.ID}</td>
                    <td>${student.full_name}</td>
                    <td>
                        <input type="checkbox" name="student_ids[]" value="${student.ID}">
                    </td>
                </tr>
            `).join('');

            // Add event listener for the search
            const studentSearch = document.getElementById('studentSearch');
            if (studentSearch) {
                // Remove existing event listeners
                const newStudentSearch = studentSearch.cloneNode(true);
                studentSearch.parentNode.replaceChild(newStudentSearch, studentSearch);
                
                // Add new event listener
                newStudentSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    filterStudentTables(searchTerm);
                });
            }

        } catch (error) {
            alert('Error loading student data: ' + error.message);
        }
    }


    function filterStudentTables(searchTerm) {
        const enrolledRows = document.querySelectorAll('.enrolled-students-body tr');
        const availableRows = document.querySelectorAll('#availableStudentsBody tr');
        
        const filterRow = (row) => {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            const matches = id.includes(searchTerm) || name.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
        };

        enrolledRows.forEach(filterRow);
        availableRows.forEach(filterRow);
    }


    function filterTable(searchTerm, selector) {
        const rows = document.querySelectorAll(selector);
        searchTerm = searchTerm.toLowerCase();

        rows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            const matches = id.includes(searchTerm) || name.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
        });
    }

        async function handleAssignSubmit(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('admin_course_management.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.status === 'success') {
                    closeModal('assignModal');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

    async function handleEnrollSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_course_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('enrollModal');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function handleCourseSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_course_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('courseModal');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    function showModal(modalId, mode) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';
    
    if (modalId === 'courseModal') {
        // Reset form
        document.getElementById('courseForm').reset();
        
        if (mode === 'add') {
            // Set up for adding new course
            document.getElementById('modalTitle').textContent = 'Add New Course';
            document.getElementById('action').value = 'add_course';
            document.getElementById('courseId').readOnly = false;
        }
    }
}

    async function removeEnrollment(studentId, courseId) {
        if (!confirm('Are you sure you want to remove this student from the course?')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'remove_enrollment');
            formData.append('student_id', studentId);
            formData.append('course_id', courseId);
            
            const response = await fetch('admin_course_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                // Refresh the enrollment modal
                showEnrollModal(courseId);
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function deleteCourse(courseId) {
        if (!confirm('Are you sure you want to delete this course? This will remove all enrollments and faculty assignments.')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'delete_course');
            formData.append('course_id', courseId);
            
            const response = await fetch('admin_course_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                const row = document.querySelector(`tr[data-id="${courseId}"]`);
                if (row) {
                    row.remove();
                } else {
                    location.reload();
                }
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
    async function editCourse(courseId) {
        try {
            const response = await fetch(`get_course_details.php?code=${courseId}`);
            const course = await response.json();
            
            // Set form to edit mode
            document.getElementById('modalTitle').textContent = 'Edit Course';
            document.getElementById('action').value = 'edit_course';
            document.getElementById('courseId').value = course.Code;
            
            // Populate form fields
            document.getElementById('courseName').value = course.Title;
            document.getElementById('credits').value = course.Credits;
            document.getElementById('status').value = course.semester;
            
            // Show modal
            document.getElementById('courseModal').style.display = 'block';
        } catch (error) {
            alert('Error loading course data: ' + error.message);
        }
    }

    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('coursesTableBody');

    // Add sort buttons to table headers
    document.querySelectorAll('th').forEach(th => {
        if (['Course ID', 'Students'].includes(th.textContent)) {
            th.style.cursor = 'pointer';
            th.innerHTML += ' <i class="fas fa-sort"></i>';
            th.addEventListener('click', () => sortTable(th.cellIndex));
        }
    });

    function filterAndSortTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortValue = statusFilter.value;
        const rows = Array.from(tableBody.getElementsByTagName('tr'));

        // First, filter by search term
        rows.forEach(row => {
            const courseId = row.cells[0].textContent.toLowerCase();
            const courseName = row.cells[1].textContent.toLowerCase();
            const faculty = row.cells[4].textContent.toLowerCase();
            
            const matchesSearch = courseId.includes(searchTerm) || 
                                courseName.includes(searchTerm) ||
                                faculty.includes(searchTerm);

            row.style.display = matchesSearch ? '' : 'none';
        });

        // Then sort if a sort option is selected
        if (sortValue) {
            rows.sort((a, b) => {
                const [field, direction] = sortValue.split('_');
                const multiplier = direction === 'asc' ? 1 : -1;

                if (field === 'code') {
                    return multiplier * a.cells[0].textContent.localeCompare(b.cells[0].textContent);
                } else if (field === 'students') {
                    return multiplier * (parseInt(a.cells[5].textContent) - parseInt(b.cells[5].textContent));
                }
                return 0;
            });

            // Reappend sorted rows
            rows.forEach(row => tableBody.appendChild(row));
        }
    }

    function sortTable(columnIndex) {
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const direction = this.asc ? -1 : 1;
        this.asc = !this.asc;

        const sortedRows = rows.sort((a, b) => {
            let valueA = a.cells[columnIndex].textContent;
            let valueB = b.cells[columnIndex].textContent;

            // Handle numeric sorting for student count
            if (columnIndex === 5) { // Students column
                return direction * (parseInt(valueA) - parseInt(valueB));
            }
            // Handle course ID sorting
            if (columnIndex === 0) { // Course ID column
                return direction * valueA.localeCompare(valueB);
            }
            return 0;
        });

        // Update table with sorted rows
        sortedRows.forEach(row => tableBody.appendChild(row));
    }

    // Event listeners
    searchInput.addEventListener('input', filterAndSortTable);
    statusFilter.addEventListener('change', filterAndSortTable);
    // Update the closeModal function to clean up the search box
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
        
        // Remove the search box if it's the enroll modal
        if (modalId === 'enrollModal') {
            const searchBox = modal.querySelector('.search-filter-container');
            if (searchBox) {
                searchBox.remove();
            }
        }
    }


    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
</script>
</body>
</html>