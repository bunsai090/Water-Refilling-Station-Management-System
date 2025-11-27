<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$page_title = "Admin Login";
$additional_css = [
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
    'frontend/assets/css/login.css'
];
$additional_js = [
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
    'frontend/assets/js/auth.js'
];
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

<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <!-- Logo and Title -->
                        <div class="text-center mb-4">
                            <img src="frontend/assets/images/logo.svg" alt="Logo" width="80" height="80" class="mb-3">
                            <h2 class="fw-bold text-primary mb-2">Admin Login</h2>
                            <p class="text-muted">Water Refilling Station Management</p>
                        </div>
                        
                        <!-- Error Alert -->
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Login Form -->
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="bi bi-person-fill me-1"></i> Username
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Enter your username"
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                       required>
                                <div class="invalid-feedback">Please enter your username.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1"></i> Password
                                </label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <button type="button" 
                                            class="btn password-toggle-btn" 
                                            onclick="togglePassword()"
                                            aria-label="Toggle password visibility">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>
                            </div>
                        </form>
                        
                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <a href="index.php" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="text-center mt-4 text-white-50">
                    <small>&copy; <?php echo date('Y'); ?> Water Refilling Station. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
