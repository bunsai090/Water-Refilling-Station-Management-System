<?php
// Delete backup file
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!isset($data['filename'])) {
        throw new Exception('No filename specified');
    }
    
    $filename = basename($data['filename']);
    $filepath = '../../../backups/' . $filename;
    
    // Check if file exists
    if (!file_exists($filepath)) {
        throw new Exception('Backup file not found');
    }
    
    // Delete file
    if (unlink($filepath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Backup deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete backup file');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
