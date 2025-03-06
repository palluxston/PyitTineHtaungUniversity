<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../public/portal.php");
    exit();
}

// Get student information
try {
    $stmt = $conn->prepare("
        SELECT * FROM personal_details 
        WHERE ID = :student_id
    ");
    $stmt->bindParam(':student_id', $_SESSION['user_id']);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}


// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    try {
        $stmt = $conn->prepare("
            UPDATE personal_details 
            SET full_name = :full_name, 
                email = :email, 
                date_of_birth = :dob, 
                address = :address 
            WHERE ID = :student_id
        ");
        
        $stmt->bindParam(':full_name', $_POST['full_name']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':dob', $_POST['dob']);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':student_id', $_SESSION['user_id']);
        
        if($stmt->execute()) {
            // Refresh the page to show updated info
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Pyit Tine Htaung University</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/dashboard_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

    </style>
</head>
<body>
    <!-- Top Information Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="header-container">
                <div class="logo">
                    <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo">
                </div>
                <div class="user-info">
                    <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($student['full_name']); ?></span>
                    <span><i class="fas fa-user-graduate"></i> Student</span>
                </div>
            </div>
            <a href="../public/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h2>Student Dashboard</h2>
            <p>Welcome back, <?php echo htmlspecialchars($student['full_name']); ?>!</p>
        </div>

        <!-- New Profile Section -->
        <div class="profile-section">
        <div class="profile-details">
                <h3><i class="fas fa-user-circle"></i> Personal Details</h3>
                
                <!-- View Mode -->
                <div class="view-mode">
                    <dl class="profile-info">
                        <dt>Full Name:</dt>
                        <dd><?php echo htmlspecialchars($student['full_name']); ?></dd>
                        <dt>Email:</dt>
                        <dd><?php echo htmlspecialchars($student['email']); ?></dd>
                        <dt>Date of Birth:</dt>
                        <dd><?php echo htmlspecialchars($student['date_of_birth']); ?></dd>
                        <dt>Address:</dt>
                        <dd><?php echo htmlspecialchars($student['address']); ?></dd>
                    </dl>
                    <button onclick="toggleEdit()" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>

                <!-- Edit Mode -->
                <div class="edit-mode">
                    <form class="profile-form" id="profileForm" method="POST">
                        <div>
                            <label for="full_name">Full Name:</label>
                            <input type="text" id="full_name" name="full_name" 
                                value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" 
                                value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div>
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" 
                                value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>
                        </div>
                        <div>
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" 
                                value="<?php echo htmlspecialchars($student['address']); ?>" required>
                        </div>
                        <div class="button-group">
                            <button type="button" onclick="toggleEdit()" class="cancel-btn">Cancel</button>
                            <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Enrolled Courses with Assignments -->
            <div class="dashboard-card">
                <h3><i class="fas fa-book"></i> My Courses & Assignments</h3>
                <?php
                try {
                    $stmt = $conn->prepare("
                        SELECT DISTINCT c.* FROM courses c
                        JOIN enrollment e ON c.Code = e.Code
                        WHERE e.SID = :student_id
                    ");
                    $stmt->bindParam(':student_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($courses as $course) {
                        echo "<div class='course-item'>";
                        echo "<h4>" . htmlspecialchars($course['Title']) . "</h4>";
                        echo "<p>Code: " . htmlspecialchars($course['Code']) . "</p>";
                        echo "<p>Credits: " . htmlspecialchars($course['Credits']) . "</p>";
                        
                        // Get assignments for this course
                        $stmt = $conn->prepare("
                            SELECT * FROM assignment 
                            WHERE Code = :course_code
                        ");
                        $stmt->bindParam(':course_code', $course['Code']);
                        $stmt->execute();
                        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (!empty($assignments)) {
                            echo "<div class='course-assignments'>";
                            echo "<h5>Course Assignments:</h5>";
                            foreach($assignments as $assignment) {
                                echo "<div class='assignment-item'>";
                                echo "<h4>" . htmlspecialchars($assignment['Title']) . "</h4>";
                                echo "<p>Deadline: " . htmlspecialchars($assignment['deadline']) . "</p>";
                                echo "<p>Full Marks: " . htmlspecialchars($assignment['full_marks']) . "</p>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </div>

            <!-- Grades section remains the same -->
            <div class="dashboard-card">
                <h3><i class="fas fa-chart-line"></i> My Grades</h3>
                <?php
                try {
                    $stmt = $conn->prepare("
                        SELECT a.Title, g.graded_mark, a.full_marks 
                        FROM grade g
                        JOIN assignment a ON g.AID = a.AID
                        WHERE g.SID = :student_id
                    ");
                    $stmt->bindParam(':student_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($grades as $grade) {
                        echo "<div class='grade-item'>";
                        echo "<h4>" . htmlspecialchars($grade['Title']) . "</h4>";
                        echo "<p>Code: " . htmlspecialchars($course['Code']) . "</p>";
                        echo "<p>Grade: " . htmlspecialchars($grade['graded_mark']) . "/" . htmlspecialchars($grade['full_marks']) . "</p>";
                        echo "</div>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>


    <script>
        function toggleEdit() {
            const viewMode = document.querySelector('.view-mode');
            const editMode = document.querySelector('.edit-mode');
            
            viewMode.classList.toggle('hidden');
            editMode.classList.toggle('active');
        }
    </script>

</body>
</html>