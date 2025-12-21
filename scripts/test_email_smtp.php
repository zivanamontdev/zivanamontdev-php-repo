<?php
/**
 * Test Email SMTP Configuration
 */

// Load config
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/models/EmailSetting.php';

echo "=== EMAIL SMTP CONFIGURATION TEST ===\n\n";

// 1. Check database connection
echo "1. Database Connection: ";
try {
    $db = Database::getInstance();
    echo "✓ Connected\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check email_settings table exists
echo "\n2. Email Settings Table: ";
try {
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'email_settings'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_NUM);
    if ($result) {
        echo "✓ Exists\n";
    } else {
        echo "✗ Table not found\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Check email configuration
echo "\n3. Email Configuration:\n";
$emailSetting = new EmailSetting();
$config = $emailSetting->getConfig();

if ($config) {
    echo "   - ID: " . $config['id'] . "\n";
    echo "   - SMTP Username: " . ($config['smtp_username'] ?: '(empty)') . "\n";
    echo "   - SMTP Password: " . ($config['smtp_password'] ? str_repeat('*', strlen($config['smtp_password'])) : '(empty)') . "\n";
    echo "   - From Name: " . ($config['from_name'] ?: '(empty)') . "\n";
    echo "   - Is Enabled: " . ($config['is_enabled'] ? '✓ YES' : '✗ NO') . "\n";
    echo "   - Updated At: " . $config['updated_at'] . "\n";
} else {
    echo "   ✗ No configuration found\n";
}

// 4. Test email sending
echo "\n4. Test Email Sending:\n";
require_once ROOT_PATH . '/app/helpers/email.php';

$testEmail = 'andirifqialnur276@gmail.com';
$subject = 'Test Email SMTP - ' . date('Y-m-d H:i:s');
$message = '<h2>Test Email</h2><p>Ini adalah test email dari sistem Zivana Montessori School.</p><p>Waktu: ' . date('Y-m-d H:i:s') . '</p>';

echo "   Sending test email to: {$testEmail}\n";
echo "   Subject: {$subject}\n\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$result = send_email($testEmail, $subject, $message);

if ($result) {
    echo "   ✓ Email sent successfully!\n";
    echo "   Check your inbox: {$testEmail}\n";
    echo "   Also check SPAM folder if not in inbox.\n";
} else {
    echo "   ✗ Email sending failed\n";
    echo "   Check storage/logs/emails.log for details\n";
}

echo "\n=== TEST COMPLETED ===\n";
