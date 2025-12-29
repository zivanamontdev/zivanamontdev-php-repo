<?php
/**
 * Quick R2 Connection Test
 * 
 * Test cepat untuk memastikan R2 credentials dan connection bekerja
 */

require_once __DIR__ . '/config/config.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>R2 Connection Test</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
    .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
    h2 { margin-top: 0; color: #333; }
    .success { color: #28a745; font-weight: bold; }
    .error { color: #dc3545; font-weight: bold; }
    .warning { color: #ffc107; font-weight: bold; }
    .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0; border-radius: 5px; }
    pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
</style></head><body>";

echo "<div class='box'>";
echo "<h2>🔍 R2 Connection Test</h2>";

// 1. Check R2_ENABLED
echo "<h3>1. Configuration Check</h3>";
if (!defined('R2_ENABLED')) {
    echo "<p class='error'>❌ R2_ENABLED is not defined!</p>";
    echo "<p>Check your config/config.php file.</p>";
    exit;
}

if (!R2_ENABLED) {
    echo "<p class='warning'>⚠️ R2_ENABLED is FALSE</p>";
    echo "<p>Currently in LOCAL MODE. Set R2_ENABLED=true in .env to use R2.</p>";
    echo "</div></body></html>";
    exit;
}

echo "<p class='success'>✅ R2_ENABLED is TRUE</p>";

// 2. Check R2 credentials
echo "<h3>2. R2 Credentials Check</h3>";
$credentials_ok = true;

$required_constants = [
    'R2_ACCESS_KEY_ID',
    'R2_SECRET_ACCESS_KEY',
    'R2_ACCOUNT_ID',
    'R2_PUBLIC_BUCKET',
    'R2_ENDPOINT',
    'R2_PUBLIC_URL'
];

foreach ($required_constants as $const) {
    if (!defined($const) || empty(constant($const))) {
        echo "<p class='error'>❌ $const is not set!</p>";
        $credentials_ok = false;
    } else {
        if (strpos($const, 'SECRET') !== false || strpos($const, 'KEY') !== false) {
            echo "<p class='success'>✅ $const is set (***hidden***)</p>";
        } else {
            echo "<p class='success'>✅ $const is set: " . constant($const) . "</p>";
        }
    }
}

if (!$credentials_ok) {
    echo "<div class='info'>";
    echo "<strong>Fix:</strong> Add the missing credentials to your .env file:<br><br>";
    echo "<code>";
    echo "R2_ENABLED=true<br>";
    echo "R2_ACCESS_KEY_ID=your_access_key<br>";
    echo "R2_SECRET_ACCESS_KEY=your_secret_key<br>";
    echo "R2_ACCOUNT_ID=your_account_id<br>";
    echo "R2_PUBLIC_BUCKET=your-bucket-name<br>";
    echo "R2_ENDPOINT=https://your_account.r2.cloudflarestorage.com<br>";
    echo "R2_PUBLIC_URL=https://pub-xxxxx.r2.dev";
    echo "</code>";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

// 3. Test CloudflareR2 class initialization
echo "<h3>3. CloudflareR2 Class Test</h3>";
try {
    require_once APP_PATH . '/helpers/CloudflareR2.php';
    $r2 = new CloudflareR2();
    echo "<p class='success'>✅ CloudflareR2 class initialized successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Failed to initialize CloudflareR2 class</p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div></body></html>";
    exit;
}

// 4. Test R2 public bucket accessibility
echo "<h3>4. R2 Public Bucket Accessibility Test</h3>";
$testUrl = rtrim(R2_PUBLIC_URL, '/') . '/test.txt';
echo "<p>Testing URL: <code>$testUrl</code></p>";

$ch = curl_init($testUrl);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($httpCode === 404) {
    echo "<p class='success'>✅ Bucket is accessible (HTTP 404 - file doesn't exist, which is normal)</p>";
} elseif ($httpCode === 200) {
    echo "<p class='success'>✅ Bucket is accessible (HTTP 200)</p>";
} elseif ($httpCode === 403) {
    echo "<p class='error'>❌ Bucket is NOT accessible (HTTP 403 - Forbidden)</p>";
    echo "<div class='info'>";
    echo "<strong>Fix:</strong><br>";
    echo "1. Go to Cloudflare Dashboard > R2<br>";
    echo "2. Select your bucket: <code>" . R2_PUBLIC_BUCKET . "</code><br>";
    echo "3. Go to Settings > Public Access<br>";
    echo "4. Enable 'Allow Public Access'<br>";
    echo "5. Configure custom domain or use R2.dev domain";
    echo "</div>";
} else {
    echo "<p class='warning'>⚠️ Unexpected HTTP status code: $httpCode</p>";
    if ($curlError) {
        echo "<p>cURL Error: " . htmlspecialchars($curlError) . "</p>";
    }
}

// 5. Test file upload capability
echo "<h3>5. Test File Upload (Optional)</h3>";
echo "<p>You can now test actual file upload using the form below:</p>";
echo "<form action='debug_upload.php' method='GET' style='text-align: center;'>";
echo "<button type='submit' style='background: #007bff; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-weight: bold;'>Go to Upload Test Page</button>";
echo "</form>";

// Summary
echo "<h3>Summary</h3>";
echo "<div class='info'>";
echo "<strong>Configuration Status:</strong><br>";
echo "R2 Mode: <span class='success'>ENABLED ✅</span><br>";
echo "Public Bucket: <code>" . R2_PUBLIC_BUCKET . "</code><br>";
echo "Public URL: <code>" . R2_PUBLIC_URL . "</code><br>";
echo "Bucket Accessibility: " . ($httpCode === 404 || $httpCode === 200 ? "<span class='success'>OK ✅</span>" : "<span class='error'>FAILED ❌</span>") . "<br><br>";

if ($httpCode === 404 || $httpCode === 200) {
    echo "<strong>✅ All checks passed!</strong><br>";
    echo "Your R2 configuration is correct. You can now:<br>";
    echo "1. Test file upload using <a href='debug_upload.php'>debug_upload.php</a><br>";
    echo "2. Use the application normally<br>";
    echo "3. Check error logs if upload still fails: <code>storage/logs/error.log</code>";
} else {
    echo "<strong>⚠️ Some checks failed</strong><br>";
    echo "Please fix the issues above before proceeding.";
}
echo "</div>";

echo "</div>";
echo "</body></html>";
