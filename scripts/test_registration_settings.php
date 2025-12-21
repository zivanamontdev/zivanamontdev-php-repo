<?php
/**
 * Script to test registration settings
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/models/RegistrationSetting.php';

$registrationSettingModel = new RegistrationSetting();

echo "=== Test Registration Settings ===\n\n";

// Test 1: Get current settings
echo "1. Getting current settings...\n";
$whatsappNumber = $registrationSettingModel->get('whatsapp_number');
$whatsappTemplate = $registrationSettingModel->get('whatsapp_template');

echo "WhatsApp Number: " . ($whatsappNumber ?: '(not set)') . "\n";
echo "WhatsApp Template: " . ($whatsappTemplate ? substr($whatsappTemplate, 0, 50) . '...' : '(not set)') . "\n\n";

// Test 2: Set test values
echo "2. Setting test values...\n";
$testNumber = '6281234567890';
$testTemplate = "*PENDAFTARAN BARU - Zivana Montessori School*\n\n*Nama Anak:* {childName}\n*Nama Orang Tua:* {parentName}\n*Nomor Telepon:* {phone}\n{address}\n{message}\n\nTerima kasih telah mendaftar di Zivana Montessori School!";

$result1 = $registrationSettingModel->set('whatsapp_number', $testNumber);
$result2 = $registrationSettingModel->set('whatsapp_template', $testTemplate);

echo "Set WhatsApp Number: " . ($result1 ? 'SUCCESS' : 'FAILED') . "\n";
echo "Set WhatsApp Template: " . ($result2 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Test 3: Verify saved values
echo "3. Verifying saved values...\n";
$savedNumber = $registrationSettingModel->get('whatsapp_number');
$savedTemplate = $registrationSettingModel->get('whatsapp_template');

echo "Saved WhatsApp Number: " . $savedNumber . "\n";
echo "Match: " . ($savedNumber === $testNumber ? 'YES' : 'NO') . "\n\n";

echo "Saved WhatsApp Template (first 100 chars): " . substr($savedTemplate, 0, 100) . "...\n";
echo "Match: " . ($savedTemplate === $testTemplate ? 'YES' : 'NO') . "\n\n";

// Test 4: Get all settings
echo "4. Getting all settings...\n";
$allSettings = $registrationSettingModel->getAll();
echo "Total settings: " . count($allSettings) . "\n";
foreach ($allSettings as $key => $value) {
    echo "  - {$key}: " . substr($value, 0, 50) . (strlen($value) > 50 ? '...' : '') . "\n";
}

echo "\n=== Test Complete ===\n";
