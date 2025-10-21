<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Payment Management";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="page-actions">
            <h2>Payment Management</h2>
            <div>
                <button class="btn btn-primary" onclick="openRecordPaymentModal()">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#payments"></use></svg>
                    Record Payment
                </button>
                <button class="btn btn-secondary" onclick="exportPayments()">
                    <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#reports"></use></svg>
                    Export Payments
                </button>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="dashboard-stats">
                <div class="stat-card success">
                    <h3>Today's Payments</h3>
                    <div class="stat-number" id="todayPayments">₱0.00</div>
                    <p>Payments received today</p>
                </div>
                <div class="stat-card primary">
                    <h3>This Month</h3>
                    <div class="stat-number" id="monthPayments">₱0.00</div>
                    <p>Monthly payment total</p>
                </div>
                <div class="stat-card warning">
                    <h3>Pending Verification</h3>
                    <div class="stat-number" id="pendingPayments">0</div>
                    <p>Payments awaiting verification</p>
                </div>
            </div>
        
        <div class="card">
                <div class="card-header">
                    <h3>Payment Records</h3>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <select id="paymentStatusFilter" onchange="filterPayments()" class="form-control" style="min-width: 150px;">
                            <option value="">All Status</option>
                            <option value="verified">Verified</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <input type="date" id="paymentDateFilter" onchange="filterPayments()" class="form-control">
                        <div class="search-box">
                            <input type="text" id="paymentSearch" placeholder="Search payments..." onkeyup="searchPayments()">
                        </div>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="data-table" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center loading">
                                    <div class="spinner"></div>
                                    Loading payments...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Record Payment Modal -->
<div id="recordPaymentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Record Payment</h3>
            <button class="modal-close" onclick="closeModal('recordPaymentModal')">&times;</button>
        </div>
        <form id="recordPaymentForm">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="paymentOrder">Order *</label>
                        <select id="paymentOrder" name="order_id" required>
                            <option value="">Select Order</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentAmount">Amount *</label>
                        <input type="number" id="paymentAmount" name="amount" step="0.01" min="0" required placeholder="0.00">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method *</label>
                        <select id="paymentMethod" name="payment_method" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentReference">Reference Number</label>
                        <input type="text" id="paymentReference" name="reference_number" placeholder="Enter reference number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="paymentProof">Payment Proof (Optional)</label>
                    <input type="file" id="paymentProof" name="payment_proof" accept="image/*">
                    <small class="form-text">Upload receipt or proof of payment</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('recordPaymentModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>
</div>

<!-- Payment Verification Modal -->
<div id="verifyPaymentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Verify Payment</h3>
            <span class="modal-close">&times;</span>
        </div>
        <div id="paymentDetails">
            <!-- Payment details will be loaded here -->
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-danger" onclick="rejectPayment()">Reject</button>
            <button type="button" class="btn btn-success" onclick="verifyPayment()">Verify</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('verifyPaymentModal')">Cancel</button>
        </div>
    </div>
</div>

<script>
function openRecordPaymentModal() {
    document.getElementById('recordPaymentModal').classList.add('show');
    loadPendingOrders();
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

function loadPendingOrders() {
    fetch('backend/admin/orders/get_orders.php?status=pending')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('paymentOrder');
            select.innerHTML = '<option value="">Select Order</option>';
            data.forEach(order => {
                select.innerHTML += `<option value="${order.id}">${order.order_id} - ${order.customer_name}</option>`;
            });
        });
}

function filterPayments() {
    const status = document.getElementById('paymentStatusFilter').value;
    const date = document.getElementById('paymentDateFilter').value;
    // Implement payment filtering
}

function exportPayments() {
    window.open('backend/admin/reports/export_payments.php', '_blank');
}

function verifyPayment() {
    // Implement payment verification
}

function rejectPayment() {
    // Implement payment rejection
}

function loadPayments() {
    fetch('backend/admin/payments/get_payments.php')
        .then(response => response.json())
        .then(data => {
            console.log('Payments loaded:', data);
            updatePaymentSummary(data);
        })
        .catch(error => {
            console.error('Error loading payments:', error);
        });
}

function updatePaymentSummary(data) {
    // Calculate and update payment summary
    const today = new Date().toDateString();
    const thisMonth = new Date().getMonth();
    
    let todayTotal = 0;
    let monthTotal = 0;
    let pendingCount = 0;
    
    data.forEach(payment => {
        const paymentDate = new Date(payment.created_at);
        
        if (paymentDate.toDateString() === today && payment.status === 'verified') {
            todayTotal += parseFloat(payment.amount);
        }
        
        if (paymentDate.getMonth() === thisMonth && payment.status === 'verified') {
            monthTotal += parseFloat(payment.amount);
        }
        
        if (payment.status === 'pending') {
            pendingCount++;
        }
    });
    
    document.getElementById('todayPayments').textContent = '₱' + todayTotal.toFixed(2);
    document.getElementById('monthPayments').textContent = '₱' + monthTotal.toFixed(2);
    document.getElementById('pendingPayments').textContent = pendingCount;
}

document.addEventListener('DOMContentLoaded', function() {
    loadPayments();
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
