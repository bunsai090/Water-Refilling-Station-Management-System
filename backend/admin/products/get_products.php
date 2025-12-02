<?php
// Get products functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get search parameter if provided
    $search = $_GET['search'] ?? '';
    
    // Prepare SQL query with inventory data
    if (!empty($search)) {
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.description, 
                p.category, 
                p.unit_price, 
                p.status, 
                p.created_at,
                COALESCE(i.current_stock, 0) as current_stock,
                COALESCE(i.minimum_stock, 10) as minimum_stock,
                CASE 
                    WHEN COALESCE(i.current_stock, 0) <= 0 THEN 'out_of_stock'
                    WHEN COALESCE(i.current_stock, 0) < COALESCE(i.minimum_stock, 10) THEN 'low_stock'
                    ELSE 'in_stock'
                END as stock_status
            FROM products p
            LEFT JOIN inventory i ON p.id = i.product_id
            WHERE p.status = 'active' AND (p.name LIKE ? OR p.category LIKE ? OR p.description LIKE ?)
            ORDER BY p.name ASC
        ");
        $searchTerm = "%{$search}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.description, 
                p.category, 
                p.unit_price, 
                p.status, 
                p.created_at,
                COALESCE(i.current_stock, 0) as current_stock,
                COALESCE(i.minimum_stock, 10) as minimum_stock,
                CASE 
                    WHEN COALESCE(i.current_stock, 0) <= 0 THEN 'out_of_stock'
                    WHEN COALESCE(i.current_stock, 0) < COALESCE(i.minimum_stock, 10) THEN 'low_stock'
                    ELSE 'in_stock'
                END as stock_status
            FROM products p
            LEFT JOIN inventory i ON p.id = i.product_id
            WHERE p.status = 'active'
            ORDER BY p.name ASC
        ");
        $stmt->execute();
    }
    
    // Fetch all products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode($products);
    
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
