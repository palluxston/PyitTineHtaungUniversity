<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("
            SELECT AID, Title, deadline, full_marks 
            FROM assignment 
            WHERE AID = ?
        ");
        $stmt->execute([$_GET['id']]);
        
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($assignment) {
            header('Content-Type: application/json');
            echo json_encode($assignment);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Assignment not found']);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Assignment ID is required']);
}
?>