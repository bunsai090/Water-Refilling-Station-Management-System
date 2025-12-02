<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'backend/config/db.php';

echo "Database connection successful.<br>";

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found in the database.<br>";
    } else {
        echo "Tables found:<br>";
        foreach ($tables as $table) {
            echo "- " . $table . "<br>";
        }
        
        if (in_array('admins', $tables)) {
            echo "Table 'admins' exists.<br>";
            $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
            $count = $stmt->fetchColumn();
            echo "Number of admins: " . $count . "<br>";
        } else {
            echo "Table 'admins' is MISSING.<br>";
        }
    }
} catch (PDOException $e) {
    echo "Error querying database: " . $e->getMessage();
}
?>
