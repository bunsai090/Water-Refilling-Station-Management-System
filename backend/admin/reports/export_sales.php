<?php
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

// Get date range from request
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sales_report_' . $start_date . '_to_' . $end_date . '.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Write CSV headers
fputcsv($output, ['Date', 'Order ID', 'Customer', 'Products', 'Quantity', 'Amount', 'Status']);

try {
    // Prepare query to fetch sales data
    // Using the same query structure as get_report_data.php but without LIMIT
    $stmt = $pdo->prepare("
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
    ");
    
    $stmt->execute([$start_date, $end_date]);
    
    // Fetch and write data rows
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['date'],
            $row['order_id'],
            $row['customer'],
            $row['products'],
            $row['quantity'],
            number_format($row['amount'], 2),
            ucfirst($row['status'])
        ]);
    }
    
} catch (PDOException $e) {
    // In case of error, write it to the CSV
    fputcsv($output, ['Error generating report: ' . $e->getMessage()]);
}

fclose($output);
exit;
?>
