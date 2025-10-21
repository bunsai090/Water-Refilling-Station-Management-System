<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Dashboard";
$additional_css = ['frontend/assets/css/dashboard.css'];
$additional_js = ['frontend/assets/js/dashboard.js', 'frontend/assets/js/charts.js'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="dashboard-content">
            <div class="dashboard-stats">
                <div class="stat-card primary">
                    <h3>Total Customers</h3>
                    <div class="stat-number" id="total-customers">0</div>
                    <p>Active customers</p>
                </div>
                
                <div class="stat-card warning">
                    <h3>Pending Orders</h3>
                    <div class="stat-number" id="pending-orders">0</div>
                    <p>Orders to process</p>
                </div>
                
                <div class="stat-card success">
                    <h3>Total Revenue</h3>
                    <div class="stat-number" id="total-revenue">₱0</div>
                    <p>This month</p>
                </div>
                
                <div class="stat-card danger">
                    <h3>Low Stock Items</h3>
                    <div class="stat-number" id="low-stock-items">0</div>
                    <p>Need restocking</p>
                </div>
            </div>
            
            <div class="chart-container">
                <h3>Sales Overview</h3>
                <canvas id="salesChart"></canvas>
            </div>
            
            <div class="chart-container">
                <h3>Inventory Status</h3>
                <canvas id="inventoryChart"></canvas>
            </div>
            
            <div class="chart-container">
                <h3>Recent Activities</h3>
                <div class="activity-list" id="recent-activities">
                    <p>Loading recent activities...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'frontend/assets/includes/footer.php'; ?>
