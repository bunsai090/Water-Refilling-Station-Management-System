// Dashboard specific JavaScript

let salesChart = null;
let inventoryChart = null;

document.addEventListener('DOMContentLoaded', function () {
    console.log('Dashboard loading...');
    console.log('Chart.js available:', typeof Chart !== 'undefined');

    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded! Charts will not work.');
    }

    loadDashboardStats();
    loadStockLevels();
    setupRealTimeUpdates();

    // Add event listener for sales period filter
    const periodFilter = document.getElementById('salesPeriodFilter');
    if (periodFilter) {
        periodFilter.addEventListener('change', function () {
            const period = this.value;
            console.log('Period filter changed to:', period);
            loadDashboardStats(period);
        });
    }
});

function loadDashboardStats(period = 7) {
    console.log('Fetching dashboard stats from API with period:', period);

    fetch(`backend/admin/dashboard/get_stats.php?period=${period}`)
        .then(response => {
            console.log('API Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dashboard data received:', data);

            if (data.error) {
                console.error('Error from server:', data.message);
                document.getElementById('recent-activities').innerHTML =
                    '<p style="color: red; padding: 20px;">Error: ' + data.message + '</p>';
                return;
            }

            updateStatCards(data.stats);
            updateSalesChart(data.sales_data);
            updateInventoryChart(data.inventory_data);
            updateRecentActivities(data.recent_activities);
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
            document.getElementById('recent-activities').innerHTML =
                '<p style="color: red; padding: 20px;">Failed to load dashboard data. Please refresh the page.</p>';
        });
}

function updateStatCards(stats) {
    document.getElementById('total-customers').textContent = stats.total_customers || 0;
    document.getElementById('pending-orders').textContent = stats.pending_orders || 0;
    document.getElementById('total-revenue').textContent = '₱' + parseFloat(stats.total_revenue || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('low-stock-items').textContent = stats.low_stock_items || 0;
}

function updateSalesChart(salesData) {
    console.log('Updating sales chart with data:', salesData);
    const ctx = document.getElementById('salesChart');
    if (!ctx) {
        console.error('Sales chart canvas not found!');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js not available!');
        return;
    }

    // Extract labels and values - using period_label for flexibility
    const labels = salesData && salesData.length > 0 ? salesData.map(item => item.period_label) : ['No Data'];
    const values = salesData && salesData.length > 0 ? salesData.map(item => parseFloat(item.total)) : [0];

    console.log('Chart labels:', labels);
    console.log('Chart values:', values);

    // Destroy existing chart if it exists
    if (salesChart) {
        salesChart.destroy();
    }

    // Create new bar chart
    try {
        salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales (₱)',
                    data: values,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: '#007bff',
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(0, 123, 255, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Sales: ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        console.log('Sales chart created successfully');
    } catch (error) {
        console.error('Error creating sales chart:', error);
    }
}

function updateInventoryChart(inventoryData) {
    console.log('Updating inventory chart with data:', inventoryData);
    const ctx = document.getElementById('inventoryChart');
    if (!ctx) {
        console.error('Inventory chart canvas not found!');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js not available!');
        return;
    }

    // Extract labels and values
    const labels = inventoryData && inventoryData.length > 0 ? inventoryData.map(item => item.status) : ['No Data'];
    const values = inventoryData && inventoryData.length > 0 ? inventoryData.map(item => parseInt(item.count)) : [1];

    const colors = {
        'In Stock': '#28a745',
        'Low Stock': '#ffc107',
        'Out of Stock': '#dc3545'
    };

    const backgroundColors = labels.map(label => colors[label] || '#6c757d');

    console.log('Inventory labels:', labels);
    console.log('Inventory values:', values);
    console.log('Inventory colors:', backgroundColors);

    // Destroy existing chart if it exists
    if (inventoryChart) {
        inventoryChart.destroy();
    }

    // Create new chart
    try {
        inventoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors.length > 0 ? backgroundColors : ['#e9ecef'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + context.parsed + ' items';
                            }
                        }
                    }
                }
            }
        });
        console.log('Inventory chart created successfully');
    } catch (error) {
        console.error('Error creating inventory chart:', error);
    }
}

function updateRecentActivities(activities) {
    console.log('Updating recent activities with data:', activities);
    const container = document.getElementById('recent-activities');
    if (!container) {
        console.error('Recent activities container not found!');
        return;
    }

    if (!activities || activities.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #6c757d; padding: 20px;">No recent activities</p>';
        console.log('No activities to display');
        return;
    }

    try {
        container.innerHTML = activities.map(activity => `
            <div class="activity-item" style="padding: 12px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>New Order: ${activity.reference || 'N/A'}</strong>
                    <p style="margin: 4px 0 0 0; color: #6c757d; font-size: 14px;">
                        Customer: ${activity.customer_name || 'Unknown'}
                    </p>
                </div>
                <div style="text-align: right;">
                    <strong style="color: #28a745;">₱${parseFloat(activity.total_amount || 0).toLocaleString()}</strong>
                    <p style="margin: 4px 0 0 0; color: #6c757d; font-size: 12px;">
                        ${formatDateTime(activity.created_at)}
                    </p>
                </div>
            </div>
        `).join('');
        console.log('Recent activities updated successfully');
    } catch (error) {
        console.error('Error updating recent activities:', error);
        container.innerHTML = '<p style="color: red; padding: 20px;">Error displaying activities</p>';
    }
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} min ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;

    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function loadStockLevels() {
    console.log('Loading stock levels...');
    const tbody = document.querySelector('#stockLevelsTable tbody');

    fetch('backend/admin/inventory/get_inventory.php')
        .then(response => response.json())
        .then(data => {
            console.log('Stock data received:', data);

            if (data.error) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center" style="color: #dc3545; padding: 20px;">
                            ${data.message}
                        </td>
                    </tr>
                `;
                return;
            }

            const inventory = data.inventory || [];

            if (inventory.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px;">
                            No products found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = inventory.map(item => {
                let statusClass = '';
                let statusText = '';

                if (item.stock_status === 'out_of_stock') {
                    statusClass = 'danger';
                    statusText = 'Out of Stock';
                } else if (item.stock_status === 'low_stock') {
                    statusClass = 'warning';
                    statusText = 'Low Stock';
                } else {
                    statusClass = 'success';
                    statusText = 'In Stock';
                }

                return `
                    <tr>
                        <td>${item.product_name}</td>
                        <td><strong>${item.current_stock}</strong></td>
                        <td>${item.minimum_stock}</td>
                        <td>
                            <span class="status-badge ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');

            console.log('Stock levels table updated successfully');
        })
        .catch(error => {
            console.error('Error loading stock levels:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center" style="color: #dc3545; padding: 20px;">
                        Error loading stock levels. Please refresh.
                    </td>
                </tr>
            `;
        });
}

function setupRealTimeUpdates() {
    // Update dashboard every 30 seconds
    setInterval(loadDashboardStats, 30000);
}
