<?php
/**
 * Script to migrate old data
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance();

// Migrate phone to whatsapp for old data
$db->query("UPDATE registrations SET whatsapp = phone WHERE whatsapp = '' OR whatsapp IS NULL");

echo "Updated old data - migrated phone to whatsapp\n";

// Show updated data
$registrations = $db->fetchAll("SELECT id, parent_name, child_name, child_age, address, phone, whatsapp FROM registrations ORDER BY created_at DESC LIMIT 5");

echo "\nUpdated data:\n";
foreach ($registrations as $reg) {
    echo "ID: {$reg['id']}, Parent: {$reg['parent_name']}, Child: {$reg['child_name']}, Age: " . ($reg['child_age'] ?? 'NULL') . ", Phone: {$reg['phone']}, WhatsApp: {$reg['whatsapp']}\n";
}
