<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "My Profile";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';

// Get admin details
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        <div class="dashboard-content">
            <div class="card" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header">
                    <h3>Profile Information</h3>
                </div>
                <div style="padding: 30px;">
                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullName">Full Name *</label>
                                <input type="text" id="fullName" name="full_name" value="<?php echo htmlspecialchars($admin['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username *</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Account Created</label>
                            <input type="text" value="<?php echo date('F d, Y', strtotime($admin['created_at'])); ?>" disabled>
                        </div>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                            <h4 style="margin-bottom: 15px;">Change Password</h4>
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" name="current_password" placeholder="Leave blank to keep current password">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" name="new_password" placeholder="Minimum 6 characters">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" id="confirmPassword" name="confirm_password" placeholder="Re-enter new password">
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Validate password if changing
    if (data.new_password) {
        if (data.new_password.length < 6) {
            alert('New password must be at least 6 characters');
            return;
        }
        if (data.new_password !== data.confirm_password) {
            alert('New passwords do not match');
            return;
        }
        if (!data.current_password) {
            alert('Please enter your current password to change password');
            return;
        }
    }
    
    // Send to backend
    fetch('backend/admin/profile/update_profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Profile updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating profile. Please try again.');
    });
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
