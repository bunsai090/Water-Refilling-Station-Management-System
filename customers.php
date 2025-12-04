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
                        <input type="tel" id="customerPhone" name="phone" required pattern="[0-9]{11}" maxlength="11" placeholder="09123456789" title="Please enter exactly 11 digits">
                    </div>
                </div>
                <div class="form-group">
                    <label for="customerEmail">Email Address</label>
                    <input type="email" id="customerEmail" name="email" required>
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

<!-- View Customer Modal -->
<div id="viewCustomerModal" class="modal">
    <div class="modal-content modal-view">
        <div class="modal-header">
            <h3>Customer Information</h3>
            <button class="modal-close" onclick="closeModal('viewCustomerModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="customer-info-grid">
                <div class="info-row">
                    <span class="info-label">ID</span>
                    <span class="info-value" id="viewCustomerId">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value" id="viewCustomerName">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value" id="viewCustomerPhone">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value" id="viewCustomerEmail">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value" id="viewCustomerAddress">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value" id="viewCustomerStatus">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Registered</span>
                    <span class="info-value" id="viewCustomerCreated">-</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewCustomerModal')">Close</button>
        </div>
    </div>
</div>

<!-- Delete Customer Confirmation Modal -->
<div id="deleteCustomerModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Delete Customer</h3>
            <button class="modal-close" onclick="closeModal('deleteCustomerModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; padding: 20px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#FC8181" viewBox="0 0 16 16" style="margin-bottom: 20px;">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                </svg>
                <h4 style="color: #4A5568; margin-bottom: 10px; font-weight: 600;">Are you sure?</h4>
                <p style="color: #718096; margin: 0; line-height: 1.6;">
                    Do you really want to delete this customer? <br>
                    <strong id="deleteCustomerName" style="color: #4A5568;"></strong><br>
                    <small style="color: #A0AEC0;">This action cannot be undone.</small>
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteCustomerModal')">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="background: #FC8181; border-color: #FC8181;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 6px; vertical-align: middle;">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                Delete Customer
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <div class="modal-body" style="padding: 30px;">
            <div style="margin-bottom: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#68D391" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </div>
            <h3 style="color: #2D3748; margin-bottom: 10px;">Success!</h3>
            <p id="successMessage" style="color: #718096; margin-bottom: 20px;">Operation completed successfully.</p>
            <button class="btn btn-primary" onclick="closeModal('successModal')">OK</button>
        </div>
    </div>
</div>

<script>
function openAddCustomerModal() {
    // Reset form to "Add" mode
    document.getElementById('addCustomerForm').reset();
    document.getElementById('addCustomerForm').removeAttribute('data-customer-id');
    document.querySelector('#addCustomerModal .modal-header h3').textContent = 'Add New Customer';
    document.querySelector('#addCustomerForm button[type="submit"]').textContent = 'Add Customer';
    
    document.getElementById('addCustomerModal').classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
    // Reset form when closing
    if (modalId === 'addCustomerModal') {
        document.getElementById('addCustomerForm').reset();
        document.getElementById('addCustomerForm').removeAttribute('data-customer-id');
    }
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
                    <button class="btn-icon btn-icon-view" onclick="viewCustomer(${customer.id})" title="View">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 2C4.5 2 1.5 4.5 0 8c1.5 3.5 4.5 6 8 6s6.5-2.5 8-6c-1.5-3.5-4.5-6-8-6zm0 10c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4zm0-6.5c-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5-1.1-2.5-2.5-2.5z"/>
                        </svg>
                    </button>
                    <button class="btn-icon btn-icon-edit" onclick="editCustomer(${customer.id})" title="Edit">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M12.854 1.146a.5.5 0 0 1 0 .708L11.707 3l-2-2 1.147-1.146a.5.5 0 0 1 .708 0l2 2zM11 4l-8 8H1v-2l8-8 2 2z"/>
                        </svg>
                    </button>
                    <button class="btn-icon btn-icon-delete" onclick="deleteCustomer(${customer.id})" title="Delete">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Add form submission handler
document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
    
    // Add phone number input validation
    const phoneInput = document.getElementById('customerPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
        
        // Additional validation on blur
        phoneInput.addEventListener('blur', function(e) {
            if (this.value.length > 0 && this.value.length !== 11) {
                this.setCustomValidity('Phone number must be exactly 11 digits');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Handle add/edit customer form submission
    const addCustomerForm = document.getElementById('addCustomerForm');
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate phone number one more time before submission
            const phone = document.getElementById('customerPhone').value;
            if (phone.length !== 11) {
                alert('Phone number must be exactly 11 digits');
                return;
            }
            
            if (!/^[0-9]{11}$/.test(phone)) {
                alert('Phone number must contain only numbers');
                return;
            }
            
            const formData = new FormData(this);
            const customerData = Object.fromEntries(formData);
            const customerId = this.getAttribute('data-customer-id');
            
            // Determine if we're adding or editing
            const isEditing = customerId !== null;
            const url = isEditing 
                ? `backend/admin/customers/update_customer.php?id=${customerId}`
                : 'backend/admin/customers/add_customer.php';
            
            console.log(isEditing ? 'Updating customer:' : 'Adding customer:', customerData);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(customerData)
            })
            .then(response => response.json())
            .then(data => {
                console.log(isEditing ? 'Customer updated:' : 'Customer added:', data);
                
                if (data.success) {
                    // Show success message
                    showSuccessModal(data.message || (isEditing ? 'Customer updated successfully!' : 'Customer added successfully!'));
                    
                    // Close modal and reset form
                    closeModal('addCustomerModal');
                    addCustomerForm.reset();
                    addCustomerForm.removeAttribute('data-customer-id');
                    
                    // Reload customers list
                    loadCustomers();
                } else {
                    // Show error message from server
                    alert(data.message || `Error ${isEditing ? 'updating' : 'adding'} customer. Please try again.`);
                }
            })
            .catch(error => {
                console.error(`Error ${isEditing ? 'updating' : 'adding'} customer:`, error);
                alert(`Error ${isEditing ? 'updating' : 'adding'} customer. Please check your connection and try again.`);
            });
        });
    }
});

function viewCustomer(id) {
    console.log('View customer:', id);
    // Fetch customer details and show in a modal
    fetch(`backend/admin/customers/get_customer.php?id=${id}`)
        .then(response => response.json())
        .then(customer => {
            if (customer && !customer.error) {
                // Populate modal with customer data
                document.getElementById('viewCustomerId').textContent = customer.id || '-';
                document.getElementById('viewCustomerName').textContent = customer.name || '-';
                document.getElementById('viewCustomerPhone').textContent = customer.phone || '-';
                document.getElementById('viewCustomerEmail').textContent = customer.email || 'N/A';
                document.getElementById('viewCustomerAddress').textContent = customer.address || '-';
                
                // Format status with badge
                const statusElement = document.getElementById('viewCustomerStatus');
                const statusText = customer.status === 'active' ? 'Active' : 'Inactive';
                statusElement.innerHTML = `<span class="status-badge ${customer.status === 'active' ? 'active' : 'inactive'}">${statusText}</span>`;
                
                // Format created date
                const createdDate = customer.created_at ? new Date(customer.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : '-';
                document.getElementById('viewCustomerCreated').textContent = createdDate;
                
                // Show modal
                document.getElementById('viewCustomerModal').classList.add('show');
            } else {
                alert('Error: ' + (customer.message || 'Customer not found'));
            }
        })
        .catch(error => {
            console.error('Error fetching customer:', error);
            alert('Error loading customer details');
        });
}

function editCustomer(id) {
    console.log('Edit customer:', id);
    // Fetch customer details for editing
    fetch(`backend/admin/customers/get_customer.php?id=${id}`)
        .then(response => response.json())
        .then(customer => {
            if (customer) {
                // Populate form with customer data
                document.getElementById('customerName').value = customer.name;
                document.getElementById('customerPhone').value = customer.phone;
                document.getElementById('customerEmail').value = customer.email || '';
                document.getElementById('customerAddress').value = customer.address;
                
                // Show modal
                openAddCustomerModal();
                
                // Change modal title and button
                document.querySelector('#addCustomerModal .modal-header h3').textContent = 'Edit Customer';
                document.querySelector('#addCustomerForm button[type="submit"]').textContent = 'Update Customer';
                
                // Store customer ID for update
                document.getElementById('addCustomerForm').setAttribute('data-customer-id', id);
            }
        })
        .catch(error => {
            console.error('Error fetching customer:', error);
            alert('Error loading customer for editing');
        });
}

function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageElement = document.getElementById('successMessage');
    if (modal && messageElement) {
        messageElement.textContent = message;
        modal.classList.add('show');
    }
}

function deleteCustomer(id) {
    // First, fetch customer details to show name in modal
    fetch(`backend/admin/customers/get_customer.php?id=${id}`)
        .then(response => response.json())
        .then(customer => {
            if (customer && !customer.error) {
                // Show customer name in modal
                document.getElementById('deleteCustomerName').textContent = customer.name || 'Customer #' + id;
                
                // Show modal
                document.getElementById('deleteCustomerModal').classList.add('show');
                
                // Set up confirm button click handler
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                confirmBtn.onclick = function() {
                    // Close modal first
                    closeModal('deleteCustomerModal');
                    
                    // Proceed with delete
                    fetch(`backend/admin/customers/delete_customer.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessModal(data.message || 'Customer deleted successfully!');
                            loadCustomers();
                        } else {
                            alert(data.message || 'Error deleting customer. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting customer:', error);
                        alert('Error deleting customer. Please try again.');
                    });
                };
            } else {
                alert('Error: Customer not found');
            }
        })
        .catch(error => {
            console.error('Error fetching customer:', error);
            alert('Error loading customer details');
        });
}
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
