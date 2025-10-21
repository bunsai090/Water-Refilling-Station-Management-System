<?php
// Generate inventory report functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

// Fetch inventory data
$stmt = $pdo->query("
    SELECT 
        p.id,
        p.name as product_name,
        p.category,
        p.unit_price,
        COALESCE(i.current_stock, 0) as current_stock,
        COALESCE(i.minimum_stock, 10) as minimum_stock,
        CASE 
            WHEN COALESCE(i.current_stock, 0) = 0 THEN 'Out of Stock'
            WHEN COALESCE(i.current_stock, 0) > 0 AND COALESCE(i.current_stock, 0) < COALESCE(i.minimum_stock, 10) THEN 'Low Stock'
            ELSE 'In Stock'
        END as stock_status,
        COALESCE(i.current_stock, 0) * p.unit_price as stock_value
    FROM products p
    LEFT JOIN inventory i ON p.id = i.product_id
    WHERE p.status = 'active'
    ORDER BY p.name ASC
");
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$total_products = count($inventory);
$total_stock_value = array_sum(array_column($inventory, 'stock_value'));
$low_stock_count = count(array_filter($inventory, fn($item) => $item['stock_status'] === 'Low Stock'));
$out_of_stock_count = count(array_filter($inventory, fn($item) => $item['stock_status'] === 'Out of Stock'));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventory Report - <?php echo date('F d, Y'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #28a745;
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
            border-left: 4px solid #28a745;
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
            color: #28a745;
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
            background: #28a745;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .stock-status {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .stock-status.in-stock { background: #d4edda; color: #155724; }
        .stock-status.low-stock { background: #fff3cd; color: #856404; }
        .stock-status.out-of-stock { background: #f8d7da; color: #721c24; }
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
        <h1>Inventory Report</h1>
        <p><strong>Water Refilling Station Management System</strong></p>
        <p>Generated: <?php echo date('F d, Y g:i A'); ?></p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Products</h3>
            <div class="value"><?php echo number_format($total_products); ?></div>
        </div>
        <div class="summary-card">
            <h3>Stock Value</h3>
            <div class="value">₱<?php echo number_format($total_stock_value, 2); ?></div>
        </div>
        <div class="summary-card">
            <h3>Low Stock Items</h3>
            <div class="value" style="color: #ffc107;"><?php echo number_format($low_stock_count); ?></div>
        </div>
        <div class="summary-card">
            <h3>Out of Stock</h3>
            <div class="value" style="color: #dc3545;"><?php echo number_format($out_of_stock_count); ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>Minimum Stock</th>
                <th>Unit Price</th>
                <th>Stock Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($inventory)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">No inventory records found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo number_format($item['current_stock']); ?></td>
                        <td><?php echo number_format($item['minimum_stock']); ?></td>
                        <td>₱<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td>₱<?php echo number_format($item['stock_value'], 2); ?></td>
                        <td>
                            <span class="stock-status <?php echo strtolower(str_replace(' ', '-', $item['stock_status'])); ?>">
                                <?php echo $item['stock_status']; ?>
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
        <button onclick="window.print()" style="padding: 10px 30px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">Print Report</button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-left: 10px;">Close</button>
    </div>
</body>
</html>
