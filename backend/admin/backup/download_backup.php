<?php
// Download backup file
require_once '../../config/auth_check.php';

try {
    if (!isset($_GET['file'])) {
        throw new Exception('No file specified');
    }
    
    $filename = basename($_GET['file']);
    $filepath = '../../../backups/' . $filename;
    
    // Check if file exists
    if (!file_exists($filepath)) {
        throw new Exception('Backup file not found');
    }
    
    // Send headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Pragma: public');
    header('Cache-Control: must-revalidate');
    
    // Clear output buffer
    ob_clean();
    flush();
    
    // Read and output file
    readfile($filepath);
    exit;
    
} catch (Exception $e) {
    header('Content-Type: text/html');
    echo '<h1>Error</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="../../../backup.php">Go back</a></p>';
}
?>
