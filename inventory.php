<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Inventory Management";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="page-actions">
            <h2>Inventory Management</h2>
            <div>
                <button class="btn btn-primary" onclick="openAddStockModal()" id="addStockBtn">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#inventory"></use></svg>
                    Add Stock
                </button>
                <button class="btn btn-secondary" onclick="generateInventoryReport()">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#reports"></use></svg>
                    Generate Report
                </button>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="dashboard-stats">
                <div class="stat-card primary">
                    <h3>Total Items</h3>
                    <div class="stat-number" id="totalItems">0</div>
                    <p>Products in inventory</p>
                </div>
                <div class="stat-card warning">
                    <h3>Low Stock Alerts</h3>
                    <div class="stat-number" id="lowStockCount">0</div>
                    <p>Items need restocking</p>
                </div>
                <div class="stat-card danger">
                    <h3>Out of Stock</h3>
                    <div class="stat-number" id="outOfStockCount">0</div>
                    <p>Items unavailable</p>
                </div>
            </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Inventory Items</h3>
                <div class="search-box">
                    <input type="text" id="inventorySearch" placeholder="Search items..." onkeyup="searchInventory()">
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Minimum Stock</th>
                            <th>Unit Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center loading">
                                <div class="spinner"></div>
                                Loading inventory...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div id="addStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Stock</h3>
            <button class="modal-close" onclick="closeModal('addStockModal')">&times;</button>
        </div>
        <form id="addStockForm">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="stockProduct">Product *</label>
                        <select id="stockProduct" name="product_id" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stockQuantity">Quantity to Add *</label>
                        <input type="number" id="stockQuantity" name="quantity" min="1" required placeholder="Enter quantity">
                    </div>
                </div>
                <div class="form-group">
                    <label for="stockNotes">Notes (Optional)</label>
                    <textarea id="stockNotes" name="notes" rows="3" placeholder="Add any notes about this stock addition..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addStockModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Stock</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddStockModal() {
    document.getElementById('addStockModal').classList.add('show');
    loadProductsForStock();
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
}

function loadProductsForStock() {
    // Load products for stock addition
    fetch('backend/admin/products/get_products.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('stockProduct');
            select.innerHTML = '<option value="">Select Product</option>';
            if (data && data.length > 0) {
                data.forEach(product => {
                    select.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
}

function searchInventory() {
    const searchTerm = document.getElementById('inventorySearch').value;
    // Implement inventory search
}

function generateInventoryReport() {
    window.open('backend/admin/reports/generate_inventory_report.php', '_blank');
}

function loadInventory() {
    // Simulate loading inventory
    const tbody = document.querySelector('#inventoryTable tbody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center loading">
                <div class="spinner"></div>
                Loading inventory...
            </td>
        </tr>
    `;
    
    // You can implement actual inventory loading here
    setTimeout(() => {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center">No inventory items found. Click "Add Stock" to get started.</td>
            </tr>
        `;
    }, 1000);
}

// Load inventory on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    loadInventory();
    
    // Add alternative event listener for the Add Stock button
    const addStockBtn = document.getElementById('addStockBtn');
    if (addStockBtn) {
        console.log('Add Stock button found, adding click listener');
        addStockBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add Stock button clicked via event listener');
            openAddStockModal();
        });
    } else {
        console.error('Add Stock button not found!');
    }
    
    // Test modal availability
    const modal = document.getElementById('addStockModal');
    console.log('Modal element found:', !!modal);
});

// Add form submission handler
document.getElementById('addStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Here you would normally send the data to the server
    console.log('Adding stock:', Object.fromEntries(formData));
    
    // Show success message
    alert('Stock added successfully!');
    
    // Close modal and reset form
    closeModal('addStockModal');
    this.reset();
    
    // Reload inventory
    loadInventory();
});

// Debug function to test modal
function testModal() {
    console.log('Testing modal...');
    const modal = document.getElementById('addStockModal');
    console.log('Modal element:', modal);
    if (modal) {
        modal.classList.add('show');
        console.log('Modal should be visible now');
    } else {
        console.error('Modal element not found!');
    }
}

// Test if the function is available globally
console.log('openAddStockModal function:', typeof openAddStockModal);

// Make functions globally available for debugging
window.openAddStockModal = openAddStockModal;
window.testModal = testModal;
window.closeModal = closeModal;

console.log('Inventory page JavaScript loaded successfully');

function updateInventorySummary(data) {
    document.getElementById('totalItems').textContent = data.length;
    
    let lowStockCount = 0;
    let outOfStockCount = 0;
    
    data.forEach(item => {
        if (item.current_stock <= 0) {
            outOfStockCount++;
        } else if (item.current_stock <= item.minimum_stock) {
            lowStockCount++;
        }
    });
    
    document.getElementById('lowStockCount').textContent = lowStockCount;
    document.getElementById('outOfStockCount').textContent = outOfStockCount;
}

document.addEventListener('DOMContentLoaded', function() {
    loadInventory();
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
