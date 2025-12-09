<?php
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="payments_export_' . date('Y-m-d_H-i-s') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'Payment ID', 
    'Order Number', 
    'Customer Name', 
    'Amount', 
    'Payment Method', 
    'Reference Number', 
    'Status', 
    'Verified By', 
    'Date Recorded'
]);

try {
    // Get filter parameters (optional support for future UI updates)
    $status = $_GET['status'] ?? '';
    $search = $_GET['search'] ?? '';
    $date = $_GET['date'] ?? '';
    
    // Build query
    $sql = "
        SELECT 
            p.id,
            o.order_id as order_number,
            c.name as customer_name,
            p.amount,
            p.payment_method,
            p.reference_number,
            p.status,
            a.full_name as verified_by_name,
            p.created_at
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
    
    // Fetch and output rows
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format data if necessary
        $row['amount'] = number_format($row['amount'], 2);
        $row['payment_method'] = ucfirst(str_replace('_', ' ', $row['payment_method']));
        $row['status'] = ucfirst($row['status']);
        $row['created_at'] = date('M d, Y h:i A', strtotime($row['created_at']));
        
        fputcsv($output, $row);
    }
    
} catch (PDOException $e) {
    // In case of error, output it to the CSV (or just log it)
    fputcsv($output, ['Error exporting data: ' . $e->getMessage()]);
}

fclose($output);
?>
