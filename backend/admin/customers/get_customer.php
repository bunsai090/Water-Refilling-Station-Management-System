<?php
// Get single customer functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Check if ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode([
            'error' => true,
            'message' => 'Customer ID is required'
        ]);
        exit;
    }
    
    $customerId = $_GET['id'];
    
    // Prepare SQL query to get single customer
    $stmt = $pdo->prepare("
        SELECT id, name, phone, email, address, status, created_at 
        FROM customers 
        WHERE id = ?
    ");
    $stmt->execute([$customerId]);
    
    // Fetch customer
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($customer) {
        // Return customer data
        echo json_encode($customer);
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Customer not found'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
