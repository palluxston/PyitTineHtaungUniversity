<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_GET['assignment'])) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                p.ID,
                p.full_name,
                g.graded_mark,
                a.full_marks
            FROM personal_details p
            JOIN enrollment e ON p.ID = e.SID
            JOIN assignment a ON e.Code = a.Code
            LEFT JOIN grade g ON p.ID = g.SID AND a.AID = g.AID
            WHERE a.AID = ?
            ORDER BY p.full_name
        ");
        $stmt->execute([$_GET['assignment']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>