<?php
/**
 * Test Email Settings Update
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/models/EmailSetting.php';

echo "Testing Email Settings Update\n";
echo "==============================\n\n";

$model = new EmailSetting();

// 1. Check existing config
echo "1. Current config:\n";
$config = $model->getConfig();
if ($config) {
    echo "   Email: " . $config['smtp_username'] . "\n";
    echo "   Enabled: " . ($config['is_enabled'] ? 'YES' : 'NO') . "\n\n";
} else {
    echo "   No config found\n\n";
}

// 2. Test update with SAME data (should still succeed)
echo "2. Testing update with same data:\n";
$data = [
    'smtp_username' => 'zivanamont.dev@gmail.com',
    'smtp_password' => 'gfaqvdtvasggsmws',
    'is_enabled' => 1,
    'from_name' => 'Zivana Montessori School'
];

$result = $model->updateConfig($data);
echo "   Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n\n";

// 3. Verify update
echo "3. Verify config after update:\n";
$configAfter = $model->getConfig();
if ($configAfter) {
    echo "   Email: " . $configAfter['smtp_username'] . "\n";
    echo "   Enabled: " . ($configAfter['is_enabled'] ? 'YES' : 'NO') . "\n";
    echo "   Updated: " . $configAfter['updated_at'] . "\n\n";
}

// 4. Test connection
echo "4. Testing SMTP connection:\n";
$testConn = $model->testConnection($data['smtp_username'], $data['smtp_password']);
echo "   Connection: " . ($testConn ? 'SUCCESS' : 'FAILED') . "\n\n";

echo "==============================\n";
echo "Test completed!\n";
