<?php
// Helper functions

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_order_id() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function format_currency($amount) {
    return '₱' . number_format($amount, 2);
}

function log_activity($user_id, $action, $details = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $action, $details]);
    } catch(PDOException $e) {
        error_log("Activity log error: " . $e->getMessage());
    }
}
?>
