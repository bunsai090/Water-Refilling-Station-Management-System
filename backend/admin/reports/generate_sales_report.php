<?php
// Generate sales report functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Fetch sales data
$stmt = $pdo->prepare("
    SELECT 
        DATE(o.created_at) as date,
        o.order_id,
        c.name as customer_name,
        GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') as products,
        SUM(oi.quantity) as total_quantity,
        o.total_amount,
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
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$total_orders = count($sales);
$total_revenue = array_sum(array_column($sales, 'total_amount'));
$total_items = array_sum(array_column($sales, 'total_quantity'));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report - <?php echo date('F d, Y', strtotime($start_date)); ?> to <?php echo date('F d, Y', strtotime($end_date)); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #007bff;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background: #007bff;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .status {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.processing { background: #cce5ff; color: #004085; }
        .status.delivered { background: #d4edda; color: #155724; }
        .status.cancelled { background: #f8d7da; color: #721c24; }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            color: #666;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p><strong>Water Refilling Station Management System</strong></p>
        <p>Period: <?php echo date('F d, Y', strtotime($start_date)); ?> to <?php echo date('F d, Y', strtotime($end_date)); ?></p>
        <p>Generated: <?php echo date('F d, Y g:i A'); ?></p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Orders</h3>
            <div class="value"><?php echo number_format($total_orders); ?></div>
        </div>
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <div class="value">₱<?php echo number_format($total_revenue, 2); ?></div>
        </div>
        <div class="summary-card">
            <h3>Total Items Sold</h3>
            <div class="value"><?php echo number_format($total_items); ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sales)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">No sales records found for this period</td>
                </tr>
            <?php else: ?>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($sale['date'])); ?></td>
                        <td><?php echo htmlspecialchars($sale['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($sale['products'] ?? 'N/A'); ?></td>
                        <td><?php echo $sale['total_quantity']; ?></td>
                        <td>₱<?php echo number_format($sale['total_amount'], 2); ?></td>
                        <td>
                            <span class="status <?php echo $sale['status']; ?>">
                                <?php echo ucfirst($sale['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Water Refilling Station Management System. All rights reserved.</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">Print Report</button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-left: 10px;">Close</button>
    </div>
</body>
</html>
