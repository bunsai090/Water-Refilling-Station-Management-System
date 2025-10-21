// Dashboard specific JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    initializeCharts();
    setupRealTimeUpdates();
});

function loadDashboardStats() {
    fetch('backend/admin/dashboard/get_stats.php')
        .then(response => response.json())
        .then(data => {
            updateStatCards(data);
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
        });
}

function updateStatCards(stats) {
    document.getElementById('total-customers').textContent = stats.total_customers || 0;
    document.getElementById('pending-orders').textContent = stats.pending_orders || 0;
    document.getElementById('total-revenue').textContent = '₱' + (stats.total_revenue || 0).toLocaleString();
    document.getElementById('low-stock-items').textContent = stats.low_stock_items || 0;
}

function initializeCharts() {
    // Sales chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    }
}

function setupRealTimeUpdates() {
    // Update dashboard every 30 seconds
    setInterval(loadDashboardStats, 30000);
}
