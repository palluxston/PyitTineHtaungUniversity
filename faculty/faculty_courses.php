<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Faculty') {
    header("Location: ../public/portal.php");
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Pyit Tine Htaung University</title>
    <link rel="stylesheet" href="../public/css/dashboard_style.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .course-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .course-table th, .course-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .course-table th {
            background-color: #004080;
            color: white;
        }
        .course-table tr:hover {
            background-color: #f5f5f5;
        }
        .student-list {
            display: none;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-top: 10px;
        }
        .student-list.active {
            display: block;
        }
        .student-count {
            background: #004080;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.9em;
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
        .view-btn {
            background: #004080;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .view-btn:hover {
            background: #003366;
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
            <h2>My Courses</h2>
            <p>Manage your courses and view enrolled students</p>
        </div>

        <div class="nav-links">
            <a href="faculty_home.php" class="nav-link">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="faculty_courses.php" class="nav-link active">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="faculty_assignments.php" class="nav-link">
                <i class="fas fa-tasks"></i> Assignments
            </a>
            <a href="faculty_grade.php" class="nav-link">
                <i class="fas fa-star"></i> Grades
            </a>
        </div>

        <div class="dashboard-card">
            <table class="course-table">
                <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Code</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                try {
                    $stmt = $conn->prepare("
                        SELECT * FROM courses 
                        WHERE FID = :faculty_id
                    ");
                    $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($courses as $course) {
                        // Get student count
                        $stmt2 = $conn->prepare("
                            SELECT COUNT(*) as student_count 
                            FROM enrollment 
                            WHERE Code = :course_code
                        ");
                        $stmt2->bindParam(':course_code', $course['Code']);
                        $stmt2->execute();
                        $studentCount = $stmt2->fetch(PDO::FETCH_ASSOC)['student_count'];

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($course['Title']) . "</td>";
                        echo "<td>" . htmlspecialchars($course['Code']) . "</td>";
                        echo "<td>" . htmlspecialchars($course['Credits']) . "</td>";
                        echo "<td>" . htmlspecialchars($course['semester']) . "</td>";
                        echo "<td><span class='student-count'>" . $studentCount . "</span></td>";
                        echo "<td><button class='view-btn' onclick='toggleStudentList(\"" . $course['Code'] . "\")'>";
                        echo "<i class='fas fa-users'></i> View Students</button></td>";
                        echo "</tr>";

                        // Student list section
                        echo "<tr id='students-" . $course['Code'] . "' class='student-list'>";
                        echo "<td colspan='6'>";
                        
                        // Get enrolled students
                        $stmt3 = $conn->prepare("
                            SELECT p.* FROM personal_details p
                            JOIN enrollment e ON p.ID = e.SID
                            WHERE e.Code = :course_code
                        ");
                        $stmt3->bindParam(':course_code', $course['Code']);
                        $stmt3->execute();
                        $students = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (!empty($students)) {
                            echo "<h4>Enrolled Students:</h4>";
                            echo "<ul>";
                            foreach($students as $student) {
                                echo "<li>" . htmlspecialchars($student['full_name']) . 
                                     " (ID: " . htmlspecialchars($student['ID']) . ")</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No students enrolled yet.</p>";
                        }
                        
                        echo "</td></tr>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleStudentList(courseCode) {
            const studentList = document.getElementById('students-' + courseCode);
            studentList.classList.toggle('active');
        }
    </script>
</body>
</html>