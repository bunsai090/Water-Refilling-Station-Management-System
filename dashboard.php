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
            
            <div class="dashboard-grid">
                <!-- Row 1: Sales Chart (8 cols) & Recent Activities (4 cols) -->
                <div class="grid-col-span-8">
                    <div class="chart-container" style="height: 100%; margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3 style="margin: 0;">Sales Overview</h3>
                            <select id="salesPeriodFilter" class="form-control" style="width: auto; padding: 8px 12px; border-radius: 5px; border: 1px solid #ddd;">
                                <option value="7">Last 7 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 3 Months</option>
                                <option value="180">Last 6 Months</option>
                            </select>
                        </div>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                
                <div class="grid-col-span-4">
                    <div class="chart-container" style="height: 100%; margin-bottom: 0;">
                        <h3>Recent Activities</h3>
                        <div class="activity-list" id="recent-activities">
                            <p>Loading recent activities...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <!-- Row 2: Inventory Chart (4 cols) & Stock Table (8 cols) -->
                <div class="grid-col-span-4">
                    <div class="chart-container" style="height: 100%; margin-bottom: 0;">
                        <h3>Inventory Status</h3>
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
                
                <div class="grid-col-span-8">
                    <div class="card" style="height: 100%; margin-bottom: 0;">
                        <div class="card-header">
                            <h3>Product Stock Levels</h3>
                        </div>
                        <div class="table-container">
                            <table class="data-table" id="stockLevelsTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Current Stock</th>
                                        <th>Minimum Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center loading">
                                            <div class="spinner"></div>
                                            Loading stock levels...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'frontend/assets/includes/footer.php'; ?>
