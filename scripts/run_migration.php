<?php
/**
 * Simple Migration Runner Script
 * Usage: php scripts/run_migration.php <migration_file>
 */

// Get the migration file from command line argument
$migrationFile = $argv[1] ?? null;

if (!$migrationFile) {
    echo "Usage: php scripts/run_migration.php <migration_file>\n";
    echo "Example: php scripts/run_migration.php 006_create_kepala_sekolah_table.sql\n";
    exit(1);
}

// Define paths
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';

// Build full path to migration file
$migrationPath = ROOT_PATH . '/database/migrations/' . $migrationFile;

if (!file_exists($migrationPath)) {
    echo "Error: Migration file not found: $migrationPath\n";
    exit(1);
}

try {
    // Create database connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL file
    $sql = file_get_contents($migrationPath);
    $pdo->exec($sql);
    
    echo "✓ Migration executed successfully: $migrationFile\n";
} catch (PDOException $e) {
    echo "✗ Error executing migration: " . $e->getMessage() . "\n";
    exit(1);
}
