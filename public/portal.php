<?php
session_start();
require_once '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query to check login credentials and get role
        $stmt = $conn->prepare("
            SELECT l.ID, l.username, l.password, p.Role 
            FROM login_details l 
            JOIN personal_details p ON l.ID = p.ID 
            WHERE l.username = :username
        ");
        
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) { // In production, use password_verify()
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['Role'];

            // Redirect based on role
            switch($user['Role']) {
                case 'Admin':
                    header("Location: ../admin/admin_home.php");
                    break;
                case 'Student':
                    header("Location: ../student/student_home.php");
                    break;
                case 'Faculty':
                    header("Location: ../faculty/faculty_home.php");
                    break;
                default:
                    throw new Exception("Invalid role");
            }
            exit();
        } else {
            $error = "<span style='color: red;'>Invalid username or password</span>";
        }
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
    <title>Login Form</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Top Information Bar -->
  <div class="top-bar">
    <div class="container">
      <span><i class="fas fa-phone-alt"></i> +95 123 456 789</span>
      <span><i class="fas fa-envelope"></i> info@pyittinehtaung.edu.mm</span>
    </div>
  </div>

  <!-- Header Section -->
  <header class="site-header">
    <div class="container header-container">
      <div class="logo">
        <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo"> Pyit Tine Htaung University
      </div>
      <nav class="site-nav">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="programs.php">Programs</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="research.php">Research</a></li>
          <li><a href="portal.php">Portal</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="login-container">
    <div class="login-form">
      <h2>Portal Login</h2>
      <?php if(isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        
        <div class="show-password-container">
          <label for="show-password">Show Password</label>
          <input type="checkbox" id="show-password" onclick="togglePassword()"> 
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
        <p class="signup-link">Don't have an account in our portal? <a href="register.php">Register here</a></p>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      var showPasswordCheckbox = document.getElementById("show-password");

      if (showPasswordCheckbox.checked) {
        passwordField.type = "text";
      } else {
        passwordField.type = "password";
      }
    }
  </script>
</body>
</html>
