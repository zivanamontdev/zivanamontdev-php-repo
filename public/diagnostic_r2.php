<?php
/**
 * Diagnostic & Fix Tool untuk R2 Upload Issue
 * Akses via: https://yourdomain.com/diagnostic_r2.php
 * HAPUS FILE INI setelah selesai!
 */

echo "<h2>🔍 R2 Diagnostic Tool</h2>";
echo "<hr>";

// 1. Check if .env file exists
echo "<h3>1. File .env Check:</h3>";
$envPath = dirname(__DIR__) . '/.env';  // Go up one level from public/
if (file_exists($envPath)) {
    echo "<p style='color: green;'>✅ File .env exists at: " . $envPath . "</p>";
    
    // Show .env content (masked sensitive data)
    $envContent = file_get_contents($envPath);
    $lines = explode("\n", $envContent);
    
    echo "<pre>";
    foreach ($lines as $line) {
        if (strpos($line, 'R2_') === 0) {
            if (strpos($line, 'R2_ACCESS_KEY_ID') !== false || 
                strpos($line, 'R2_SECRET_ACCESS_KEY') !== false) {
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $value = $parts[1];
                    if (strlen($value) > 16) {
                        $masked = substr($value, 0, 8) . '***' . substr($value, -8);
                        echo $parts[0] . '=' . $masked . "\n";
                    } else {
                        echo $line . "\n";
                    }
                }
            } else {
                echo $line . "\n";
            }
        }
    }
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ File .env NOT FOUND!</p>";
    echo "<p>⚠️ <strong>ACTION REQUIRED:</strong> Copy .env.production to .env</p>";
}

echo "<hr>";

// 2. Load config and check constants
echo "<h3>2. PHP Constants Check:</h3>";

// Manually load env like config.php does
$envVars = [];
$envPath = dirname(__DIR__) . '/.env';  // Go up one level from public/
if (file_exists($envPath)) {
    // Debug: Show parse_ini_file result
    $env = parse_ini_file($envPath);
    
    echo "<p><strong>🔍 DEBUG parse_ini_file():</strong></p>";
    echo "<pre>";
    if ($env === false) {
        echo "❌ parse_ini_file() returned FALSE - parsing failed!\n";
        echo "Trying manual parsing instead...\n\n";
        
        // Fallback: Manual parsing
        $content = file_get_contents($envPath);
        $lines = explode("\n", $content);
        $env = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Remove quotes if any
                $value = trim($value, '"\'');
                $env[$key] = $value;
            }
        }
        echo "Manual parsing result: " . count($env) . " variables found\n";
    } else {
        echo "✅ parse_ini_file() SUCCESS - found " . count($env) . " variables\n";
    }
    var_dump($env);
    echo "</pre>";
    
    if ($env !== false && !empty($env)) {
        foreach ($env as $key => $value) {
            if (strpos($key, '#') !== 0) {
                $envVars[$key] = $value;
            }
        }
    }
}

echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
echo "<tr><th>Config Key</th><th>Value from .env</th><th>After filter_var</th></tr>";

$r2Enabled = $envVars['R2_ENABLED'] ?? 'NOT SET';
$r2EnabledBool = filter_var($r2Enabled, FILTER_VALIDATE_BOOLEAN);

echo "<tr><td>R2_ENABLED</td><td>" . htmlspecialchars($r2Enabled) . "</td><td>" . ($r2EnabledBool ? 'TRUE' : 'FALSE') . "</td></tr>";
echo "<tr><td>R2_ACCESS_KEY_ID</td><td>" . (isset($envVars['R2_ACCESS_KEY_ID']) && !empty($envVars['R2_ACCESS_KEY_ID']) ? 'SET (masked)' : 'NOT SET') . "</td><td>-</td></tr>";
echo "<tr><td>R2_PUBLIC_BUCKET</td><td>" . htmlspecialchars($envVars['R2_PUBLIC_BUCKET'] ?? 'NOT SET') . "</td><td>-</td></tr>";
echo "<tr><td>R2_PUBLIC_URL</td><td>" . htmlspecialchars($envVars['R2_PUBLIC_URL'] ?? 'NOT SET') . "</td><td>-</td></tr>";
echo "</table>";

echo "<hr>";

// 3. Clear OPcache
echo "<h3>3. PHP OPcache Status:</h3>";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<p style='color: green;'>✅ OPcache cleared successfully!</p>";
    echo "<p>ℹ️ Old cached config.php has been removed from memory</p>";
} else {
    echo "<p style='color: orange;'>⚠️ OPcache not available or not enabled</p>";
}

// Clear realpath cache
clearstatcache(true);
echo "<p style='color: green;'>✅ Realpath cache cleared</p>";

echo "<hr>";

// 4. Test upload to R2
echo "<h3>4. R2 Connection Test:</h3>";

if (!$r2EnabledBool) {
    echo "<p style='color: red;'>❌ R2_ENABLED is FALSE - uploads will go to local storage!</p>";
    echo "<p><strong>Fix:</strong> Set R2_ENABLED=true in .env file</p>";
} else {
    echo "<p style='color: green;'>✅ R2_ENABLED is TRUE - uploads will go to R2!</p>";
    
    // Try to load CloudflareR2
    $helperPath = dirname(__DIR__) . '/app/helpers/CloudflareR2.php';
    if (file_exists($helperPath)) {
        echo "<p style='color: green;'>✅ CloudflareR2.php exists</p>";
        
        require_once dirname(__DIR__) . '/app/core/Database.php';
        require_once $helperPath;
        
        try {
            $r2 = new CloudflareR2();
            echo "<p style='color: green;'>✅ CloudflareR2 client initialized successfully!</p>";
            echo "<p>R2 is ready to accept uploads.</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Failed to initialize R2 client: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ CloudflareR2.php not found!</p>";
    }
}

echo "<hr>";
echo "<h3>5. Summary & Action:</h3>";

if (!file_exists($envPath)) {
    echo "<p style='color: red;'><strong>❌ CRITICAL:</strong> File .env tidak ditemukan!</p>";
    echo "<p><strong>ACTION:</strong></p>";
    echo "<ol>";
    echo "<li>Login ke File Manager hosting</li>";
    echo "<li>Copy file <code>.env.production</code></li>";
    echo "<li>Paste dan rename jadi <code>.env</code></li>";
    echo "<li>Refresh halaman ini untuk verify</li>";
    echo "</ol>";
} else if (!$r2EnabledBool) {
    echo "<p style='color: red;'><strong>❌ CRITICAL:</strong> R2_ENABLED is FALSE!</p>";
    echo "<p><strong>ACTION:</strong></p>";
    echo "<ol>";
    echo "<li>Edit file <code>.env</code> di hosting</li>";
    echo "<li>Ubah <code>R2_ENABLED=false</code> menjadi <code>R2_ENABLED=true</code></li>";
    echo "<li>Save file</li>";
    echo "<li>Refresh halaman ini untuk verify</li>";
    echo "</ol>";
} else {
    echo "<p style='color: green;'><strong>✅ ALL GOOD!</strong> R2 is enabled and ready!</p>";
    echo "<p>Upload gambar di Admin Panel seharusnya langsung ke R2 sekarang.</p>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>Test upload gambar di tab Informasi Kelas</li>";
    echo "<li>Check database - kolom image harus berisi URL R2 (https://pub-xxx.r2.dev/...)</li>";
    echo "<li>Jika sukses, <strong>HAPUS file diagnostic_r2.php ini!</strong></li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ PENTING: Hapus file diagnostic_r2.php setelah selesai debugging!</p>";
?>
