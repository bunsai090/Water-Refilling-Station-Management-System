<?php
// Get report data
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    
    // Sales Trend (daily sales within date range)
    $salesTrendStmt = $pdo->prepare("
        SELECT 
            DATE(o.created_at) as date,
            COUNT(*) as order_count,
            COALESCE(SUM(o.total_amount), 0) as total_sales
        FROM orders o
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY DATE(o.created_at)
        ORDER BY date ASC
    ");
    $salesTrendStmt->execute([$start_date, $end_date]);
    $salesTrend = $salesTrendStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $salesTrendData = [
        'labels' => array_map(function($row) {
            return date('M d', strtotime($row['date']));
        }, $salesTrend),
        'values' => array_map(function($row) {
            return floatval($row['total_sales']);
        }, $salesTrend)
    ];
    
    // Revenue Trend (monthly revenue)
    $revenueTrendStmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(p.created_at, '%Y-%m') as month,
            DATE_FORMAT(p.created_at, '%b %Y') as month_label,
            COALESCE(SUM(p.amount), 0) as total_revenue
        FROM payments p
        WHERE p.status = 'verified'
        AND DATE(p.created_at) BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(p.created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $revenueTrendStmt->execute([$start_date, $end_date]);
    $revenueTrend = $revenueTrendStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $revenueTrendData = [
        'labels' => array_column($revenueTrend, 'month_label'),
        'values' => array_map(function($row) {
            return floatval($row['total_revenue']);
        }, $revenueTrend)
    ];
    
    // Top Products (by quantity sold)
    $topProductsStmt = $pdo->prepare("
        SELECT 
            p.name,
            SUM(oi.quantity) as total_quantity,
            COALESCE(SUM(oi.total_price), 0) as total_revenue
        FROM order_items oi
        INNER JOIN products p ON oi.product_id = p.id
        INNER JOIN orders o ON oi.order_id = o.id
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY p.id, p.name
        ORDER BY total_quantity DESC
        LIMIT 5
    ");
    $topProductsStmt->execute([$start_date, $end_date]);
    $topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $topProductsData = [
        'labels' => array_column($topProducts, 'name'),
        'values' => array_map(function($row) {
            return intval($row['total_quantity']);
        }, $topProducts)
    ];
    
    // Customer Growth (new customers per month)
    $customerGrowthStmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            DATE_FORMAT(created_at, '%b %Y') as month_label,
            COUNT(*) as new_customers
        FROM customers
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $customerGrowthStmt->execute([$start_date, $end_date]);
    $customerGrowth = $customerGrowthStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $customerGrowthData = [
        'labels' => array_column($customerGrowth, 'month_label'),
        'values' => array_map(function($row) {
            return intval($row['new_customers']);
        }, $customerGrowth)
    ];
    
    // Detailed Sales Data for table
    $salesDataStmt = $pdo->prepare("
        SELECT 
            DATE(o.created_at) as date,
            o.order_id,
            c.name as customer,
            GROUP_CONCAT(p.name SEPARATOR ', ') as products,
            SUM(oi.quantity) as quantity,
            o.total_amount as amount,
            o.status
        FROM orders o
        INNER JOIN customers c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT 50
    ");
    $salesDataStmt->execute([$start_date, $end_date]);
    $salesData = $salesDataStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return all data
    echo json_encode([
        'sales_trend' => $salesTrendData,
        'revenue_trend' => $revenueTrendData,
        'top_products' => $topProductsData,
        'customer_growth' => $customerGrowthData,
        'sales' => $salesData
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
