
<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Faculty') {
    header("Location: ../public/portal.php");
    exit();
}

// Initialize variables
$error = '';
$success = '';
$grades = [];
$selectedAssignment = isset($_GET['assignment']) ? $_GET['assignment'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Get faculty information
try {
    $stmt = $conn->prepare("SELECT * FROM personal_details WHERE ID = :faculty_id");
    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Get available assignments for the faculty
try {
    $stmt = $conn->prepare("
        SELECT DISTINCT 
            a.AID, 
            a.Title, 
            c.Title as CourseTitle,
            a.deadline,
            a.full_marks,
            c.Code
        FROM assignment a
        JOIN courses c ON a.Code = c.Code
        WHERE c.FID = :faculty_id
        ORDER BY a.deadline DESC
    ");
    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
    $stmt->execute();
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Handle grade submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_grade'])) {
    try {
        // Check if grade already exists
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM grade WHERE SID = :sid AND AID = :aid");
        $check_stmt->bindParam(':sid', $_POST['student_id']);
        $check_stmt->bindParam(':aid', $_POST['assignment_id']);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() > 0) {
            $stmt = $conn->prepare("UPDATE grade SET graded_mark = :mark WHERE SID = :sid AND AID = :aid");
        } else {
            $stmt = $conn->prepare("INSERT INTO grade (SID, AID, graded_mark) VALUES (:sid, :aid, :mark)");
        }
        
        $stmt->bindParam(':mark', $_POST['marks']);
        $stmt->bindParam(':sid', $_POST['student_id']);
        $stmt->bindParam(':aid', $_POST['assignment_id']);
        
        if($stmt->execute()) {
            $success = "Grade updated successfully!";
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Get students and grades for selected assignment
if ($selectedAssignment) {
    try {
        $query = "
            SELECT 
                e.SID,
                pd.full_name as StudentName,
                c.Title as CourseTitle,
                a.Title as AssignmentTitle,
                a.deadline,
                a.full_marks,
                g.graded_mark
            FROM assignment a
            JOIN courses c ON a.Code = c.Code
            JOIN enrollment e ON c.Code = e.Code
            JOIN personal_details pd ON e.SID = pd.ID
            LEFT JOIN grade g ON (e.SID = g.SID AND a.AID = g.AID)
            WHERE a.AID = :assignment_id
        ";

        if ($searchTerm) {
            $query .= " AND pd.full_name LIKE :search";
        }

        switch($sortBy) {
            case 'name_desc':
                $query .= " ORDER BY pd.full_name DESC";
                break;
            case 'grade_asc':
                $query .= " ORDER BY g.graded_mark ASC NULLS FIRST";
                break;
            case 'grade_desc':
                $query .= " ORDER BY g.graded_mark DESC NULLS LAST";
                break;
            default:
                $query .= " ORDER BY pd.full_name ASC";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':assignment_id', $selectedAssignment);
        
        if ($searchTerm) {
            $searchParam = "%$searchTerm%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management - Pyit Tine Htaung University</title>
    <link rel="stylesheet" href="../public/css/dashboard_style.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .grade-section {
            margin: 20px 0;
            font-family: 'Roboto', sans-serif;
        }
        .submissions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .submissions-table th, .submissions-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .submissions-table th {
            background-color: #004080;
            color: white;
        }
        .grade-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .grade-input {
            width: 80px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .status-graded {
            color: #28a745;
        }
        .status-pending {
            color: #dc3545;
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
        .assignment-selector {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .assignment-selector h3 {
            color: #004080;
            margin-bottom: 20px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-sort-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            gap: 20px;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 1;
            min-width: 250px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .search-box:focus {
            border-color: #004080;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 64, 128, 0.1);
        }
        .sort-select {
            padding: 12px 35px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            min-width: 200px;
            font-size: 0.95rem;
            appearance: none;
            background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="%23555" viewBox="0 0 16 16"><path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') no-repeat;
            background-position: calc(100% - 12px) center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .sort-select:focus {
            border-color: #004080;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 64, 128, 0.1);
        }
        .sort-select:hover {
            border-color: #004080;
        }
        @media (max-width: 768px) {
            .search-sort-container {
                flex-direction: column;
            }
            
            .search-box, .sort-select {
                width: 100%;
                min-width: 100%;
            }
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
            <h2>Grade Management</h2>
            <p>View and manage student grades for assignments</p>
        </div>

        <div class="nav-links">
            <a href="faculty_home.php" class="nav-link">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="faculty_courses.php" class="nav-link">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="faculty_assignments.php" class="nav-link">
                <i class="fas fa-tasks"></i> Assignments
            </a>
            <a href="faculty_grade.php" class="nav-link active">
                <i class="fas fa-star"></i> Grades
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="grade-section">
            <div class="assignment-selector">
                <h3><i class="fas fa-tasks"></i> Select Assignment</h3>
                <form method="GET" class="search-sort-container">
                    <select name="assignment" class="sort-select" required onchange="this.form.submit()">
                        <option value="">Select an assignment</option>
                        <?php foreach ($assignments as $assignment): ?>
                            <option value="<?php echo htmlspecialchars($assignment['AID']); ?>"
                                    <?php echo $selectedAssignment === $assignment['AID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($assignment['CourseTitle'] . ' - ' . $assignment['Title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <?php if ($selectedAssignment): ?>
                <div class="dashboard-card">
                    <div class="search-sort-container">
                        <input type="text" class="search-box" placeholder="Search by student name..."
                               value="<?php echo htmlspecialchars($searchTerm); ?>"
                               onkeyup="updateSearch(this.value)">
                        <select class="sort-select" onchange="updateSort(this.value)">
                            <option value="name_asc" <?php echo $sortBy === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo $sortBy === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                        </select>
                    </div>

                    <table class="submissions-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Deadline</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($grades)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No students found for this assignment.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($grades as $grade): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($grade['StudentName']); ?></td>
                                        <td><?php echo htmlspecialchars($grade['CourseTitle']); ?></td>
                                        <td><?php echo htmlspecialchars($grade['AssignmentTitle']); ?></td>
                                        <td>
                                            <?php 
                                                $deadline = new DateTime($grade['deadline']);
                                                echo $deadline->format('M d, Y'); // Changed to only show date
                                            ?>
                                        </td>
                                        <td>
                                            <form method="POST" class="grade-form">
                                                <input type="hidden" name="student_id" value="<?php echo $grade['SID']; ?>">
                                                <input type="hidden" name="assignment_id" value="<?php echo $selectedAssignment; ?>">
                                                <input type="number" name="marks" class="grade-input" 
                                                       value="<?php echo $grade['graded_mark']; ?>"
                                                       min="0" max="<?php echo $grade['full_marks']; ?>" required>
                                                <span>/<?php echo $grade['full_marks']; ?></span>
                                                <button type="submit" name="submit_grade" class="submit-grade-btn">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php if ($grade['graded_mark'] !== null): ?>
                                                <span class="status-graded">
                                                    <i class="fas fa-check-circle"></i> Graded
                                                </span>
                                            <?php else: ?>
                                                <span class="status-pending">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Validate grade input
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.grade-form');
            forms.forEach(form => {
                form.onsubmit = function() {
                    const marks = parseInt(this.querySelector('[name="marks"]').value);
                    const maxMarks = parseInt(this.querySelector('[name="marks"]').max);
                    
                    if (marks < 0 || marks > maxMarks) {
                        alert(`Marks must be between 0 and ${maxMarks}`);
                        return false;
                    }
                    return true;
                };
            });
        });

        function updateSearch(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('search', value);
            window.location.href = '?' + urlParams.toString();
        }

        function updateSort(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', value);
            window.location.href = '?' + urlParams.toString();
        }
    </script>
</body>
</html>