<?php
// Session authentication check
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_logged_in'])) {
    header('Location: ../../login.php');
    exit();
}

// Optional: Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: ../../login.php');
    exit();
}

$_SESSION['last_activity'] = time();
?>
