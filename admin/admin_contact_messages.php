<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}

// Handle CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_status':
                try {
                    $stmt = $conn->prepare("
                        UPDATE contact_submissions 
                        SET status = :status 
                        WHERE id = :id
                    ");
                    
                    $stmt->execute([
                        ':id' => $_POST['message_id'],
                        ':status' => $_POST['status']
                    ]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

            case 'delete_message':
                try {
                    $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = :id");
                    $stmt->execute([':id' => $_POST['message_id']]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
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
    <title>Contact Management - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <h2>Contact Messages Management</h2>
            </div>

            <div class="search-filter-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search messages...">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="responded">Responded</option>
                </select>
            </div>

            <div class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="messagesTableBody">
                            <?php
                            $stmt = $conn->query("
                                SELECT * FROM contact_submissions 
                                ORDER BY submission_date DESC
                            ");
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $statusClass = 'status-' . $row['status'];
                                echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                                echo "<td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($row['submission_date']))) . "</td>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                echo "<td><span class='status-badge {$statusClass}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                echo "<td>
                                    <button class='action-btn view-btn' onclick='viewMessage(" . $row['id'] . ")'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    <button class='action-btn delete-btn' onclick='deleteMessage(" . $row['id'] . ")'>
                                        <i class='fas fa-trash'></i>
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

    <!-- View Message Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('messageModal')">&times;</span>
            <h2>Message Details</h2>
            <div id="messageDetails">
                <div class="message-header">
                    <p><strong>From:</strong> <span id="modalName"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                </div>
                <div class="message-body">
                    <p><strong>Message:</strong></p>
                    <div id="modalMessage" class="message-content"></div>
                </div>
                <div class="message-status">
                    <label for="messageStatus">Status:</label>
                    <select id="messageStatus" onchange="updateMessageStatus(this.value)">
                        <option value="new">New</option>
                        <option value="read">Read</option>
                        <option value="responded">Responded</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentMessageId = null;

    async function viewMessage(id) {
        currentMessageId = id;
        try {
            const response = await fetch(`get_message_details.php?id=${id}`);
            const message = await response.json();
            
            document.getElementById('modalName').textContent = message.full_name;
            document.getElementById('modalEmail').textContent = message.email;
            document.getElementById('modalSubject').textContent = message.subject;
            document.getElementById('modalDate').textContent = new Date(message.submission_date).toLocaleString();
            document.getElementById('modalMessage').textContent = message.message;
            document.getElementById('messageStatus').value = message.status;
            
            document.getElementById('messageModal').style.display = 'block';
        } catch (error) {
            alert('Error loading message details: ' + error.message);
        }
    }

    async function updateMessageStatus(status) {
        if (!currentMessageId) return;

        try {
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('message_id', currentMessageId);
            formData.append('status', status);
            
            // Update the URL to match the current file name
            const response = await fetch('admin_contact_messages.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                const row = document.querySelector(`tr[data-id="${currentMessageId}"]`);
                const statusCell = row.querySelector('td:nth-child(5)');
                statusCell.innerHTML = `<span class="status-badge status-${status}">${status}</span>`;
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function deleteMessage(id) {
        if (!confirm('Are you sure you want to delete this message?')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'delete_message');
            formData.append('message_id', id);
            
            // Update the URL to match the current file name
            const response = await fetch('admin_contact_messages.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        currentMessageId = null;
    }

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('messagesTableBody');

    function filterMessages() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = tableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const subject = row.cells[3].textContent.toLowerCase();
            const status = row.cells[4].textContent.toLowerCase();
            
            const matchesSearch = name.includes(searchTerm) || 
                                email.includes(searchTerm) || 
                                subject.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterMessages);
    statusFilter.addEventListener('change', filterMessages);
    </script>
</body>
</html>