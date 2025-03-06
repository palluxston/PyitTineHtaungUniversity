<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
            $stmt = $conn->prepare("
            UPDATE login_details l
            JOIN personal_details p ON l.ID = p.ID
            SET 
                p.email = :email,
                p.full_name = :full_name,
                p.address = :address,
                p.date_of_birth = DATE_FORMAT(STR_TO_DATE(:date_of_birth, '%Y-%m-%d'), '%d/%m/%Y')
            WHERE l.ID = :user_id
        ");

        $stmt->execute([
            ':email' => $_POST['email'],
            ':full_name' => $_POST['full_name'],
            ':address' => $_POST['address'],
            ':date_of_birth' => $_POST['date_of_birth'],
            ':user_id' => $_SESSION['user_id']
        ]);

        // Handle password update if provided
        if (!empty($_POST['new_password'])) {
            $stmt = $conn->prepare("UPDATE login_details SET password = :password WHERE ID = :user_id");
            $stmt->execute([
                ':password' => $_POST['new_password'],
                ':user_id' => $_SESSION['user_id']
            ]);
        }

        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: admin_profile.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error updating profile: " . $e->getMessage();
    }
}

// Update the fetch query
try {
    $stmt = $conn->prepare("
        SELECT l.username, p.email, p.full_name, p.address, 
               DATE_FORMAT(STR_TO_DATE(p.date_of_birth, '%d/%m/%Y'), '%Y-%m-%d') as date_of_birth
        FROM login_details l
        JOIN personal_details p ON l.ID = p.ID
        WHERE l.ID = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $_SESSION['error_message'] = "Error fetching profile: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
/* Profile Styles */
.profile-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 0 auto;
}

.profile-container h2 {
    color: #004080;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.profile-form {
    display: grid;
    gap: 20px;
}

.profile-form .form-group {
    margin-bottom: 0;
}

.profile-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    resize: vertical;
}

.profile-form textarea:focus {
    border-color: #004080;
    outline: none;
    box-shadow: 0 0 5px rgba(0,64,128,0.2);
}

.form-actions {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.password-input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input-container input {
    width: 100%;
}

.toggle-password {
    position: absolute;
    right: 10px;
    cursor: pointer;
    color: #666;
}

.toggle-password:hover {
    color: #004080;
}
</style>
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="profile-container">
                <h2><i class="fas fa-user-circle"></i> Admin Profile</h2>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert success">
                        <?php 
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert error">
                        <?php 
                            echo $_SESSION['error_message'];
                            unset($_SESSION['error_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="profile-form" id="profileForm">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password (leave blank to keep current)</label>
                        <div class="password-input-container">
                            <input type="password" id="new_password" name="new_password" minlength="6">
                            <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('new_password', this)"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="confirm_password" name="confirm_password" minlength="6">
                            <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New password and confirm password do not match!');
        }
    });
    </script>
</body>
</html>