<?php
// Get customers functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get search parameter if provided
    $search = $_GET['search'] ?? '';
    
    // Prepare SQL query
    if (!empty($search)) {
        $stmt = $pdo->prepare("
            SELECT id, name, phone, email, address, status, created_at 
            FROM customers 
            WHERE name LIKE ? OR phone LIKE ? OR email LIKE ?
            ORDER BY created_at DESC
        ");
        $searchTerm = "%{$search}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $pdo->prepare("
            SELECT id, name, phone, email, address, status, created_at 
            FROM customers 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
    }
    
    // Fetch all customers
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode($customers);
    
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
