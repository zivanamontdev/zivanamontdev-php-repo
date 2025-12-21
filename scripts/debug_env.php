<?php
define('ROOT_PATH', dirname(__DIR__));

echo "Debug .env loading:\n";
echo "===================\n\n";

$envFile = ROOT_PATH . '/.env';
echo "1. File exists: " . (file_exists($envFile) ? 'YES' : 'NO') . "\n";

if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    echo "2. parse_ini_file result: " . ($env !== false ? 'SUCCESS' : 'FAILED') . "\n";
    echo "3. Array empty: " . (empty($env) ? 'YES' : 'NO') . "\n";
    echo "4. Array count: " . count($env) . "\n\n";
    
    echo "5. Array contents:\n";
    foreach ($env as $key => $value) {
        if (strpos($key, '#') === 0) {
            echo "   SKIP: $key (commented)\n";
            continue;
        }
        echo "   SET: $key = $value\n";
        $_ENV[$key] = $value;
    }
    
    echo "\n6. Final $_ENV:\n";
    print_r($_ENV);
    
    echo "\n7. Test APP_URL: " . ($_ENV['APP_URL'] ?? 'NOT SET') . "\n";
}
