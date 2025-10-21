<?php
// Add customer functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate required fields
    if (empty($data['name']) || empty($data['phone'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Name and phone are required fields'
        ]);
        exit;
    }
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("
        INSERT INTO customers (name, phone, email, address, status, created_at) 
        VALUES (?, ?, ?, ?, 'active', NOW())
    ");
    
    // Execute with data
    $result = $stmt->execute([
        $data['name'],
        $data['phone'],
        $data['email'] ?? null,
        $data['address'] ?? null
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Customer added successfully',
            'customer_id' => $pdo->lastInsertId()
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add customer'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
