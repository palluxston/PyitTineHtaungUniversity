<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM contact_submissions WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($message) {
            echo json_encode($message);
        } else {
            echo json_encode(['error' => 'Message not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>