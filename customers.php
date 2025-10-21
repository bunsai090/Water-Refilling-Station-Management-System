<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Manage Customers";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="page-actions">
            <h2>Customer Management</h2>
            <button class="btn btn-primary" onclick="openAddCustomerModal()">
                <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#users"></use></svg>
                Add New Customer
            </button>
        </div>
        
        <div class="dashboard-content">
            <div class="card">
                <div class="card-header">
                    <h3>Customer List</h3>
                    <div class="search-box">
                        <input type="text" id="customerSearch" placeholder="Search customers..." onkeyup="searchCustomers()">
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="data-table" id="customersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center loading">
                                    <div class="spinner"></div>
                                    Loading customers...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div id="addCustomerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Customer</h3>
            <button class="modal-close" onclick="closeModal('addCustomerModal')">&times;</button>
        </div>
        <form id="addCustomerForm">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="customerName">Full Name</label>
                        <input type="text" id="customerName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Phone Number</label>
                        <input type="tel" id="customerPhone" name="phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="customerEmail">Email Address</label>
                    <input type="email" id="customerEmail" name="email">
                </div>
                <div class="form-group">
                    <label for="customerAddress">Address</label>
                    <textarea id="customerAddress" name="address" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addCustomerModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddCustomerModal() {
    document.getElementById('addCustomerModal').classList.add('show');
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

function searchCustomers() {
    // Implement customer search functionality
    const searchTerm = document.getElementById('customerSearch').value;
    // Add AJAX call to search customers
}

// Load customers on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
});

function loadCustomers() {
    fetch('backend/admin/customers/get_customers.php')
        .then(response => response.json())
        .then(data => {
            // Populate customers table
            console.log('Customers loaded:', data);
        })
        .catch(error => {
            console.error('Error loading customers:', error);
        });
}
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
