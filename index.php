<?php
$page_title = "Welcome";
$additional_css = [];
include 'frontend/assets/includes/header.php';
?>

<div class="landing-container">
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <img src="frontend/assets/images/logo.svg" alt="Water Station Logo" class="hero-logo">
                <h1>Water Refilling Station Management System</h1>
                <p>Streamline your water refilling business operations with our comprehensive management solution.</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary">Admin Login</a>
                    <a href="#features" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="features-section" id="features">
        <div class="container">
            <h2>Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#users"></use></svg>
                    <h3>Customer Management</h3>
                    <p>Manage customer information, track orders, and maintain customer relationships.</p>
                </div>
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#orders"></use></svg>
                    <h3>Order Processing</h3>
                    <p>Handle orders efficiently with delivery tracking and status updates.</p>
                </div>
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#inventory"></use></svg>
                    <h3>Inventory Control</h3>
                    <p>Track stock levels, manage supplies, and get low stock alerts.</p>
                </div>
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#payments"></use></svg>
                    <h3>Payment Processing</h3>
                    <p>Record payments, verify transactions, and generate receipts.</p>
                </div>
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#reports"></use></svg>
                    <h3>Reports & Analytics</h3>
                    <p>Generate detailed reports for sales, inventory, and customer analytics.</p>
                </div>
                <div class="feature-card">
                    <svg class="feature-icon"><use href="frontend/assets/svg/icons.svg#settings"></use></svg>
                    <h3>System Settings</h3>
                    <p>Configure pricing, taxes, and other system parameters.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'frontend/assets/includes/footer.php'; ?>
