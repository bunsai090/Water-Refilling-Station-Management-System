<?php
// Restore database from backup
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Check if file was uploaded
    if (!isset($_FILES['backup_file'])) {
        throw new Exception('No backup file uploaded');
    }
    
    $file = $_FILES['backup_file'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error');
    }
    
    if ($file['size'] === 0) {
        throw new Exception('Uploaded file is empty');
    }
    
    // Check file extension
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if ($ext !== 'sql') {
        throw new Exception('Invalid file type. Only .sql files are allowed');
    }
    
    // Read SQL file
    $sql = file_get_contents($file['tmp_name']);
    
    if ($sql === false) {
        throw new Exception('Failed to read backup file');
    }
    
    // Get database credentials
    $host = 'localhost';
    $dbname = 'water_refilling_db';
    $username = 'root';
    $password = '';
    
    // Execute SQL commands
    try {
        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        
        // Split SQL into individual statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($stmt) {
                return !empty($stmt);
            }
        );
        
        // Execute each statement
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        
        echo json_encode([
            'success' => true,
            'message' => 'Database restored successfully'
        ]);
        
    } catch (PDOException $e) {
        // Re-enable foreign key checks even on error
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        throw new Exception('Database restore failed: ' . $e->getMessage());
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
