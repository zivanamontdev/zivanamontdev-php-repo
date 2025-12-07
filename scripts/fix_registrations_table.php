<?php
/**
 * Script to fix registrations table - make old columns nullable
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance();

echo "Updating registrations table structure...\n\n";

try {
    // Make email nullable
    $db->query("ALTER TABLE registrations MODIFY COLUMN email VARCHAR(100) NULL");
    echo "email column is now nullable.\n";
    
    // Make phone nullable
    $db->query("ALTER TABLE registrations MODIFY COLUMN phone VARCHAR(20) NULL");
    echo "phone column is now nullable.\n";
    
    // Make message nullable (should already be)
    $db->query("ALTER TABLE registrations MODIFY COLUMN message TEXT NULL");
    echo "message column is now nullable.\n";
    
    // Show updated structure
    echo "\nUpdated table structure:\n";
    echo str_repeat("-", 60) . "\n";
    
    $columns = $db->fetchAll("SHOW COLUMNS FROM registrations");
    foreach ($columns as $col) {
        echo sprintf("%-20s %-30s %s\n", $col['Field'], $col['Type'], $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL');
    }
    
    echo "\nDone!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
