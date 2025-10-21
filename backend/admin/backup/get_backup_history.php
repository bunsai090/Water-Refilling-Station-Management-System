<?php
// Get backup history
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    $backupDir = '../../../backups';
    
    // Check if directory exists
    if (!file_exists($backupDir)) {
        echo json_encode([]);
        exit;
    }
    
    // Get all SQL files
    $files = glob($backupDir . '/*.sql');
    $backups = [];
    
    foreach ($files as $file) {
        $backups[] = [
            'filename' => basename($file),
            'date' => date('F d, Y g:i A', filemtime($file)),
            'size' => formatBytes(filesize($file)),
            'timestamp' => filemtime($file)
        ];
    }
    
    // Sort by timestamp descending (newest first)
    usort($backups, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    echo json_encode($backups);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
