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
    const tbody = document.querySelector('#inventoryTable tbody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center loading">
                <div class="spinner"></div>
                Loading inventory...
            </td>
        </tr>
    `;
    
    fetch('backend/admin/inventory/get_inventory.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center" style="color: #dc3545; padding: 40px;">
                            ${data.message}
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Update statistics
            if (data.stats) {
                document.getElementById('totalItems').textContent = data.stats.total_items;
                document.getElementById('lowStockCount').textContent = data.stats.low_stock_count;
                document.getElementById('outOfStockCount').textContent = data.stats.out_of_stock_count;
            }
            
            // Display inventory items
            displayInventory(data.inventory || []);
        })
        .catch(error => {
            console.error('Error loading inventory:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center" style="color: #dc3545; padding: 40px;">
                        Error loading inventory. Please try again.
                    </td>
                </tr>
            `;
        });
}

function displayInventory(inventory) {
    const tbody = document.querySelector('#inventoryTable tbody');
    
    if (inventory.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center">No inventory items found. Click "Add Stock" to get started.</td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = inventory.map(item => `
        <tr>
            <td>${item.id}</td>
            <td>${item.product_name}</td>
            <td>${item.category || 'N/A'}</td>
            <td>${item.current_stock}</td>
            <td>${item.minimum_stock}</td>
            <td>₱${parseFloat(item.unit_price).toFixed(2)}</td>
            <td>
                <span class="status-badge ${getStockStatusClass(item.stock_status)}">
                    ${formatStockStatus(item.stock_status)}
                </span>
            </td>
            <td>
                <button class="btn-action btn-edit" onclick="editInventoryItem(${item.id})" title="Edit">
                    <svg class="icon"><use href="frontend/assets/svg/icons.svg#edit"></use></svg>
                </button>
                <button class="btn-action btn-delete" onclick="deleteInventoryItem(${item.id})" title="Delete">
                    <svg class="icon"><use href="frontend/assets/svg/icons.svg#delete"></use></svg>
                </button>
            </td>
        </tr>
    `).join('');
}

function getStockStatusClass(status) {
    const statusMap = {
        'in_stock': 'success',
        'low_stock': 'warning',
        'out_of_stock': 'danger'
    };
    return statusMap[status] || '';
}

function formatStockStatus(status) {
    const formatMap = {
        'in_stock': 'In Stock',
        'low_stock': 'Low Stock',
        'out_of_stock': 'Out of Stock'
    };
    return formatMap[status] || status;
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
    const data = {
        product_id: formData.get('product_id'),
        quantity: formData.get('quantity'),
        notes: formData.get('notes')
    };
    
    // Validate
    if (!data.product_id || !data.quantity) {
        alert('Please select a product and enter quantity');
        return;
    }
    
    console.log('Adding stock:', data);
    
    // Send to backend
    fetch('backend/admin/inventory/add_stock.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(`Stock added successfully!\n${result.product_name}: ${result.new_stock} units in stock`);
            closeModal('addStockModal');
            document.getElementById('addStockForm').reset();
            loadInventory(); // Reload inventory table
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error adding stock:', error);
        alert('Error adding stock. Please try again.');
    });
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
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
