<?php
require_once '../connect.php';

$code = $_GET['code'];

try {
    // Get current faculty
    $stmt = $conn->prepare("
        SELECT p.ID, p.full_name 
        FROM courses c
        LEFT JOIN personal_details p ON c.FID = p.ID
        WHERE c.Code = ?
    ");
    $stmt->execute([$code]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get available faculty
    $stmt = $conn->prepare("
        SELECT ID, full_name 
        FROM personal_details 
        WHERE Role = 'Faculty'
        AND ID != COALESCE(?, 0)
        ORDER BY full_name
    ");
    $stmt->execute([$current['ID']]);
    $available = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'faculty_name' => $current['full_name'],
        'available_faculty' => $available
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}