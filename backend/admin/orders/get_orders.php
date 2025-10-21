<?php
// Get orders functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get filter parameters
    $status = $_GET['status'] ?? '';
    $search = $_GET['search'] ?? '';
    
    // Build query
    $sql = "
        SELECT 
            o.id,
            o.order_id,
            o.customer_id,
            c.name as customer_name,
            c.phone as customer_phone,
            o.total_amount,
            o.status,
            o.delivery_address,
            o.delivery_date,
            o.notes,
            o.created_at,
            GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') as items
        FROM orders o
        INNER JOIN customers c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE 1=1
    ";
    
    $params = [];
    
    // Apply status filter
    if (!empty($status)) {
        $sql .= " AND o.status = ?";
        $params[] = $status;
    }
    
    // Apply search filter
    if (!empty($search)) {
        $sql .= " AND (o.order_id LIKE ? OR c.name LIKE ? OR c.phone LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " GROUP BY o.id ORDER BY o.created_at DESC";
    
    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    // Fetch results
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode($orders);
    
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
