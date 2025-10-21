<?php
// Get payments functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get filter parameters
    $status = $_GET['status'] ?? '';
    $search = $_GET['search'] ?? '';
    $date = $_GET['date'] ?? '';
    
    // Build query
    $sql = "
        SELECT 
            p.id,
            p.order_id,
            o.order_id as order_number,
            c.name as customer_name,
            p.amount,
            p.payment_method,
            p.reference_number,
            p.payment_proof,
            p.status,
            p.verified_by,
            p.verified_at,
            p.created_at,
            a.full_name as verified_by_name
        FROM payments p
        INNER JOIN orders o ON p.order_id = o.id
        INNER JOIN customers c ON o.customer_id = c.id
        LEFT JOIN admins a ON p.verified_by = a.id
        WHERE 1=1
    ";
    
    $params = [];
    
    // Apply status filter
    if (!empty($status)) {
        $sql .= " AND p.status = ?";
        $params[] = $status;
    }
    
    // Apply date filter
    if (!empty($date)) {
        $sql .= " AND DATE(p.created_at) = ?";
        $params[] = $date;
    }
    
    // Apply search filter
    if (!empty($search)) {
        $sql .= " AND (o.order_id LIKE ? OR c.name LIKE ? OR p.reference_number LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    // Fetch results
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate statistics
    $stats = [
        'today_total' => 0,
        'month_total' => 0,
        'pending_count' => 0
    ];
    
    // Get today's total
    $todayStmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM payments 
        WHERE DATE(created_at) = CURDATE() AND status = 'verified'
    ");
    $todayStmt->execute();
    $stats['today_total'] = $todayStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get this month's total
    $monthStmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM payments 
        WHERE YEAR(created_at) = YEAR(CURDATE()) 
        AND MONTH(created_at) = MONTH(CURDATE())
        AND status = 'verified'
    ");
    $monthStmt->execute();
    $stats['month_total'] = $monthStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get pending count
    $pendingStmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM payments 
        WHERE status = 'pending'
    ");
    $pendingStmt->execute();
    $stats['pending_count'] = $pendingStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Return JSON response with both payments and stats
    echo json_encode([
        'payments' => $payments,
        'stats' => $stats
    ]);
    
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
