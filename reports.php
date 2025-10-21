<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Reports & Analytics";
$additional_css = ['frontend/assets/css/dashboard.css'];
$additional_js = ['frontend/assets/js/charts.js'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="reports-controls">
            <div class="date-range">
                <label>Date Range:</label>
                <input type="date" id="startDate" onchange="updateReports()">
                <input type="date" id="endDate" onchange="updateReports()">
            </div>
            <div class="report-actions">
                <button class="btn btn-primary" onclick="generateSalesReport()">Sales Report</button>
                <button class="btn btn-secondary" onclick="generateInventoryReport()">Inventory Report</button>
                <button class="btn btn-info" onclick="generateCustomerReport()">Customer Report</button>
            </div>
        </div>
        
        <div class="reports-grid">
            <div class="report-card">
                <div class="card">
                    <h3>Sales Overview</h3>
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="report-card">
                <div class="card">
                    <h3>Revenue Trends</h3>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="report-card">
                <div class="card">
                    <h3>Top Products</h3>
                    <canvas id="productsChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="report-card">
                <div class="card">
                    <h3>Customer Analytics</h3>
                    <canvas id="customersChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="report-tables">
            <div class="card">
                <div class="card-header">
                    <h3>Detailed Sales Report</h3>
                    <button class="btn btn-sm btn-secondary" onclick="exportSalesData()">Export CSV</button>
                </div>
                <div class="table-container">
                    <table class="data-table" id="salesReportTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">Loading sales data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let salesChart, revenueChart, productsChart, customersChart;

function initializeCharts() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Daily Sales',
                data: [],
                borderColor: '#007bff',
                backgroundColor: '#007bff20',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Sales Trend'
                }
            }
        }
    });
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue (₱)',
                data: [],
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Revenue'
                }
            }
        }
    });
    
    // Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    productsChart = new Chart(productsCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Top Selling Products'
                }
            }
        }
    });
    
    // Customers Chart
    const customersCtx = document.getElementById('customersChart').getContext('2d');
    customersChart = new Chart(customersCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'New Customers',
                data: [],
                backgroundColor: '#6f42c1'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Customer Growth'
                }
            }
        }
    });
}

function updateReports() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        loadReportData(startDate, endDate);
    }
}

function loadReportData(startDate, endDate) {
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate
    });
    
    fetch(`backend/admin/reports/get_report_data.php?${params}`)
        .then(response => response.json())
        .then(data => {
            updateCharts(data);
            updateSalesTable(data.sales);
        })
        .catch(error => {
            console.error('Error loading report data:', error);
        });
}

function updateCharts(data) {
    // Update sales chart
    salesChart.data.labels = data.sales_trend.labels;
    salesChart.data.datasets[0].data = data.sales_trend.values;
    salesChart.update();
    
    // Update revenue chart
    revenueChart.data.labels = data.revenue_trend.labels;
    revenueChart.data.datasets[0].data = data.revenue_trend.values;
    revenueChart.update();
    
    // Update products chart
    productsChart.data.labels = data.top_products.labels;
    productsChart.data.datasets[0].data = data.top_products.values;
    productsChart.update();
    
    // Update customers chart
    customersChart.data.labels = data.customer_growth.labels;
    customersChart.data.datasets[0].data = data.customer_growth.values;
    customersChart.update();
}

function updateSalesTable(salesData) {
    const tbody = document.querySelector('#salesReportTable tbody');
    tbody.innerHTML = '';
    
    salesData.forEach(sale => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${sale.date}</td>
            <td>${sale.order_id}</td>
            <td>${sale.customer}</td>
            <td>${sale.products}</td>
            <td>${sale.quantity}</td>
            <td>₱${sale.amount}</td>
            <td><span class="status ${sale.status}">${sale.status}</span></td>
        `;
    });
}

function generateSalesReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate
    });
    
    window.open(`backend/admin/reports/generate_sales_report.php?${params}`, '_blank');
}

function generateInventoryReport() {
    window.open('backend/admin/reports/generate_inventory_report.php', '_blank');
}

function generateCustomerReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate
    });
    
    window.open(`backend/admin/reports/generate_customer_report.php?${params}`, '_blank');
}

function exportSalesData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        format: 'csv'
    });
    
    window.open(`backend/admin/reports/export_sales.php?${params}`, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (last 30 days)
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
    document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
    
    initializeCharts();
    updateReports();
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
