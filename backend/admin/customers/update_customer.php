<?php
// Update customer functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get customer ID from query string
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Customer ID is required'
        ]);
        exit;
    }
    
    $customerId = $_GET['id'];
    
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
        UPDATE customers 
        SET name = ?, phone = ?, email = ?, address = ?, updated_at = NOW()
        WHERE id = ?
    ");
    
    // Execute with data
    $result = $stmt->execute([
        $data['name'],
        $data['phone'],
        $data['email'] ?? null,
        $data['address'] ?? null,
        $customerId
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Customer updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update customer'
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
