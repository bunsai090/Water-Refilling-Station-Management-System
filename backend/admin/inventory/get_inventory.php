<?php
// Get inventory functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get search parameter
    $search = $_GET['search'] ?? '';
    
    // Build query to fetch products with inventory
    $sql = "
        SELECT 
            p.id,
            p.name as product_name,
            p.description,
            p.category,
            p.unit_price,
            p.status,
            COALESCE(i.current_stock, 0) as current_stock,
            COALESCE(i.minimum_stock, 10) as minimum_stock,
            CASE 
                WHEN COALESCE(i.current_stock, 0) <= 0 THEN 'out_of_stock'
                WHEN COALESCE(i.current_stock, 0) < COALESCE(i.minimum_stock, 10) THEN 'low_stock'
                ELSE 'in_stock'
            END as stock_status,
            p.created_at
        FROM products p
        LEFT JOIN inventory i ON p.id = i.product_id
        WHERE p.status = 'active'
    ";
    
    $params = [];
    
    // Apply search filter
    if (!empty($search)) {
        $sql .= " AND (p.name LIKE ? OR p.category LIKE ? OR p.description LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " ORDER BY p.name ASC";
    
    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    // Fetch results
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate statistics
    $stats = [
        'total_items' => count($inventory),
        'low_stock_count' => 0,
        'out_of_stock_count' => 0,
        'in_stock_count' => 0
    ];
    
    foreach ($inventory as $item) {
        if ($item['stock_status'] === 'low_stock') {
            $stats['low_stock_count']++;
        } elseif ($item['stock_status'] === 'out_of_stock') {
            $stats['out_of_stock_count']++;
        } else {
            $stats['in_stock_count']++;
        }
    }
    
    // Log for debugging
    error_log("Inventory Stats: Total={$stats['total_items']}, Low={$stats['low_stock_count']}, Out={$stats['out_of_stock_count']}, In={$stats['in_stock_count']}");
    
    // Return JSON response with both inventory and stats
    echo json_encode([
        'inventory' => $inventory,
        'stats' => $stats
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
