<?php
// Create order functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate required fields
    if (empty($data['customer_id']) || empty($data['items']) || count($data['items']) == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Customer and at least one item are required'
        ]);
        exit;
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Generate unique order ID
        $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += floatval($item['total_price']);
        }
        
        // Insert order
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                order_id, customer_id, total_amount, status, 
                delivery_address, delivery_date, notes, created_at
            ) VALUES (?, ?, ?, 'pending', ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $orderId,
            $data['customer_id'],
            $totalAmount,
            $data['delivery_address'] ?? null,
            $data['delivery_date'] ?? null,
            $data['notes'] ?? null
        ]);
        
        $orderDbId = $pdo->lastInsertId();
        
        // Insert order items
        $itemStmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($data['items'] as $item) {
            $itemStmt->execute([
                $orderDbId,
                $item['product_id'],
                $item['quantity'],
                $item['unit_price'],
                $item['total_price']
            ]);
            
            // Update inventory - reduce stock
            $updateStock = $pdo->prepare("
                UPDATE inventory 
                SET current_stock = current_stock - ? 
                WHERE product_id = ?
            ");
            $updateStock->execute([$item['quantity'], $item['product_id']]);
            
            // Log stock movement
            $logStmt = $pdo->prepare("
                INSERT INTO stock_movements 
                (product_id, movement_type, quantity, reference_type, reference_id, created_by, created_at)
                VALUES (?, 'out', ?, 'sale', ?, ?, NOW())
            ");
            $logStmt->execute([
                $item['product_id'],
                $item['quantity'],
                $orderDbId,
                $_SESSION['admin_id'] ?? null
            ]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Order created successfully',
            'order_id' => $orderId,
            'order_db_id' => $orderDbId
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
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
