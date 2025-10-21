<?php
// Create database backup
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get database credentials from config
    $host = 'localhost';
    $dbname = 'water_refilling_db';
    $username = 'root';
    $password = '';
    
    // Create backups directory if it doesn't exist
    $backupDir = '../../../backups';
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    // Generate filename with timestamp
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $filepath = $backupDir . '/' . $filename;
    
    // Create backup using mysqldump
    $command = "mysqldump --user={$username} --password={$password} --host={$host} {$dbname} > {$filepath}";
    
    // Execute command
    exec($command, $output, $result);
    
    if ($result === 0 && file_exists($filepath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Backup created successfully',
            'filename' => $filename,
            'size' => filesize($filepath)
        ]);
    } else {
        throw new Exception('Failed to create backup');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
