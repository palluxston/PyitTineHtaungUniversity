<?php
    session_start();
    require_once '../connect.php';

    // Check if user is logged in and is an admin
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) ||  $_SESSION['role'] !== 'Admin') {
        header("Location: ../public/portal.php");
        exit();
    }
    // Get admin information
    try {
        $stmt = $conn->prepare("SELECT * FROM personal_details WHERE ID = :user_id AND role = 'Admin'");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) {
            // If admin not found, destroy session and redirect
            session_destroy();
            header("Location: ../public/portal.php");
            exit();
        }
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    // Handle user operations
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    try {
                        $conn->beginTransaction();
                
                        // Check if email already exists
                        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM personal_details WHERE email = :email");
                        $checkStmt->bindParam(':email', $_POST['email']);
                        $checkStmt->execute();
                        
                        if ($checkStmt->fetchColumn() > 0) {
                            echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
                            exit();
                        }
                
                        // Generate unique ID
                        $rolePrefix = substr($_POST['role'], 0, 1);
                        $stmt = $conn->query("SELECT ID FROM login_details WHERE ID LIKE '$rolePrefix%' ORDER BY ID DESC LIMIT 1");
                        $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($lastId) {
                            $numericPart = intval(substr($lastId['ID'], 1)) + 1;
                            $newId = $rolePrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT);
                        } else {
                            $newId = $rolePrefix . '001';
                        }
                
                        // Insert into login_details
                        $loginStmt = $conn->prepare("
                            INSERT INTO login_details (ID, username, password) 
                            VALUES (:id, :username, :password)
                        ");
                        
                        $username = explode('@', $_POST['email'])[0];
                        
                        $loginStmt->execute([
                            ':id' => $newId,
                            ':username' => $username,
                            ':password' => $_POST['password']
                        ]);
                
                        // Insert into personal_details
                        $personalStmt = $conn->prepare("
                            INSERT INTO personal_details (ID, full_name, email, Role, date_of_birth, address) 
                            VALUES (:id, :full_name, :email, :role, :dob, :address)
                        ");
                        
                        $personalStmt->execute([
                            ':id' => $newId,
                            ':full_name' => $_POST['full_name'],
                            ':email' => $_POST['email'],
                            ':role' => $_POST['role'],
                            ':dob' => $_POST['dob'],
                            ':address' => $_POST['address']
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

                    case 'edit':
                        try {
                            $conn->beginTransaction();
                            
                            // Check if email exists for other users
                            $checkStmt = $conn->prepare("
                                SELECT COUNT(*) FROM personal_details 
                                WHERE email = :email AND ID != :id
                            ");
                            $checkStmt->execute([
                                ':email' => $_POST['email'],
                                ':id' => $_POST['user_id']
                            ]);
                            
                            if ($checkStmt->fetchColumn() > 0) {
                                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
                                exit();
                            }
                    
                            // Update personal_details
                            $stmt = $conn->prepare("
                                UPDATE personal_details 
                                SET full_name = :full_name, 
                                    email = :email, 
                                    role = :role,
                                    date_of_birth = :dob,
                                    address = :address
                                WHERE ID = :id
                            ");
                            
                            $stmt->execute([
                                ':full_name' => $_POST['full_name'],
                                ':email' => $_POST['email'],
                                ':role' => $_POST['role'],
                                ':dob' => $_POST['dob'],
                                ':address' => $_POST['address'],
                                ':id' => $_POST['user_id']
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

                // In the delete case:
                case 'delete':
                    try {
                        // Start transaction
                        $conn->beginTransaction();

                        // Prevent deleting self
                        if ($_POST['user_id'] == $_SESSION['user_id']) {
                            echo json_encode(['status' => 'error', 'message' => 'Cannot delete your own account']);
                            exit();
                        }

                        // Delete from personal_details and login_details (cascade will handle it)
                        $stmt = $conn->prepare("DELETE FROM login_details WHERE ID = :id");
                        $stmt->bindParam(':id', $_POST['user_id']);
                        $stmt->execute();

                        // Commit transaction
                        $conn->commit();
                        echo json_encode(['status' => 'success']);
                        exit();
                    } catch(PDOException $e) {
                        // Rollback transaction on error
                        $conn->rollBack();
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        exit();
                    }
                    break;
                case 'update_login':
                try {
                    $conn->beginTransaction();
                    
                    $stmt = $conn->prepare("
                        UPDATE login_details 
                        SET username = :username, 
                            password = :password 
                        WHERE ID = :id
                    ");
                    
                    $stmt->execute([
                        ':username' => $_POST['username'],
                        ':password' => $_POST['password'],
                        ':id' => $_POST['user_id']
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
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <h2>User Management</h2>
                <button class="add-btn" onclick="showModal('userModal', 'add')">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>

            <div class="search-filter-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by name, email, or ID...">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <div class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php
                            $stmt = $conn->query("SELECT * FROM personal_details ORDER BY ID DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr data-id='" . htmlspecialchars($row['ID']) . "'>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Role']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date_of_birth']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";

                                echo "<td>
                                        <button class='action-btn edit-btn' onclick='editUser(\"" . htmlspecialchars($row['ID']) . "\")'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='action-btn delete-btn' onclick='deleteUser(\"" . htmlspecialchars($row['ID']) . "\")'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                        <button class='action-btn view-btn' onclick='viewLoginDetails(\"" . htmlspecialchars($row['ID']) . "\")'>
                                            <i class='fas fa-key'></i>
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

    <!-- User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('userModal')">&times;</span>
            <h2 id="modalTitle">Add New User</h2>
            <form id="userForm" onsubmit="handleUserSubmit(event)">
                <input type="hidden" id="userId" name="user_id">
                <input type="hidden" id="action" name="action" value="add">
                
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group" id="passwordGroup">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                
                <button type="submit" class="submit-btn">Save</button>
            </form>
        </div>
    </div>

    <!-- Login Details Modal -->
    <div id="loginDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginDetailsModal')">&times;</span>
            <h2>Login Details</h2>
            <form id="loginDetailsForm" onsubmit="handleLoginDetailsSubmit(event)">
                <input type="hidden" name="action" value="update_login">
                <input type="hidden" id="loginDetailsUserId" name="user_id">
                
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="text" id="loginPassword" name="password" required>
                </div>
                
                <button type="submit" class="submit-btn">Update Login Details</button>
            </form>
        </div>
    </div>

    <script>
    function showModal(modalId, action) {
        document.getElementById(modalId).style.display = 'block';
        document.getElementById('action').value = action;
        if (action === 'add') {
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('password').required = true;
            document.getElementById('userForm').reset();
        } else {
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('passwordGroup').style.display = 'none';
            document.getElementById('password').required = false;
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.getElementById('userForm').reset();
    }

    async function editUser(userId) {
    try {
        const response = await fetch(`get_user.php?id=${userId}`);
        const user = await response.json();
        
        if (user.error) {
            alert('Error: ' + user.error);
            return;
        }
        
        document.getElementById('userId').value = user.ID;
        document.getElementById('fullName').value = user.full_name;
        document.getElementById('email').value = user.email;
        document.getElementById('role').value = user.Role;
        document.getElementById('dob').value = user.date_of_birth;
        document.getElementById('address').value = user.address;
        document.getElementById('action').value = 'edit';
        
        showModal('userModal', 'edit');
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

    async function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('user_id', userId);
            
            const response = await fetch('admin_user_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                // Remove the row from the table without reloading
                const row = document.querySelector(`tr[data-id="${userId}"]`);
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

    async function handleUserSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_user_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('userModal');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
    async function viewLoginDetails(userId) {
        try {
            const response = await fetch(`get_login_details.php?id=${userId}`);
            const data = await response.json();
            
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            document.getElementById('loginDetailsUserId').value = data.ID;
            document.getElementById('loginUsername').value = data.username;
            document.getElementById('loginPassword').value = data.password;
            
            document.getElementById('loginDetailsModal').style.display = 'block';
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function handleLoginDetailsSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_user_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('loginDetailsModal');
                alert('Login details updated successfully');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const tableBody = document.getElementById('usersTableBody');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value;
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const role = row.cells[3].textContent;

            const matchesSearch = id.includes(searchTerm) || 
                                name.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesRole = roleValue === '' || role === roleValue;

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        }
    }
    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
</script>



</body>
</html>
