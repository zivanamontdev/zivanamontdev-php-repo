<?php

// Define ROOT_PATH first
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance();

echo "========================================\n";
echo "Checking Program Harian IDs\n";
echo "========================================\n\n";

$programs = $db->query("SELECT id, day_name, program_name FROM programs_harian ORDER BY display_order ASC")->fetchAll();

if (empty($programs)) {
    echo "❌ No programs found in database!\n";
} else {
    echo "Found " . count($programs) . " programs:\n\n";
    foreach ($programs as $program) {
        echo "ID: {$program['id']} - Day: {$program['day_name']} - Program: {$program['program_name']}\n";
    }
}

echo "\n========================================\n";
echo "Done!\n";
echo "========================================\n";
