<?php
/**
 * Script to test registration submission
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/models/Registration.php';

$db = Database::getInstance();
$registration = new Registration();

// Insert test data
$testData = [
    'parent_name' => 'Budi Santoso',
    'child_name' => 'Andi Santoso',
    'child_age' => '3 tahun',
    'address' => 'Jl. Merdeka No. 123, Jakarta Selatan',
    'whatsapp' => '081234567890',
    'status' => 'new',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 Test',
];

try {
    $id = $registration->create($testData);
    echo "Test registration created with ID: $id\n\n";
    
    // Fetch and display all registrations
    echo "All registrations:\n";
    echo str_repeat("-", 100) . "\n";
    
    $all = $db->fetchAll("SELECT id, parent_name, child_name, child_age, address, whatsapp, created_at FROM registrations ORDER BY created_at DESC");
    
    foreach ($all as $reg) {
        echo "ID: {$reg['id']}\n";
        echo "  Parent: {$reg['parent_name']}\n";
        echo "  Child: {$reg['child_name']}\n";
        echo "  Age: " . ($reg['child_age'] ?? 'NULL') . "\n";
        echo "  Address: " . ($reg['address'] ?? 'NULL') . "\n";
        echo "  WhatsApp: " . ($reg['whatsapp'] ?? 'NULL') . "\n";
        echo "  Created: {$reg['created_at']}\n";
        echo str_repeat("-", 50) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
