<?php
require_once '../connect.php';

if (isset($_GET['code'])) {
    try {
        $stmt = $conn->prepare("
            SELECT Code, Title, Credits, semester 
            FROM courses 
            WHERE Code = :code
        ");
        $stmt->execute([':code' => $_GET['code']]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($course) {
            echo json_encode($course);
        } else {
            echo json_encode(['error' => 'Course not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No course code provided']);
}
?>