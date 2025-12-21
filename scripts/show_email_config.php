<?php
/**
 * Show Email Configuration (for debugging)
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM email_settings WHERE id = 1");
    $stmt->execute();
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($config) {
        echo "Email SMTP Configuration:\n";
        echo "========================\n";
        echo "SMTP Username: " . $config['smtp_username'] . "\n";
        echo "SMTP Password: " . $config['smtp_password'] . "\n"; // Show actual password
        echo "Password Length: " . strlen($config['smtp_password']) . " characters\n";
        echo "From Name: " . $config['from_name'] . "\n";
        echo "Is Enabled: " . ($config['is_enabled'] ? 'YES' : 'NO') . "\n";
    } else {
        echo "No configuration found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
