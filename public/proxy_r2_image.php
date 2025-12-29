<?php
/**
 * R2 Image Proxy
 * 
 * Proxy untuk serve R2 images di local development
 * Bypass SSL certificate issues
 */

// Disable error output to prevent breaking image data
error_reporting(0);
ini_set('display_errors', '0');

// Get image URL from query parameter
$imageUrl = $_GET['url'] ?? '';

if (empty($imageUrl)) {
    http_response_code(400);
    header('Content-Type: text/plain');
    die('No image URL provided');
}

// Validate URL is from R2
if (strpos($imageUrl, '.r2.dev') === false && strpos($imageUrl, 'r2.cloudflarestorage.com') === false) {
    http_response_code(403);
    header('Content-Type: text/plain');
    die('Invalid image URL - must be from R2');
}

// Fetch image from R2 with SSL verification disabled (for local dev only)
$ch = curl_init($imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for local dev
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HEADER, false);

$imageData = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// Check for cURL errors
if ($curlError) {
    http_response_code(500);
    header('Content-Type: text/plain');
    die('cURL Error: ' . $curlError);
}

// Check HTTP response code
if ($httpCode !== 200) {
    http_response_code($httpCode);
    header('Content-Type: text/plain');
    die('R2 returned HTTP ' . $httpCode);
}

// Check if image data is not empty
if (empty($imageData)) {
    http_response_code(500);
    header('Content-Type: text/plain');
    die('Empty image data received from R2');
}

// Determine content type from URL if not provided by R2
if (empty($contentType)) {
    $ext = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
    $contentTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'bmp' => 'image/bmp',
        'ico' => 'image/x-icon'
    ];
    $contentType = $contentTypes[$ext] ?? 'application/octet-stream';
}

// Set appropriate headers
header('Content-Type: ' . $contentType);
header('Cache-Control: public, max-age=86400'); // Cache for 1 day
header('Access-Control-Allow-Origin: *');
header('Content-Length: ' . strlen($imageData));

// Output image data
echo $imageData;
exit;
