<?php
/**
 * Check database tables and data
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if classes table exists
    $result = $db->query("SHOW TABLES LIKE 'classes'")->fetch();
    
    if ($result) {
        echo "✓ Table 'classes' exists\n\n";
        
        // Get table structure
        echo "Table structure:\n";
        $columns = $db->query("DESCRIBE classes")->fetchAll();
        foreach ($columns as $col) {
            echo "  - {$col['Field']} ({$col['Type']})\n";
        }
        
        // Get data count
        $count = $db->query("SELECT COUNT(*) as total FROM classes")->fetch();
        echo "\nTotal records: {$count['total']}\n\n";
        
        // Get sample data
        echo "Sample data:\n";
        $classes = $db->query("SELECT * FROM classes LIMIT 5")->fetchAll();
        foreach ($classes as $class) {
            echo "  - ID: {$class['id']}, Name: {$class['name']}, Age: {$class['age_range']}\n";
        }
        
    } else {
        echo "✗ Table 'classes' does not exist\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
