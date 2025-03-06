<?php
session_start();
require_once '../connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("SELECT ID, username, password FROM login_details WHERE ID = ?");
        $stmt->execute([$_GET['id']]);
        $login = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($login) {
            echo json_encode($login);
        } else {
            echo json_encode(['error' => 'Login details not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}