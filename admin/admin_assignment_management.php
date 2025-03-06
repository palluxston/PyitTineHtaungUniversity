<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../public/portal.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_assignment':
                try {
                    $stmt = $conn->prepare("
                        INSERT INTO assignment (AID, Code, Title, deadline, full_marks)
                        VALUES (:aid, :code, :title, :deadline, :full_marks)
                    ");
                    
                    $stmt->execute([
                        ':aid' => $_POST['aid'],
                        ':code' => $_POST['code'],
                        ':title' => $_POST['title'],
                        ':deadline' => $_POST['deadline'],
                        ':full_marks' => $_POST['full_marks']
                    ]);
                    
                    echo json_encode(['status' => 'success']);
                    exit();
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit();
                }
                break;

                case 'update_assignment':
                    try {
                        // First check if the new AID already exists (if it's different)
                        if ($_POST['aid'] !== $_POST['original_aid']) {
                            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM assignment WHERE AID = :aid");
                            $checkStmt->execute([':aid' => $_POST['aid']]);
                            if ($checkStmt->fetchColumn() > 0) {
                                echo json_encode(['status' => 'error', 'message' => 'Assignment ID already exists']);
                                exit();
                            }
                        }
    
                        $stmt = $conn->prepare("
                            UPDATE assignment 
                            SET AID = :new_aid,
                                Title = :title, 
                                deadline = :deadline, 
                                full_marks = :full_marks
                            WHERE AID = :old_aid
                        ");
                        
                        $stmt->execute([
                            ':new_aid' => $_POST['aid'],
                            ':old_aid' => $_POST['original_aid'],
                            ':title' => $_POST['title'],
                            ':deadline' => $_POST['deadline'],
                            ':full_marks' => $_POST['full_marks']
                        ]);
                        
                        echo json_encode(['status' => 'success']);
                        exit();
                    } catch(PDOException $e) {
                        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        exit();
                    }
                    break;

            case 'delete_assignment':
                try {
                    $stmt = $conn->prepare("DELETE FROM assignment WHERE AID = :aid");
                    $stmt->execute([':aid' => $_POST['aid']]);
                    
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
    <title>Assignment Management - PTH University</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>

</style>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <h2>Assignment Management</h2>

            <div class="filter-section">
                <select id="courseSelect" onchange="loadAssignments()">
                    <option value="">Select Course</option>
                    <?php
                    $stmt = $conn->query("SELECT Code, Title FROM courses ORDER BY Code");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['Code']) . "'>" . 
                            htmlspecialchars($row['Code'] . ' - ' . $row['Title']) . "</option>";
                    }
                    ?>
                </select>
                <button onclick="showAddAssignmentModal()" class="add-btn">
                    <i class="fas fa-plus"></i> Add New Assignment
                </button>
            </div>

            <div class="table-section">
                <h3>Assignment Details</h3>
                <table id="assignmentTable">
                    <thead>
                        <tr>
                            <th>Assignment ID</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Full Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="assignmentTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="assignmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('assignmentModal')">&times;</span>
            <h2 id="modalTitle">Add New Assignment</h2>
            <form id="assignmentForm" onsubmit="handleAssignmentSubmit(event)">
                <input type="hidden" id="assignmentAction" name="action" value="add_assignment">
                <input type="hidden" id="courseCode" name="code">
                
        <div class="form-group">
            <label for="aid">Assignment ID</label>
            <input type="text" id="aid" name="aid" required 
                pattern="[A-Za-z0-9-_]+" 
                title="Assignment ID can only contain letters, numbers, hyphens, and underscores"
                placeholder="e.g., ASG-001">
        </div>
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required
                minlength="3" maxlength="100"
                placeholder="Enter assignment title">
        </div>
        
        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="date" id="deadline" name="deadline" required
                min="<?php echo date('Y-m-d'); ?>"
                value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
        </div>
        
        <div class="form-group">
            <label for="fullMarks">Full Marks</label>
            <input type="number" id="fullMarks" name="full_marks" required 
                min="1" max="100" value="100"
                placeholder="Enter full marks">
        </div>
                
                <button type="submit" class="submit-btn">Save</button>
            </form>
        </div>
    </div>

    <script>
    let currentCourse = '';

    async function loadAssignments() {
        currentCourse = document.getElementById('courseSelect').value;
        const tableBody = document.getElementById('assignmentTableBody');
        
        tableBody.innerHTML = '';
        if (!currentCourse) return;

        try {
            const response = await fetch(`get_assignments.php?course=${currentCourse}`);
            const assignments = await response.json();
            
            tableBody.innerHTML = assignments.map(assignment => {
            const deadline = new Date(assignment.deadline).toISOString().split('T')[0];
            return `
                <tr>
                    <td>${assignment.AID}</td>
                    <td>${assignment.Title}</td>
                    <td>${deadline}</td>
                    <td>${assignment.full_marks}</td>
                    <td>
                        <button onclick="editAssignment('${assignment.AID}')" class="action-btn edit-btn" title="Edit Assignment">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteAssignment('${assignment.AID}')" class="action-btn delete-btn" title="Delete Assignment">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

            if (assignments.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="no-data">No assignments found for this course</td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="error-message">Error loading assignments</td>
                </tr>
            `;
        }
    }

    function showAddAssignmentModal() {
        document.getElementById('modalTitle').textContent = 'Add New Assignment';
        document.getElementById('assignmentAction').value = 'add_assignment';
        document.getElementById('courseCode').value = currentCourse;
        document.getElementById('aid').value = '';
        document.getElementById('aid').readOnly = false;
        document.getElementById('title').value = '';
        
        // Set default date format
        const defaultDate = new Date();
        defaultDate.setDate(defaultDate.getDate() + 7); // Default to 7 days from now
        const formattedDate = defaultDate.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
        document.getElementById('deadline').value = formattedDate;
        
        document.getElementById('fullMarks').value = '100';
        document.getElementById('assignmentModal').style.display = 'block';
    }

    async function editAssignment(aid) {
        try {
            const response = await fetch(`get_assignment_details.php?id=${aid}`);
            const assignment = await response.json();
            
            document.getElementById('modalTitle').textContent = 'Edit Assignment';
            document.getElementById('assignmentAction').value = 'update_assignment';
            document.getElementById('aid').value = assignment.AID;
            document.getElementById('courseCode').value = currentCourse;
            
            // Add hidden field for original assignment ID
            const originalAidInput = document.createElement('input');
            originalAidInput.type = 'hidden';
            originalAidInput.name = 'original_aid';
            originalAidInput.value = assignment.AID;
            document.getElementById('assignmentForm').appendChild(originalAidInput);
            
            document.getElementById('title').value = assignment.Title;
            
            // Format the deadline date
            const deadlineDate = new Date(assignment.deadline);
            const formattedDeadline = deadlineDate.toISOString().slice(0, 16);
            document.getElementById('deadline').value = formattedDeadline;
            
            document.getElementById('fullMarks').value = assignment.full_marks;
            document.getElementById('assignmentModal').style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading assignment details');
        }
    }

    async function handleAssignmentSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        // Validate assignment ID format
        const aid = formData.get('aid');
        if (!/^[A-Za-z0-9-_]+$/.test(aid)) {
            alert('Assignment ID can only contain letters, numbers, hyphens, and underscores');
            return;
        }

        // Validate deadline format
        const deadlineDate = new Date(formData.get('deadline'));
        const formattedDeadline = deadlineDate.toISOString().split('T')[0];
        formData.set('deadline', formattedDeadline);
        if (isNaN(deadline)) {
            alert('Please enter a valid deadline date');
            return;
        }

        // Validate full marks
        const fullMarks = parseInt(formData.get('full_marks'));
        if (isNaN(fullMarks) || fullMarks <= 0) {
            alert('Full marks must be a positive number');
            return;
        }

        try {
            const response = await fetch('admin_assignment_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('assignmentModal');
                loadAssignments();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error saving assignment');
        }
    }

    async function deleteAssignment(aid) {
        if (!confirm('Are you sure you want to delete this assignment?')) return;
        
        try {
            const response = await fetch('admin_assignment_management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_assignment&aid=${aid}`
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                loadAssignments();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error deleting assignment');
        }
    }

    async function handleAssignmentSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('admin_assignment_management.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                closeModal('assignmentModal');
                loadAssignments();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error saving assignment');
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    </script>
</body>
</html>