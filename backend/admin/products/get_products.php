<?php
// Get products functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get search parameter if provided
    $search = $_GET['search'] ?? '';
    
    // Prepare SQL query
    if (!empty($search)) {
        $stmt = $pdo->prepare("
            SELECT id, name, description, category, unit_price, status, created_at 
            FROM products 
            WHERE status = 'active' AND (name LIKE ? OR category LIKE ? OR description LIKE ?)
            ORDER BY name ASC
        ");
        $searchTerm = "%{$search}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $pdo->prepare("
            SELECT id, name, description, category, unit_price, status, created_at 
            FROM products 
            WHERE status = 'active'
            ORDER BY name ASC
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
