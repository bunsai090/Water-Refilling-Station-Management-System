<div class="sidebar">
    <div class="sidebar-header">
        <img src="frontend/assets/images/logo.svg" alt="Logo" width="40" height="40">
        <h3>Water Station</h3>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#dashboard"></use></svg>
                Dashboard
            </a>
        </li>
        <li>
            <a href="customers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#users"></use></svg>
                Customers
            </a>
        </li>
        <li>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#orders"></use></svg>
                Orders
            </a>
        </li>
        <li>
            <a href="inventory.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#inventory"></use></svg>
                Inventory
            </a>
        </li>
        <li>
            <a href="payments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#payments"></use></svg>
                Payments
            </a>
        </li>
        <li>
            <a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#reports"></use></svg>
                Reports
            </a>
        </li>
        <li>
            <a href="backup.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'backup.php' ? 'active' : ''; ?>">
                <svg class="icon"><use href="frontend/assets/svg/icons.svg#settings"></use></svg>
                Backup
            </a>
        </li>
    </ul>
</div>
