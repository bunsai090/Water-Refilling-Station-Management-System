// Authentication related JavaScript

function handleLogin(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch('backend/auth/login.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            showNotification('An error occurred during login', 'error');
        });
}

function handleLogout() {
    showLogoutModal();
}

function showLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.add('show');
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

function confirmLogout() {
    window.location.href = 'logout.php';
}

// Session timeout warning
let sessionTimeout;
let warningTimeout;

function resetSessionTimer() {
    clearTimeout(sessionTimeout);
    clearTimeout(warningTimeout);

    // Warning 5 minutes before timeout
    warningTimeout = setTimeout(() => {
        if (confirm('Your session will expire in 5 minutes. Do you want to continue?')) {
            resetSessionTimer();
        }
    }, 25 * 60 * 1000); // 25 minutes

    // Auto logout after 30 minutes
    sessionTimeout = setTimeout(() => {
        alert('Session expired. You will be redirected to login page.');
        window.location.href = 'logout.php';
    }, 30 * 60 * 1000); // 30 minutes
}

// Reset timer on user activity
document.addEventListener('click', resetSessionTimer);
document.addEventListener('keypress', resetSessionTimer);

// Close logout modal when clicking outside
window.addEventListener('click', function (event) {
    const modal = document.getElementById('logoutModal');
    if (event.target === modal) {
        closeLogoutModal();
    }
});

// Initialize session timer
resetSessionTimer();

// Bootstrap 5 Form Validation
(function () {
    'use strict';

    // Fetch all forms that need validation
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
