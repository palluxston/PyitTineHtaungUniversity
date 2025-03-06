<?php
require_once '../connect.php';

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("
            SELECT ID, full_name, email, Role, date_of_birth, address 
            FROM personal_details 
            WHERE ID = ?
        ");
        $stmt->execute([$_GET['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}