<?php
// Add stock functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate required fields
    if (empty($data['product_id']) || empty($data['quantity'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Product and quantity are required fields'
        ]);
        exit;
    }
    
    $product_id = intval($data['product_id']);
    $quantity = intval($data['quantity']);
    $notes = $data['notes'] ?? '';
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Check if inventory record exists for this product
        $checkStmt = $pdo->prepare("SELECT id FROM inventory WHERE product_id = ?");
        $checkStmt->execute([$product_id]);
        $inventoryExists = $checkStmt->fetch();
        
        if ($inventoryExists) {
            // Update existing inventory
            $stmt = $pdo->prepare("
                UPDATE inventory 
                SET current_stock = current_stock + ?,
                    last_updated = NOW()
                WHERE product_id = ?
            ");
            $stmt->execute([$quantity, $product_id]);
        } else {
            // Create new inventory record
            $stmt = $pdo->prepare("
                INSERT INTO inventory (product_id, current_stock, minimum_stock, last_updated)
                VALUES (?, ?, 10, NOW())
            ");
            $stmt->execute([$product_id, $quantity]);
        }
        
        // Log stock movement
        $logStmt = $pdo->prepare("
            INSERT INTO stock_movements 
            (product_id, movement_type, quantity, reference_type, notes, created_by, created_at)
            VALUES (?, 'in', ?, 'purchase', ?, ?, NOW())
        ");
        $logStmt->execute([
            $product_id,
            $quantity,
            $notes,
            $_SESSION['admin_id'] ?? null
        ]);
        
        // Commit transaction
        $pdo->commit();
        
        // Get updated stock info
        $stockStmt = $pdo->prepare("
            SELECT i.current_stock, p.name as product_name
            FROM inventory i
            INNER JOIN products p ON i.product_id = p.id
            WHERE i.product_id = ?
        ");
        $stockStmt->execute([$product_id]);
        $stockInfo = $stockStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'message' => 'Stock added successfully',
            'product_name' => $stockInfo['product_name'] ?? '',
            'new_stock' => $stockInfo['current_stock'] ?? 0
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
