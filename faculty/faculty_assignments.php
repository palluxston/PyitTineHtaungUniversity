<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Faculty') {
    header("Location: ../public/portal.php");
    exit();
}

// Initialize error and success messages
$error = '';
$success = '';

// Get faculty information
try {
    $stmt = $conn->prepare("
        SELECT * FROM personal_details 
        WHERE ID = :faculty_id
    ");
    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle assignment creation
    if (isset($_POST['create_assignment'])) {
        try {
            // Check if assignment ID already exists
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM assignment WHERE AID = :aid");
            $check_stmt->bindParam(':aid', $_POST['aid']);
            $check_stmt->execute();
            
            if ($check_stmt->fetchColumn() > 0) {
                $error = "Assignment ID already exists!";
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO assignment (AID, Code, Title, deadline, full_marks)
                    VALUES (:aid, :code, :title, :deadline, :full_marks)
                ");
                
                $stmt->bindParam(':aid', $_POST['aid']);
                $stmt->bindParam(':code', $_POST['course_code']);
                $stmt->bindParam(':title', $_POST['title']);
                $stmt->bindParam(':deadline', $_POST['deadline']);
                $stmt->bindParam(':full_marks', $_POST['full_marks']);
                
                if($stmt->execute()) {
                    $success = "Assignment created successfully!";
                }
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
    
    // Handle assignment editing
    elseif (isset($_POST['edit_assignment'])) {
        try {
            $stmt = $conn->prepare("
                UPDATE assignment 
                SET Title = :title, 
                    deadline = :deadline, 
                    full_marks = :full_marks 
                WHERE AID = :aid AND Code = :code
            ");
            
            $stmt->bindParam(':title', $_POST['title']);
            $stmt->bindParam(':deadline', $_POST['deadline']);
            $stmt->bindParam(':full_marks', $_POST['full_marks']);
            $stmt->bindParam(':aid', $_POST['aid']);
            $stmt->bindParam(':code', $_POST['course_code']);
            
            if($stmt->execute()) {
                $success = "Assignment updated successfully!";
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
    
    // Handle assignment deletion
    elseif (isset($_POST['delete_assignment'])) {
        try {
            // First check if there are any submissions
            $check_stmt = $conn->prepare("
                SELECT COUNT(*) FROM submission 
                WHERE AID = :aid AND Code = :code
            ");
            $check_stmt->bindParam(':aid', $_POST['aid']);
            $check_stmt->bindParam(':code', $_POST['course_code']);
            $check_stmt->execute();
            
            if ($check_stmt->fetchColumn() > 0) {
                $error = "Cannot delete assignment with existing submissions!";
            } else {
                $stmt = $conn->prepare("
                    DELETE FROM assignment 
                    WHERE AID = :aid AND Code = :code
                ");
                $stmt->bindParam(':aid', $_POST['aid']);
                $stmt->bindParam(':code', $_POST['course_code']);
                
                if($stmt->execute()) {
                    $success = "Assignment deleted successfully!";
                }
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Get all assignments for this faculty
try {
    $stmt = $conn->prepare("
        SELECT a.*, c.Title as CourseTitle 
        FROM assignment a
        JOIN courses c ON a.Code = c.Code
        WHERE c.FID = :faculty_id
        ORDER BY a.deadline ASC
    ");
    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
    $stmt->execute();
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Get faculty's courses for the dropdown
try {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE FID = :faculty_id");
    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments - Pyit Tine Htaung University</title>
    <link rel="stylesheet" href="../public/css/dashboard_style.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .assignment-section {
            margin-bottom: 30px;
        }
        .assignment-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #004080;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', sans-serif;
        }
        .assignment-table {
            width: 100%;
            border-collapse: collapse;
        }
        .assignment-table th, .assignment-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .assignment-table th {
            background-color: #004080;
            color: white;
        }
        .create-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .create-btn:hover {
            background-color: #218838;
        }
        .view-grades-btn {
            background-color: #004080;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .nav-links {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            background-color: #004080;
            padding: 15px;
            border-radius: 8px;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }

        .assignment-form {
            font-family: 'Roboto', sans-serif;
            position: relative;
        }
        .form-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-description {
            margin-bottom: 20px;
            color: #666;
        }
        .action-btns {
            display: flex;
            gap: 10px;
        }
        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .edit-btn {
            background-color: #ffc107;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-assignment-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-family: 'Roboto', sans-serif;
        }

        .edit-assignment-form input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .edit-assignment-form .form-title {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <div class="header-container">
                <div class="logo">
                    <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo">
                </div>
                <div class="user-info">
                    <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($faculty['full_name']); ?></span>
                    <span><i class="fas fa-chalkboard-teacher"></i> Faculty</span>
                </div>
            </div>
            <a href="../public/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h2>Assignment Management</h2>
            <p>Create and manage course assignments</p>
        </div>

        <div class="nav-links">
            <a href="faculty_home.php" class="nav-link">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="faculty_courses.php" class="nav-link">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="faculty_assignments.php" class="nav-link active">
                <i class="fas fa-tasks"></i> Assignments
            </a>
            <a href="faculty_grade.php" class="nav-link">
                <i class="fas fa-star"></i> Grades
            </a>
        </div>

        <div class="assignment-section">
        <div class="assignment-form" id="assignmentForm">
        <div class="form-title">
            <h3><i class="fas fa-plus"></i> <span id="formTitle">Create New Assignment</span></h3>
            <button type="button" class="cancel-btn" onclick="resetForm()" style="display: none;" id="cancelBtn">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
        <div class="form-description">
            Fill in the details below to create a new assignment for your course.
        </div>
        <form method="POST" id="assignmentFormElement">
            <input type="hidden" name="form_type" id="formType" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Course:</label>
                    <select name="course_code" required id="courseSelect">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM courses WHERE FID = :faculty_id");
                        $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
                        $stmt->execute();
                        while($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($course['Code']) . "'>" . 
                                htmlspecialchars($course['Title']) . " (" . htmlspecialchars($course['Code']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Assignment ID:</label>
                    <input type="text" name="aid" required id="assignmentId">
                </div>
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required id="assignmentTitle" 
                        placeholder="Enter assignment title">
                </div>
                <div class="form-group">
                    <label>Deadline:</label>
                    <input type="date" name="deadline" required id="assignmentDeadline">
                </div>
                <div class="form-group">
                    <label>Full Marks:</label>
                    <input type="number" name="full_marks" required id="assignmentMarks" 
                        min="0" max="100" placeholder="Enter maximum marks">
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" name="create_assignment" class="create-btn" id="submitBtn">
                    <i class="fas fa-plus"></i> Create Assignment
                </button>
            </div>
        </form>
    </div>

            <div class="dashboard-card">
                <h3><i class="fas fa-list"></i> Current Assignments</h3>
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Assignment ID</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Full Marks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    try {
                        $stmt = $conn->prepare("
                            SELECT a.*, c.Title as CourseTitle 
                            FROM assignment a
                            JOIN courses c ON a.Code = c.Code
                            WHERE c.FID = :faculty_id
                            ORDER BY a.deadline ASC
                        ");
                        $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
                        $stmt->execute();
                        
                        while($assignment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($assignment['CourseTitle']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['AID']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['Title']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['deadline']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['full_marks']) . "</td>";
                            echo "<td class='action-btns'>";
                            
                            // Properly escape the JSON for JavaScript
                            $assignmentJson = htmlspecialchars(json_encode($assignment), ENT_QUOTES);
                            
                            echo "<button class='edit-btn' onclick='editAssignment(`{$assignmentJson}`)'>";
                            echo "<i class='fas fa-edit'></i> Edit</button>";
                            
                            echo "<button class='delete-btn' onclick='deleteAssignment(`{$assignment['AID']}`, `{$assignment['Code']}`)'>";
                            echo "<i class='fas fa-trash'></i> Delete</button>";
                            
                            echo "<button class='view-grades-btn' onclick='viewGrades(`{$assignment['AID']}`)'>";
                            echo "<i class='fas fa-star'></i> View Grades</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } catch(PDOException $e) {
                        echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
             
            <div class="edit-assignment-form" id="editAssignmentForm" style="display: none;">
            <div class="form-title">
                <h3><i class="fas fa-edit"></i> Edit Assignment</h3>
                <button type="button" class="cancel-btn" onclick="closeEditForm()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
            <form method="POST" id="editFormElement">
                <input type="hidden" name="form_type" value="edit">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Course:</label>
                        <input type="text" id="editCourseTitle" readonly>
                        <input type="hidden" name="course_code" id="editCourseCode">
                    </div>
                    <div class="form-group">
                        <label>Assignment ID:</label>
                        <input type="text" name="aid" id="editAssignmentId" readonly>
                    </div>
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required id="editAssignmentTitle" 
                            placeholder="Enter assignment title">
                    </div>
                    <div class="form-group">
                        <label>Deadline:</label>
                        <input type="date" name="deadline" required id="editAssignmentDeadline">
                    </div>
                    <div class="form-group">
                        <label>Full Marks:</label>
                        <input type="number" name="full_marks" required id="editAssignmentMarks" 
                            min="0" max="100" placeholder="Enter maximum marks">
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" name="edit_assignment" class="create-btn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        </div>
    </div>

    <script>
        function viewGrades(assignmentId) {
            window.location.href = `faculty_grade.php?assignment=${assignmentId}`;
        }
        function editAssignment(assignmentData) {
            const assignment = JSON.parse(assignmentData);
            
            // Fill the edit form
            document.getElementById('editCourseCode').value = assignment.Code;
            document.getElementById('editCourseTitle').value = assignment.CourseTitle;
            document.getElementById('editAssignmentId').value = assignment.AID;
            document.getElementById('editAssignmentTitle').value = assignment.Title;
            document.getElementById('editAssignmentDeadline').value = assignment.deadline.split(' ')[0]; // Get only the date part
            document.getElementById('editAssignmentMarks').value = assignment.full_marks;
            
            // Show the edit form
            document.getElementById('editAssignmentForm').style.display = 'block';
            document.getElementById('editAssignmentForm').scrollIntoView({ behavior: 'smooth' });
        }

        // Add this new function
        function closeEditForm() {
            document.getElementById('editAssignmentForm').style.display = 'none';
        }

        function resetForm() {
            document.getElementById('formTitle').textContent = 'Create New Assignment';
            document.getElementById('formType').value = 'create';
            document.getElementById('assignmentFormElement').reset();
            document.getElementById('assignmentId').readOnly = false;
            document.getElementById('courseSelect').disabled = false;
            document.getElementById('cancelBtn').style.display = 'none';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus"></i> Create Assignment';
            document.getElementById('submitBtn').name = 'create_assignment';
        }

        // Enhance datetime-local input with current date
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('assignmentDeadline').min = now.toISOString().slice(0,16);
        });

        function deleteAssignment(aid, code) {
            if (confirm('Are you sure you want to delete this assignment?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="aid" value="${aid}">
                    <input type="hidden" name="course_code" value="${code}">
                    <input type="hidden" name="delete_assignment" value="1">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

    </script>
</body>
</html>