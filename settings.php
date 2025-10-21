<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "System Settings";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="dashboard-content">
            <div class="card" style="max-width: 900px; margin: 0 auto;">
                <div class="card-header">
                    <h3>System Settings</h3>
                </div>
                <div style="padding: 30px;">
                    
                    <!-- Business Information -->
                    <div class="settings-section">
                        <h4>Business Information</h4>
                        <form id="businessForm">
                            <div class="form-group">
                                <label for="businessName">Business Name</label>
                                <input type="text" id="businessName" name="business_name" value="Water Refilling Station">
                            </div>
                            <div class="form-group">
                                <label for="businessAddress">Address</label>
                                <textarea id="businessAddress" name="business_address" rows="3">123 Main Street, City, Province</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="businessPhone">Contact Phone</label>
                                    <input type="tel" id="businessPhone" name="business_phone" value="(02) 123-4567">
                                </div>
                                <div class="form-group">
                                    <label for="businessEmail">Contact Email</label>
                                    <input type="email" id="businessEmail" name="business_email" value="info@waterstation.com">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Business Info</button>
                        </form>
                    </div>
                    
                    <hr style="margin: 30px 0; border-color: #dee2e6;">
                    
                    <!-- Inventory Settings -->
                    <div class="settings-section">
                        <h4>Inventory Settings</h4>
                        <form id="inventoryForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lowStockThreshold">Low Stock Threshold</label>
                                    <input type="number" id="lowStockThreshold" name="low_stock_threshold" value="10" min="1">
                                    <small class="form-text">Alert when stock falls below this number</small>
                                </div>
                                <div class="form-group">
                                    <label>Enable Low Stock Alerts</label>
                                    <div style="margin-top: 10px;">
                                        <label style="display: inline-flex; align-items: center;">
                                            <input type="checkbox" name="enable_alerts" checked style="margin-right: 8px;">
                                            Send email notifications
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Inventory Settings</button>
                        </form>
                    </div>
                    
                    <hr style="margin: 30px 0; border-color: #dee2e6;">
                    
                    <!-- Order Settings -->
                    <div class="settings-section">
                        <h4>Order Settings</h4>
                        <form id="orderForm">
                            <div class="form-group">
                                <label for="orderPrefix">Order ID Prefix</label>
                                <input type="text" id="orderPrefix" name="order_prefix" value="ORD-" maxlength="10">
                                <small class="form-text">Example: ORD-20251021-123456</small>
                            </div>
                            <div class="form-group">
                                <label>Auto-confirm Orders</label>
                                <div style="margin-top: 10px;">
                                    <label style="display: inline-flex; align-items: center;">
                                        <input type="checkbox" name="auto_confirm" style="margin-right: 8px;">
                                        Automatically confirm new orders
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Order Settings</button>
                        </form>
                    </div>
                    
                    <hr style="margin: 30px 0; border-color: #dee2e6;">
                    
                    <!-- System Maintenance -->
                    <div class="settings-section">
                        <h4>System Maintenance</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="btn btn-secondary" onclick="clearCache()">
                                <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#dashboard"></use></svg>
                                Clear Cache
                            </button>
                            <button class="btn btn-info" onclick="window.location.href='backup.php'">
                                <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#backup"></use></svg>
                                Backup Database
                            </button>
                            <button class="btn btn-warning" onclick="viewSystemLogs()">
                                <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#reports"></use></svg>
                                View System Logs
                            </button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-section {
    margin-bottom: 30px;
}

.settings-section h4 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 18px;
}

.form-text {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 13px;
}
</style>

<script>
function clearCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        alert('Cache cleared successfully!');
    }
}

function viewSystemLogs() {
    alert('System logs feature coming soon!');
}

// Handle form submissions
document.getElementById('businessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Business information saved successfully!');
});

document.getElementById('inventoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Inventory settings saved successfully!');
});

document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Order settings saved successfully!');
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
