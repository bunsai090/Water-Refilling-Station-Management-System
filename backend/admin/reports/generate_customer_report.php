<?php
// Generate customer report functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Fetch customer data with order statistics
$stmt = $pdo->prepare("
    SELECT 
        c.id,
        c.name,
        c.email,
        c.address,
        c.status,
        c.created_at,
        COUNT(DISTINCT o.id) as total_orders,
        COALESCE(SUM(o.total_amount), 0) as total_spent,
        MAX(o.created_at) as last_order_date
    FROM customers c
    LEFT JOIN orders o ON c.id = o.customer_id AND DATE(o.created_at) BETWEEN ? AND ?
    GROUP BY c.id
    ORDER BY total_spent DESC, c.name ASC
");
$stmt->execute([$start_date, $end_date]);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$total_customers = count($customers);
$active_customers = count(array_filter($customers, fn($c) => $c['status'] === 'active'));
$total_revenue = array_sum(array_column($customers, 'total_spent'));
$customers_with_orders = count(array_filter($customers, fn($c) => $c['total_orders'] > 0));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Report - <?php echo date('F d, Y', strtotime($start_date)); ?> to <?php echo date('F d, Y', strtotime($end_date)); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #6f42c1;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #6f42c1;
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
            color: #6f42c1;
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
            background: #6f42c1;
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
        .status.active { background: #d4edda; color: #155724; }
        .status.inactive { background: #f8d7da; color: #721c24; }
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
        <h1>Customer Report</h1>
        <p><strong>Water Refilling Station Management System</strong></p>
        <p>Period: <?php echo date('F d, Y', strtotime($start_date)); ?> to <?php echo date('F d, Y', strtotime($end_date)); ?></p>
        <p>Generated: <?php echo date('F d, Y g:i A'); ?></p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Customers</h3>
            <div class="value"><?php echo number_format($total_customers); ?></div>
        </div>
        <div class="summary-card">
            <h3>Active Customers</h3>
            <div class="value"><?php echo number_format($active_customers); ?></div>
        </div>
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <div class="value">₱<?php echo number_format($total_revenue, 2); ?></div>
        </div>
        <div class="summary-card">
            <h3>With Orders</h3>
            <div class="value"><?php echo number_format($customers_with_orders); ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Total Orders</th>
                <th>Total Spent</th>
                <th>Last Order</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">No customer records found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($customer['address'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format($customer['total_orders']); ?></td>
                        <td>₱<?php echo number_format($customer['total_spent'], 2); ?></td>
                        <td><?php echo $customer['last_order_date'] ? date('M d, Y', strtotime($customer['last_order_date'])) : 'No orders'; ?></td>
                        <td>
                            <span class="status <?php echo $customer['status']; ?>">
                                <?php echo ucfirst($customer['status']); ?>
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
        <button onclick="window.print()" style="padding: 10px 30px; background: #6f42c1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">Print Report</button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-left: 10px;">Close</button>
    </div>
</body>
</html>
