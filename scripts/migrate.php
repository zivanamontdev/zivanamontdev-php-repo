<?php
/**
 * Run database migrations
 * Usage: php scripts/migrate.php
 */

// Load configuration
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "Running migrations...\n\n";
    
    // Get migration directory
    $migrationDir = __DIR__ . '/../database/migrations/';
    
    // Check if specific migration file is provided as argument
    if (isset($argv[1])) {
        $migrationFiles = [$migrationDir . $argv[1]];
    } else {
        // Get all migration files
        $migrationFiles = glob($migrationDir . '*.sql');
        sort($migrationFiles);
    }
    
    if (empty($migrationFiles)) {
        throw new Exception("No migration files found in: $migrationDir");
    }
    
    foreach ($migrationFiles as $migrationFile) {
        if (!file_exists($migrationFile)) {
            echo "✗ Migration file not found: $migrationFile\n\n";
            continue;
        }
        
        echo "Running migration: " . basename($migrationFile) . "\n";
        echo str_repeat('-', 60) . "\n";
        
        $sql = file_get_contents($migrationFile);
        
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        
        // Split by semicolon to execute multiple statements
        $statements = explode(';', $sql);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;
            
            // Show first 80 chars
            $preview = substr(str_replace(["\n", "\r", "\t"], ' ', $statement), 0, 80);
            echo "Executing: {$preview}...\n";
            
            try {
                $db->query($statement);
                echo "✓ Success\n\n";
            } catch (Exception $e) {
                echo "✗ Failed: " . $e->getMessage() . "\n\n";
            }
        }
    }
    
    echo "Migration process completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
