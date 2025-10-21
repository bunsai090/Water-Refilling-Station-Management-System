<?php
// Delete customer functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get customer ID
    $id = $_GET['id'] ?? null;
    
    if (empty($id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Customer ID is required'
        ]);
        exit;
    }
    
    // Delete customer
    $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
    $result = $stmt->execute([$id]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete customer'
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
