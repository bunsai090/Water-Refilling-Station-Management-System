<div class="sidebar">
    <div class="sidebar-header">
        <img src="frontend/assets/images/logo.svg" alt="Logo" width="40" height="40">
        <h3>Water Station</h3>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="bi bi-grid-1x2-fill icon"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="customers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
                <i class="bi bi-people-fill icon"></i>
                Customers
            </a>
        </li>
        <li>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                <i class="bi bi-cart-fill icon"></i>
                Orders
            </a>
        </li>
        <li>
            <a href="inventory.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>">
                <i class="bi bi-box-seam-fill icon"></i>
                Inventory
            </a>
        </li>
        <li>
            <a href="payments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>">
                <i class="bi bi-credit-card-fill icon"></i>
                Payments
            </a>
        </li>
        <li>
            <a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <i class="bi bi-bar-chart-fill icon"></i>
                Reports
            </a>
        </li>
        <li>
            <a href="backup.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'backup.php' ? 'active' : ''; ?>">
                <i class="bi bi-database-fill icon"></i>
                Backup
            </a>
        </li>
    </ul>
</div>
