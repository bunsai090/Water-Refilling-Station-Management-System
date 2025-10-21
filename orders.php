<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Manage Orders";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="page-actions">
            <h2>Order Management</h2>
            <div>
                <button class="btn btn-primary" onclick="openCreateOrderModal()">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#orders"></use></svg>
                    Create New Order
                </button>
                <button class="btn btn-secondary" onclick="refreshOrders()">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#dashboard"></use></svg>
                    Refresh
                </button>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="card">
                <div class="card-header">
                    <h3>Orders List</h3>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <select id="statusFilter" onchange="filterOrders()" class="form-control" style="min-width: 150px; padding: 10px; border: 1px solid #dee2e6; border-radius: 8px; background: white;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <div class="search-box">
                            <input type="text" id="orderSearch" placeholder="Search orders..." onkeyup="searchOrders()">
                        </div>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="data-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center loading">
                                    <div class="spinner"></div>
                                    Loading orders...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<div id="createOrderModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3>Create New Order</h3>
            <button class="modal-close" onclick="closeModal('createOrderModal')">&times;</button>
        </div>
        <form id="createOrderForm">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="orderCustomer">Customer *</label>
                        <select id="orderCustomer" name="customer_id" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orderDate">Order Date</label>
                        <input type="date" id="orderDate" name="order_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Order Items *</label>
                    <div id="orderItems" class="order-items-container">
                        <div class="order-item-row">
                            <div class="item-fields">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select name="items[0][product_id]" class="product-select" required onchange="updateItemPrice(this)">
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" required class="quantity-input" onchange="updateItemTotal(this)">
                                </div>
                                <div class="form-group">
                                    <label>Unit Price</label>
                                    <input type="number" name="items[0][unit_price]" placeholder="0.00" step="0.01" readonly class="price-input">
                                </div>
                                <div class="form-group">
                                    <label>Total</label>
                                    <input type="number" name="items[0][total_price]" placeholder="0.00" step="0.01" readonly class="total-input">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-delete remove-item-btn" onclick="removeOrderItem(this)" style="margin-top: 25px;">×</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addOrderItem()" style="margin-top: 10px;">
                        <svg class="icon" style="width: 14px; height: 14px; margin-right: 5px;"><use href="frontend/assets/svg/icons.svg#orders"></use></svg>
                        Add Another Item
                    </button>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryAddress">Delivery Address</label>
                        <textarea id="deliveryAddress" name="delivery_address" rows="2" placeholder="Enter delivery address..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="orderNotes">Notes</label>
                        <textarea id="orderNotes" name="notes" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                
                <div class="order-summary">
                    <div class="summary-row">
                        <span class="summary-label">Total Amount:</span>
                        <span class="summary-value" id="orderTotalAmount">₱0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createOrderModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Order</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateOrderModal() {
    document.getElementById('createOrderModal').classList.add('show');
    loadCustomersForOrder();
    loadProductsForOrder();
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

function loadCustomersForOrder() {
    // Load customers for order creation
    fetch('backend/admin/customers/get_customers.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('orderCustomer');
            select.innerHTML = '<option value="">Select Customer</option>';
            if (data && data.length > 0) {
                data.forEach(customer => {
                    select.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading customers:', error);
        });
}

function loadProductsForOrder() {
    // Load products for order creation
    fetch('backend/admin/products/get_products.php')
        .then(response => response.json())
        .then(data => {
            window.productsData = data || [];
            updateProductSelects();
        })
        .catch(error => {
            console.error('Error loading products:', error);
            window.productsData = [];
        });
}

function updateProductSelects() {
    const selects = document.querySelectorAll('.product-select');
    selects.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Select Product</option>';
        if (window.productsData && window.productsData.length > 0) {
            window.productsData.forEach(product => {
                select.innerHTML += `<option value="${product.id}" data-price="${product.unit_price}">${product.name} - ₱${product.unit_price}</option>`;
            });
        }
        select.value = currentValue;
    });
}

function addOrderItem() {
    const container = document.getElementById('orderItems');
    const itemCount = container.children.length;
    const newItem = document.createElement('div');
    newItem.className = 'order-item-row';
    newItem.innerHTML = `
        <div class="item-fields">
            <div class="form-group">
                <label>Product</label>
                <select name="items[${itemCount}][product_id]" class="product-select" required onchange="updateItemPrice(this)">
                    <option value="">Select Product</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" placeholder="Qty" min="1" required class="quantity-input" onchange="updateItemTotal(this)">
            </div>
            <div class="form-group">
                <label>Unit Price</label>
                <input type="number" name="items[${itemCount}][unit_price]" placeholder="0.00" step="0.01" readonly class="price-input">
            </div>
            <div class="form-group">
                <label>Total</label>
                <input type="number" name="items[${itemCount}][total_price]" placeholder="0.00" step="0.01" readonly class="total-input">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-delete remove-item-btn" onclick="removeOrderItem(this)" style="margin-top: 25px;">×</button>`;
    
    container.appendChild(newItem);
    updateProductSelects();
}

function removeOrderItem(button) {
    const container = document.getElementById('orderItems');
    if (container.children.length > 1) {
        button.parentElement.remove();
        updateOrderTotal();
    }
}

function updateItemPrice(selectElement) {
    const row = selectElement.closest('.order-item-row');
    const priceInput = row.querySelector('.price-input');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = selectedOption.getAttribute('data-price') || 0;
    
    priceInput.value = parseFloat(price).toFixed(2);
    updateItemTotal(row.querySelector('.quantity-input'));
}

function updateItemTotal(quantityInput) {
    const row = quantityInput.closest('.order-item-row');
    const priceInput = row.querySelector('.price-input');
    const totalInput = row.querySelector('.total-input');
    
    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const total = quantity * price;
    
    totalInput.value = total.toFixed(2);
    updateOrderTotal();
}

function updateOrderTotal() {
    const totalInputs = document.querySelectorAll('.total-input');
    let grandTotal = 0;
    
    totalInputs.forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    document.getElementById('orderTotalAmount').textContent = `₱${grandTotal.toFixed(2)}`;
}

function loadOrders() {
    const tbody = document.querySelector('#ordersTable tbody');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center loading">
                <div class="spinner"></div>
                Loading orders...
            </td>
        </tr>
    `;
    
    // Get filter values
    const status = document.getElementById('statusFilter')?.value || '';
    const search = document.getElementById('orderSearch')?.value || '';
    
    let url = 'backend/admin/orders/get_orders.php';
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    fetch(url)
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
                        <td colspan="6" class="text-center" style="color: #dc3545; padding: 40px;">
                            ${data.message}
                        </td>
                    </tr>
                `;
                return;
            }
            
            console.log('Orders loaded:', data);
            displayOrders(data);
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center" style="color: #dc3545; padding: 40px;">
                        Error loading orders. Please try again.
                    </td>
                </tr>
            `;
        });
}

function displayOrders(orders) {
    const tbody = document.querySelector('#ordersTable tbody');
    
    if (orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">No orders found.</td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td>${order.order_id}</td>
            <td>${order.customer_name}</td>
            <td>${order.items || 'N/A'}</td>
            <td>₱${parseFloat(order.total_amount).toFixed(2)}</td>
            <td>
                <span class="status-badge ${getOrderStatusClass(order.status)}">
                    ${formatOrderStatus(order.status)}
                </span>
            </td>
            <td>${formatDate(order.created_at)}</td>
            <td>
                <button class="btn-action btn-view" onclick="viewOrder(${order.id})" title="View">
                    <svg class="icon"><use href="frontend/assets/svg/icons.svg#view"></use></svg>
                </button>
                <button class="btn-action btn-edit" onclick="editOrder(${order.id})" title="Edit">
                    <svg class="icon"><use href="frontend/assets/svg/icons.svg#edit"></use></svg>
                </button>
                ${order.status === 'pending' ? `
                    <button class="btn-action btn-delete" onclick="cancelOrder(${order.id})" title="Cancel">
                        <svg class="icon"><use href="frontend/assets/svg/icons.svg#close"></use></svg>
                    </button>
                ` : ''}
            </td>
        </tr>
    `).join('');
}

function getOrderStatusClass(status) {
    const statusMap = {
        'pending': 'warning',
        'processing': 'info',
        'delivered': 'success',
        'cancelled': 'danger'
    };
    return statusMap[status] || '';
}

function formatOrderStatus(status) {
    const formatMap = {
        'pending': 'Pending',
        'processing': 'Processing',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled'
    };
    return formatMap[status] || status;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function filterOrders() {
    loadOrders();
}

function searchOrders() {
    loadOrders();
}

function refreshOrders() {
    loadOrders();
}

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
