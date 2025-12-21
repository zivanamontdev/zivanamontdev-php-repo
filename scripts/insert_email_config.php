<?php
/**
 * Insert Email SMTP Configuration to Database
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

echo "=== INSERTING EMAIL SMTP CONFIGURATION ===\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Data konfigurasi dari user
    $smtp_username = 'zivanamont.dev@gmail.com';
    $smtp_password = 'gfaqvdtvasggsmws'; // App Password baru (tanpa spasi)
    $from_name = 'Zivana Montessori School';
    $is_enabled = 1;
    
    // Check if record exists
    $stmt = $pdo->prepare("SELECT id FROM email_settings WHERE id = 1");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exists) {
        // Update existing record
        echo "Updating existing configuration...\n";
        $sql = "UPDATE email_settings 
                SET smtp_username = :username, 
                    smtp_password = :password, 
                    from_name = :from_name,
                    is_enabled = :is_enabled,
                    updated_at = NOW()
                WHERE id = 1";
    } else {
        // Insert new record
        echo "Inserting new configuration...\n";
        $sql = "INSERT INTO email_settings (id, smtp_username, smtp_password, from_name, is_enabled, updated_at)
                VALUES (1, :username, :password, :from_name, :is_enabled, NOW())";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $smtp_username);
    $stmt->bindParam(':password', $smtp_password);
    $stmt->bindParam(':from_name', $from_name);
    $stmt->bindParam(':is_enabled', $is_enabled);
    $stmt->execute();
    
    echo "✓ Configuration saved successfully!\n\n";
    
    // Verify
    echo "Verifying configuration:\n";
    $stmt = $pdo->prepare("SELECT * FROM email_settings WHERE id = 1");
    $stmt->execute();
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($config) {
        echo "   - SMTP Username: " . $config['smtp_username'] . "\n";
        echo "   - SMTP Password: " . str_repeat('*', strlen($config['smtp_password'])) . " (hidden)\n";
        echo "   - From Name: " . $config['from_name'] . "\n";
        echo "   - Is Enabled: " . ($config['is_enabled'] ? 'YES' : 'NO') . "\n";
        echo "   - Updated At: " . $config['updated_at'] . "\n";
    }
    
    echo "\n✓ CONFIGURATION INSERTED SUCCESSFULLY!\n";
    echo "\nNext step: Run 'php scripts/test_email_smtp.php' to test email sending\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
