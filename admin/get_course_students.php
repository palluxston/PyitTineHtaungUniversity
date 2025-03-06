<?php
require_once '../connect.php';

$code = $_GET['code'];

try {
    // Get enrolled students
    $stmt = $conn->prepare("
        SELECT p.ID, p.full_name, e.date_enrolled
        FROM enrollment e
        JOIN personal_details p ON e.SID = p.ID
        WHERE e.Code = ?
        ORDER BY p.full_name
    ");
    $stmt->execute([$code]);
    $enrolled = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get available students
    $stmt = $conn->prepare("
        SELECT ID, full_name
        FROM personal_details
        WHERE Role = 'Student'
        AND ID NOT IN (
            SELECT SID FROM enrollment WHERE Code = ?
        )
        ORDER BY full_name
    ");
    $stmt->execute([$code]);
    $available = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'enrolled_students' => $enrolled,
        'available_students' => $available
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}