<style>
    .sidebar {
        width: 250px;
        background: #004080;
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px 0;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
        border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar-header h3 {
        font-size: 18px;
        margin: 10px 0 5px;
        color: #fff;
    }

    .sidebar-header p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
    }

    .nav-menu {
        padding: 20px 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding-left: 30px;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-left: 4px solid #fff;
    }

    .nav-item i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Adjust main content margin */
    .main-content {
        margin-left: 250px;
        padding: 20px;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../images/admin-avatar.png" alt="Admin Avatar">
        <h3><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Administrator'); ?></h3>
        <p>Administrator</p>
    </div>
    <div class="nav-menu">
        <a href="admin_home.php" class="nav-item">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="admin_user_management.php" class="nav-item">
            <i class="fas fa-users"></i> Users Management
        </a>
        <a href="admin_course_management.php" class="nav-item">
            <i class="fas fa-book"></i> Courses
        </a>
        <a href="admin_assignment_management.php" class="nav-item">
            <i class="fas fa-tasks"></i> Assignments
        </a>
        <a href="admin_contact_messages.php" class="nav-item">
            <i class="fas fa-envelope"></i> Contact Messages
        </a>
        <a href="admin_grading_management.php" class="nav-item">
            <i class="fas fa-star"></i> Grading Management
        </a>
        <a href="admin_profile.php" class="nav-item">
            <i class="fas fa-user"></i> Profile
        </a>

        <a href="admin_profile.php" class="nav-item">
            <i class="fas fa-user"></i> User
        </a>

        <a href="../public/logout.php" class="nav-item">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<script>
    // Add active class to current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const navItems = document.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            if(item.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });
    });
</script>