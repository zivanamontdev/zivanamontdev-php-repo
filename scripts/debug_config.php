<?php
define('ROOT_PATH', dirname(__DIR__));

// Replicate the config loading process
$envLoaded = false;
$envVars = [];
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    if ($env !== false && !empty($env)) {
        foreach ($env as $key => $value) {
            // Skip commented keys
            if (strpos($key, '#') === 0) {
                continue;
            }
            $envVars[$key] = $value;
        }
        $envLoaded = true;
    }
}

echo "envLoaded: " . ($envLoaded ? 'TRUE' : 'FALSE') . "\n";
echo "envVars count: " . count($envVars) . "\n";
echo "APP_URL: " . ($envVars['APP_URL'] ?? 'NOT SET') . "\n\n";

// Now require actual config
require ROOT_PATH . '/config/config.php';

echo "After config load:\n";
echo "APP_URL constant: " . APP_URL . "\n";
