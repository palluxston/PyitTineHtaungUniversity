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

// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    try {
        $stmt = $conn->prepare("
            UPDATE personal_details 
            SET full_name = :full_name, 
                email = :email, 
                `date_of_birth` = :dob, 
                address = :address 
            WHERE ID = :faculty_id
        ");
        
        $stmt->bindParam(':full_name', $_POST['full_name']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':dob', $_POST['dob']);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':faculty_id', $_SESSION['user_id']);
        
        if($stmt->execute()) {
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
    <title>Faculty Dashboard - Pyit Tine Htaung University</title>
    <link rel="stylesheet" href="../public/css/dashboard_style.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .profile-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .view-mode, .edit-mode {
            padding: 15px;
        }
        .edit-mode {
            display: none;
        }
        .edit-mode.active {
            display: block;
        }
        .view-mode.hidden {
            display: none;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .profile-info dt {
            font-weight: bold;
            color: #004080;
        }
        .edit-profile-btn {
            background-color: #004080;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
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
            <h2>Faculty Dashboard</h2>
            <p>Welcome back, <?php echo htmlspecialchars($faculty['full_name']); ?>!</p>
        </div>

        <div class="nav-links">
            <a href="faculty_home.php" class="nav-link active">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="faculty_courses.php" class="nav-link">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="faculty_assignments.php" class="nav-link">
                <i class="fas fa-tasks"></i> Assignments
            </a>
            <a href="faculty_grade.php" class="nav-link">
                <i class="fas fa-star"></i> Grades
            </a>
        </div>

        <!-- Profile Section -->
        <div class="profile-section">
            <h3>Profile Information</h3>
            
            <!-- View Mode -->
            <div class="view-mode">
                <dl class="profile-info">
                    <dt>Faculty ID:</dt>
                    <dd><?php echo htmlspecialchars($faculty['ID']); ?></dd>
                    
                    <dt>Full Name:</dt>
                    <dd><?php echo htmlspecialchars($faculty['full_name']); ?></dd>
                    
                    <dt>Email:</dt>
                    <dd><?php echo htmlspecialchars($faculty['email']); ?></dd>
                    
                    <dt>Date of Birth:</dt>
                    <dd><?php echo htmlspecialchars($faculty['date_of_birth']); ?></dd>
                    
                    <dt>Address:</dt>
                    <dd><?php echo htmlspecialchars($faculty['address']); ?></dd>
                </dl>
                <button class="edit-profile-btn" onclick="toggleEdit()">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            </div>
            
            <!-- Edit Mode -->
            <div class="edit-mode">
                <form method="POST" class="profile-form">
                    <dl class="profile-info">
                        <dt>Faculty ID:</dt>
                        <dd><?php echo htmlspecialchars($faculty['ID']); ?></dd>
                        
                        <dt>Full Name:</dt>
                        <dd><input type="text" name="full_name" value="<?php echo htmlspecialchars($faculty['full_name']); ?>" required></dd>
                        
                        <dt>Email:</dt>
                        <dd><input type="email" name="email" value="<?php echo htmlspecialchars($faculty['email']); ?>" required></dd>
                        
                        <dt>Date of Birth:</dt>
                        <dd><input type="date" name="dob" value="<?php echo htmlspecialchars($faculty['date_of_birth']); ?>" required></dd>
                        
                        <dt>Address:</dt>
                        <dd><input type="text" name="address" value="<?php echo htmlspecialchars($faculty['address']); ?>" required></dd>
                    </dl>
                    <div class="button-group">
                        <button type="submit" name="update_profile" class="edit-profile-btn">Save Changes</button>
                        <button type="button" class="cancel-btn" onclick="toggleEdit()">Cancel</button>
                    </div>
                </form>
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