<?php
// Record payment functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate required fields
    if (empty($data['order_id']) || empty($data['amount']) || empty($data['payment_method'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Order, amount, and payment method are required'
        ]);
        exit;
    }
    
    $order_id = intval($data['order_id']);
    $amount = floatval($data['amount']);
    $payment_method = $data['payment_method'];
    $reference_number = $data['reference_number'] ?? null;
    $admin_id = $_SESSION['admin_id'] ?? null;
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Verify order exists and get order details
        $orderStmt = $pdo->prepare("SELECT id, total_amount, status FROM orders WHERE id = ?");
        $orderStmt->execute([$order_id]);
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            throw new Exception('Order not found');
        }
        
        // Check if payment already exists for this order
        $checkStmt = $pdo->prepare("SELECT id FROM payments WHERE order_id = ? AND status != 'rejected'");
        $checkStmt->execute([$order_id]);
        if ($checkStmt->fetch()) {
            throw new Exception('Payment already recorded for this order');
        }
        
        // Insert payment record
        $stmt = $pdo->prepare("
            INSERT INTO payments (
                order_id, amount, payment_method, reference_number, 
                payment_proof, status, verified_by, verified_at, created_at
            ) VALUES (?, ?, ?, ?, NULL, 'verified', ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $order_id,
            $amount,
            $payment_method,
            $reference_number,
            $admin_id
        ]);
        
        $payment_id = $pdo->lastInsertId();
        
        // Update order status to 'processing' or 'delivered'
        $updateOrderStmt = $pdo->prepare("
            UPDATE orders 
            SET status = 'processing' 
            WHERE id = ?
        ");
        $updateOrderStmt->execute([$order_id]);
        
        // Commit transaction
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'payment_id' => $payment_id
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
