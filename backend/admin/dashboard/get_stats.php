<?php
// Get dashboard statistics
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    $stats = [];
    
    // Total Customers (active)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers WHERE status = 'active'");
    $stats['total_customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Pending Orders
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    $stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Total Revenue (this month, verified payments only)
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM payments 
        WHERE YEAR(created_at) = YEAR(CURDATE()) 
        AND MONTH(created_at) = MONTH(CURDATE())
        AND status = 'verified'
    ");
    $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Low Stock Items
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM inventory i
        INNER JOIN products p ON i.product_id = p.id
        WHERE i.current_stock > 0 
        AND i.current_stock <= i.minimum_stock
        AND p.status = 'active'
    ");
    $stats['low_stock_items'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get period from request (default to 7 days)
    $period = isset($_GET['period']) ? intval($_GET['period']) : 7;
    
    // Determine date format based on period
    // For 7 and 30 days, show day format (e.g., "Dec 05")
    // For 90 and 180 days, show month format (e.g., "Dec")
    $dateFormat = ($period <= 30) ? '%b %d' : '%b %Y';
    $groupBy = ($period <= 30) ? 'DATE(p.created_at)' : 'YEAR(p.created_at), MONTH(p.created_at)';
    
    // Sales data for chart
    $salesData = [];
    $salesStmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(p.created_at, :dateFormat) as period_label,
            COALESCE(SUM(p.amount), 0) as total
        FROM payments p
        WHERE p.created_at >= DATE_SUB(CURDATE(), INTERVAL :period DAY)
        AND p.status = 'verified'
        GROUP BY $groupBy
        ORDER BY p.created_at ASC
    ");
    $salesStmt->bindParam(':dateFormat', $dateFormat, PDO::PARAM_STR);
    $salesStmt->bindParam(':period', $period, PDO::PARAM_INT);
    $salesStmt->execute();
    $salesData = $salesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Inventory status for chart
    $inventoryStmt = $pdo->query("
        SELECT 
            CASE 
                WHEN i.current_stock = 0 THEN 'Out of Stock'
                WHEN i.current_stock <= i.minimum_stock THEN 'Low Stock'
                ELSE 'In Stock'
            END as status,
            COUNT(*) as count
        FROM inventory i
        INNER JOIN products p ON i.product_id = p.id
        WHERE p.status = 'active'
        GROUP BY status
    ");
    $inventoryData = $inventoryStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent activities (last 10)
    $activitiesStmt = $pdo->query("
        SELECT 
            'order' as type,
            o.order_id as reference,
            c.name as customer_name,
            o.total_amount,
            o.created_at
        FROM orders o
        INNER JOIN customers c ON o.customer_id = c.id
        ORDER BY o.created_at DESC
        LIMIT 10
    ");
    $recentActivities = $activitiesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log data for debugging (comment out in production)
    error_log("Dashboard Stats: " . print_r($stats, true));
    error_log("Sales Data Count: " . count($salesData));
    error_log("Inventory Data Count: " . count($inventoryData));
    error_log("Recent Activities Count: " . count($recentActivities));
    
    // Return combined data
    echo json_encode([
        'stats' => $stats,
        'sales_data' => $salesData,
        'inventory_data' => $inventoryData,
        'recent_activities' => $recentActivities
    ], JSON_PRETTY_PRINT);
    
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
