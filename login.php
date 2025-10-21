<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$page_title = "Admin Login";
$additional_css = ['frontend/assets/css/login.css'];
$additional_js = ['frontend/assets/js/auth.js'];
include 'frontend/assets/includes/header.php';

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'backend/config/db.php';
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM admins WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = 'Invalid username or password.';
            }
        } catch(PDOException $e) {
            $error_message = 'Database error. Please try again.';
        }
    } else {
        $error_message = 'Please fill in all fields.';
    }
}
?>

<div class="login-container">
    <div class="login-form">
        <div class="login-header">
            <img src="frontend/assets/images/logo.svg" alt="Logo" width="60" height="60">
            <h2>Admin Login</h2>
        </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>
        
        <div class="login-footer">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</div>

<?php include 'frontend/assets/includes/footer.php'; ?>
