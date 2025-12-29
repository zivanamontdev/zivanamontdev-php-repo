<?php
/**
 * Debug Proxy R2 Connection
 */

echo "<!DOCTYPE html><html><head><title>Proxy Debug</title></head><body>";
echo "<h1>🔍 Proxy R2 Debug</h1>";

// Check cURL
echo "<h2>1. cURL Extension</h2>";
if (function_exists('curl_init')) {
    echo "<p style='color:green'>✅ cURL is installed</p>";
    $version = curl_version();
    echo "<pre>" . print_r($version, true) . "</pre>";
} else {
    echo "<p style='color:red'>❌ cURL is NOT installed</p>";
}

// Test R2 URL
$testUrl = 'https://pub-445331fec5ba4ec3af5c54ddc3ec1f4b.r2.dev/classes/1766843696_2b209a6ab8606d03_1766843696.png';

echo "<h2>2. Test R2 Direct Access</h2>";
echo "<p><strong>Testing URL:</strong> " . htmlspecialchars($testUrl) . "</p>";

$ch = curl_init($testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

echo "<h3>Response Details:</h3>";
echo "<ul>";
echo "<li><strong>HTTP Code:</strong> " . $httpCode;
if ($httpCode == 200) {
    echo " <span style='color:green'>✅</span>";
} else {
    echo " <span style='color:red'>❌</span>";
}
echo "</li>";
echo "<li><strong>Content-Type:</strong> " . htmlspecialchars($contentType) . "</li>";
echo "<li><strong>cURL Error:</strong> " . ($error ? '<span style="color:red">' . htmlspecialchars($error) . '</span>' : '<span style="color:green">None</span>') . "</li>";
echo "</ul>";

if ($error) {
    echo "<p style='color:red'><strong>⚠️ cURL Error Detected!</strong></p>";
    echo "<p>This means the proxy cannot fetch from R2. Possible causes:</p>";
    echo "<ul>";
    echo "<li>Network connectivity issue</li>";
    echo "<li>Firewall blocking outbound requests</li>";
    echo "<li>SSL certificate issue (should be bypassed though)</li>";
    echo "</ul>";
}

if ($httpCode !== 200) {
    echo "<p style='color:red'><strong>⚠️ R2 Returned Non-200 Status!</strong></p>";
    
    if ($httpCode == 403) {
        echo "<p>🔒 <strong>403 Forbidden</strong> - R2 bucket is NOT public or file doesn't have public permissions.</p>";
        echo "<p><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li>Go to Cloudflare Dashboard</li>";
        echo "<li>Navigate to R2 → Buckets → zivana-public</li>";
        echo "<li>Settings → Public Access → Enable</li>";
        echo "<li>Or set bucket policy to allow public read</li>";
        echo "</ol>";
    } elseif ($httpCode == 404) {
        echo "<p>📁 <strong>404 Not Found</strong> - File doesn't exist in R2 bucket.</p>";
        echo "<p><strong>Check:</strong></p>";
        echo "<ul>";
        echo "<li>Upload was successful?</li>";
        echo "<li>Path in database matches actual path in R2?</li>";
        echo "<li>Bucket name is correct (zivana-public)?</li>";
        echo "</ul>";
    } else {
        echo "<p>HTTP Status " . $httpCode . " - Check R2 configuration and network.</p>";
    }
}

// Show headers
echo "<h3>Response Headers:</h3>";
$headers = substr($response, 0, $headerSize);
echo "<pre>" . htmlspecialchars($headers) . "</pre>";

// Show body preview (first 200 bytes)
$body = substr($response, $headerSize);
if ($httpCode == 200) {
    echo "<h3>Body Preview (first 200 bytes):</h3>";
    echo "<pre>" . htmlspecialchars(substr($body, 0, 200)) . "...</pre>";
    echo "<p><strong>Body Size:</strong> " . strlen($body) . " bytes</p>";
    
    // Try to display image
    echo "<h3>Image Preview:</h3>";
    $base64 = base64_encode($body);
    echo "<img src='data:" . $contentType . ";base64," . $base64 . "' style='max-width:300px; border:1px solid #ccc;' />";
} else {
    echo "<h3>Response Body:</h3>";
    echo "<pre>" . htmlspecialchars($body) . "</pre>";
}

// Test proxy script
echo "<h2>3. Test Proxy Script</h2>";
$proxyUrl = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/proxy_r2_image.php?url=' . urlencode($testUrl);
echo "<p><strong>Proxy URL:</strong> <a href='" . htmlspecialchars($proxyUrl) . "' target='_blank'>" . htmlspecialchars($proxyUrl) . "</a></p>";

echo "<p><strong>Try opening the proxy URL above in a new tab.</strong></p>";

echo "<h2>4. Recommendations</h2>";

if ($httpCode == 200 && empty($error)) {
    echo "<p style='color:green'>✅ <strong>R2 access is working!</strong> Proxy should work now.</p>";
    echo "<p>If images still don't display in the page:</p>";
    echo "<ol>";
    echo "<li>Hard refresh the page (Ctrl+Shift+R or Cmd+Shift+R)</li>";
    echo "<li>Clear browser cache</li>";
    echo "<li>Check browser console for JavaScript errors</li>";
    echo "<li>Verify database has correct R2 URLs (starting with https://pub-xxx.r2.dev/)</li>";
    echo "</ol>";
} else {
    echo "<p style='color:red'>❌ <strong>R2 access is NOT working.</strong></p>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>Make sure R2 bucket 'zivana-public' is set to PUBLIC</li>";
    echo "<li>Test direct R2 URL in browser (without proxy)</li>";
    echo "<li>Check R2 bucket settings in Cloudflare dashboard</li>";
    echo "<li>Verify the file exists in R2</li>";
    echo "</ol>";
}

echo "</body></html>";
