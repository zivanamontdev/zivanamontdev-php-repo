<?php
/**
 * Check programs_tahun table
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "Checking programs_tahun table...\n\n";
    
    // Check if table exists
    $tables = $db->query("SHOW TABLES LIKE 'programs_tahun'")->fetchAll();
    
    if (empty($tables)) {
        echo "✗ Table 'programs_tahun' does not exist\n";
        exit(1);
    }
    
    echo "✓ Table 'programs_tahun' exists\n\n";
    
    // Get table structure
    $columns = $db->query("DESCRIBE programs_tahun")->fetchAll();
    echo "Table structure:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";
    
    // Get record count
    $count = $db->query("SELECT COUNT(*) as count FROM programs_tahun")->fetch();
    echo "Total records: {$count['count']}\n\n";
    
    // Get sample data
    $programs = $db->query("SELECT * FROM programs_tahun ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
    if (!empty($programs)) {
        echo "Sample data:\n";
        foreach ($programs as $program) {
            echo "  - ID: {$program['id']}, Name: {$program['name']}\n";
            echo "    Description: " . substr($program['description'], 0, 50) . "...\n";
            echo "    Image: " . ($program['image'] ?: 'NULL') . "\n";
            echo "    Active: " . ($program['is_active'] ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "No records found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
