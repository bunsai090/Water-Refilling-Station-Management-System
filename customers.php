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
    const tbody = document.querySelector('#customersTable tbody');
    
    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center loading">
                <div class="spinner"></div>
                Loading customers...
            </td>
        </tr>
    `;
    
    fetch('backend/admin/customers/get_customers.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Customers loaded:', data);
            displayCustomers(data);
        })
        .catch(error => {
            console.error('Error loading customers:', error);
            // Show error or empty state
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">
                        <p>No customers found or error loading data.</p>
                        <p style="color: #6c757d; font-size: 14px;">Click "Add New Customer" to get started.</p>
                    </td>
                </tr>
            `;
        });
}

function displayCustomers(customers) {
    const tbody = document.querySelector('#customersTable tbody');
    
    if (!customers || customers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
                    <p>No customers found.</p>
                    <p style="color: #6c757d; font-size: 14px;">Click "Add New Customer" to get started.</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = '';
    customers.forEach(customer => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${customer.id || '-'}</td>
            <td>${customer.name || '-'}</td>
            <td>${customer.phone || '-'}</td>
            <td>${customer.address || '-'}</td>
            <td>${customer.email || '-'}</td>
            <td><span class="status-badge ${customer.status === 'active' ? 'active' : 'inactive'}">${customer.status || 'active'}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-view" onclick="viewCustomer(${customer.id})" title="View">👁️</button>
                    <button class="btn btn-sm btn-edit" onclick="editCustomer(${customer.id})" title="Edit">✏️</button>
                    <button class="btn btn-sm btn-delete" onclick="deleteCustomer(${customer.id})" title="Delete">🗑️</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Add form submission handler
document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
    
    // Handle add customer form submission
    const addCustomerForm = document.getElementById('addCustomerForm');
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const customerData = Object.fromEntries(formData);
            
            console.log('Adding customer:', customerData);
            
            // Simulate adding customer (replace with actual API call)
            fetch('backend/admin/customers/add_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(customerData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Customer added:', data);
                
                if (data.success) {
                    // Show success message
                    alert(data.message || 'Customer added successfully!');
                    
                    // Close modal and reset form
                    closeModal('addCustomerModal');
                    addCustomerForm.reset();
                    
                    // Reload customers list
                    loadCustomers();
                } else {
                    // Show error message from server
                    alert(data.message || 'Error adding customer. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error adding customer:', error);
                alert('Error adding customer. Please check your connection and try again.');
            });
        });
    }
});

function viewCustomer(id) {
    console.log('View customer:', id);
    // Implement view customer functionality
}

function editCustomer(id) {
    console.log('Edit customer:', id);
    // Implement edit customer functionality
}

function deleteCustomer(id) {
    if (confirm('Are you sure you want to delete this customer?')) {
        console.log('Delete customer:', id);
        // Implement delete customer functionality
        fetch(`backend/admin/customers/delete_customer.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            alert('Customer deleted successfully!');
            loadCustomers();
        })
        .catch(error => {
            console.error('Error deleting customer:', error);
            alert('Error deleting customer. Please try again.');
        });
    }
}
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
