<?php
// Add product functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate required fields
    if (empty($data['name']) || empty($data['unit_price'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Product name and unit price are required fields'
        ]);
        exit;
    }
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, category, unit_price, status, created_at) 
        VALUES (?, ?, ?, ?, 'active', NOW())
    ");
    
    // Execute with data
    $result = $stmt->execute([
        $data['name'],
        $data['description'] ?? null,
        $data['category'] ?? null,
        $data['unit_price']
    ]);
    
    if ($result) {
        $productId = $pdo->lastInsertId();
        
        // Create inventory record for the new product
        $invStmt = $pdo->prepare("
            INSERT INTO inventory (product_id, current_stock, minimum_stock, last_updated)
            VALUES (?, 0, 10, NOW())
        ");
        $invStmt->execute([$productId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added successfully',
            'product_id' => $productId
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add product'
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
