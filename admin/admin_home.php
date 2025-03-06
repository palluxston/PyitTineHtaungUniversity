<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}

// Get admin information first
try {
    $adminStmt = $conn->prepare("SELECT * FROM personal_details WHERE ID = :user_id AND role = 'Admin'");
    $adminStmt->bindParam(':user_id', $_SESSION['user_id']);
    $adminStmt->execute();
    $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        session_destroy();
        header("Location: ../public/portal.php");
        exit();
    }

    // Get statistics
    // Total Users
    $stmt = $conn->query("SELECT COUNT(*) as total_users FROM personal_details");
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Users by Role
    $stmt = $conn->query("SELECT role, COUNT(*) as count FROM personal_details GROUP BY role");
    $usersByRole = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total Courses
    $stmt = $conn->query("SELECT COUNT(*) as total_courses FROM courses");
    $totalCourses = $stmt->fetch(PDO::FETCH_ASSOC)['total_courses'];

    // Total Assignments
    $stmt = $conn->query("SELECT COUNT(*) as total_assignments FROM assignment");
    $totalAssignments = $stmt->fetch(PDO::FETCH_ASSOC)['total_assignments'];

    // Recent Users
    $stmt = $conn->query("SELECT * FROM personal_details ORDER BY ID DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card i {
            font-size: 2.5em;
            color: #004080;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 2em;
            color: #004080;
            margin: 10px 0;
        }

        .stat-card p {
            color: #666;
            font-size: 1.1em;
        }

        .recent-activity {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .recent-activity h2 {
            color: #004080;
            margin-bottom: 20px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item i {
            margin-right: 15px;
            color: #004080;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
                <p>Welcome back, <?php echo htmlspecialchars($admin['full_name']); ?>!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-book"></i>
                    <h3><?php echo $totalCourses; ?></h3>
                    <p>Total Courses</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-tasks"></i>
                    <h3><?php echo $totalAssignments; ?></h3>
                    <p>Total Assignments</p>
                </div>
            </div>

            <div class="data-section">
                <h2>User Distribution</h2>
                <div class="stats-grid">
                    <?php foreach ($usersByRole as $roleData): ?>
                    <div class="stat-card">
                        <i class="fas fa-user-tag"></i>
                        <h3><?php echo $roleData['count']; ?></h3>
                        <p><?php echo htmlspecialchars($roleData['role']); ?>s</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="recent-activity">
                <h2>Recent Users</h2>
                <ul class="activity-list">
                    <?php foreach ($recentUsers as $user): ?>
                    <li class="activity-item">
                        <i class="fas fa-user-circle"></i>
                        <div>
                            <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                            <span> - <?php echo htmlspecialchars($user['Role']); ?></span>
                            <br>
                            <small><?php echo htmlspecialchars($user['email']); ?></small>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>