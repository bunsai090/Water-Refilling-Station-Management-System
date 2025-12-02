<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'backend/config/db.php';

echo "Connected to database: " . $dbname . "<br>";

function executeSqlFile($pdo, $filename) {
    if (!file_exists($filename)) {
        echo "File not found: $filename<br>";
        return;
    }

    echo "Importing $filename...<br>";
    
    $sql = file_get_contents($filename);
    
    // Remove CREATE DATABASE and USE statements to avoid database name mismatch
    $lines = explode("\n", $sql);
    $cleanSql = "";
    foreach ($lines as $line) {
        $trimLine = trim($line);
        if (stripos($trimLine, 'CREATE DATABASE') === 0 || stripos($trimLine, 'USE') === 0) {
            continue;
        }
        $cleanSql .= $line . "\n";
    }
    
    try {
        $pdo->exec($cleanSql);
        echo "Successfully imported $filename<br>";
    } catch (PDOException $e) {
        echo "Error importing $filename: " . $e->getMessage() . "<br>";
    }
}

// Import schema
executeSqlFile($pdo, 'water_refilling_db.sql');

// Import sample data
executeSqlFile($pdo, 'sample_data.sql');

echo "Database import completed.<br>";
?>
