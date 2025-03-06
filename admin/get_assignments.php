<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_GET['course'])) {
    try {
        $stmt = $conn->prepare("
            SELECT AID, Title, deadline, full_marks 
            FROM assignment 
            WHERE Code = ?
            ORDER BY deadline DESC
        ");
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement");
        }
        
        $result = $stmt->execute([$_GET['course']]);
        
        if (!$result) {
            throw new Exception("Failed to execute query");
        }
        
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($assignments);
        exit();
    } catch(Exception $e) {
        error_log("Assignment fetch error: " . $e->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Course parameter is required']);
    exit();
}
?>