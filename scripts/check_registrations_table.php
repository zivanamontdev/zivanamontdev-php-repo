<?php
/**
 * Script to check and update registrations table structure
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance();

// Check table structure
echo "Checking registrations table structure...\n\n";

try {
    $columns = $db->fetchAll("SHOW COLUMNS FROM registrations");
    
    echo "Current columns in registrations table:\n";
    echo str_repeat("-", 60) . "\n";
    
    $existingColumns = [];
    foreach ($columns as $col) {
        echo sprintf("%-20s %-30s %s\n", $col['Field'], $col['Type'], $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL');
        $existingColumns[] = $col['Field'];
    }
    
    echo "\n";
    
    // Check if child_age column exists
    if (!in_array('child_age', $existingColumns)) {
        echo "Adding child_age column...\n";
        $db->query("ALTER TABLE registrations ADD COLUMN child_age VARCHAR(50) NULL AFTER child_name");
        echo "child_age column added successfully.\n";
    } else {
        echo "child_age column already exists.\n";
    }
    
    // Check if whatsapp column exists
    if (!in_array('whatsapp', $existingColumns)) {
        echo "Adding whatsapp column...\n";
        $db->query("ALTER TABLE registrations ADD COLUMN whatsapp VARCHAR(20) NOT NULL AFTER address");
        echo "whatsapp column added successfully.\n";
    } else {
        echo "whatsapp column already exists.\n";
    }
    
    // Show sample data
    echo "\nSample data from registrations table:\n";
    echo str_repeat("-", 100) . "\n";
    
    $registrations = $db->fetchAll("SELECT * FROM registrations ORDER BY created_at DESC LIMIT 5");
    
    if (empty($registrations)) {
        echo "No data found in registrations table.\n";
    } else {
        foreach ($registrations as $reg) {
            echo "ID: {$reg['id']}\n";
            echo "Parent Name: " . ($reg['parent_name'] ?? 'NULL') . "\n";
            echo "Child Name: " . ($reg['child_name'] ?? 'NULL') . "\n";
            echo "Child Age: " . ($reg['child_age'] ?? 'NULL') . "\n";
            echo "Address: " . ($reg['address'] ?? 'NULL') . "\n";
            echo "WhatsApp: " . ($reg['whatsapp'] ?? 'NULL') . "\n";
            echo "Created At: " . ($reg['created_at'] ?? 'NULL') . "\n";
            echo str_repeat("-", 50) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
