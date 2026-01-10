<?php
// Test file: /public_html/public/test_middleware.php
// Akses: https://sekolahzivanamontessori.sch.id/test_middleware.php

// Simulate middleware without loading app
$_SERVER['HTTP_HOST'] = 'sekolahzivanamontessori.sch.id';
$_SERVER['REQUEST_URI'] = '/admin';

$uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($uri, PHP_URL_PATH);
$isAdminRoute = strpos($path, '/admin') === 0;
$host = $_SERVER['HTTP_HOST'] ?? '';
$isDev = (
    strpos($host, 'localhost') !== false || 
    strpos($host, '127.0.0.1') !== false ||
    strpos($host, 'dev.') === 0
);

header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head><title>Middleware Test</title></head>
<body style="font-family: monospace; padding: 20px;">
    <h1>Middleware Debug</h1>
    <table border="1" cellpadding="10">
        <tr><td>Request URI</td><td><?= htmlspecialchars($uri) ?></td></tr>
        <tr><td>Path</td><td><?= htmlspecialchars($path) ?></td></tr>
        <tr><td>Host</td><td><?= htmlspecialchars($host) ?></td></tr>
        <tr><td>Is Admin Route</td><td><?= $isAdminRoute ? 'YES' : 'NO' ?></td></tr>
        <tr><td>Is Dev</td><td><?= $isDev ? 'YES' : 'NO' ?></td></tr>
        <tr><td>Should Block</td><td><?= (!$isDev && $isAdminRoute) ? 'YES (404)' : 'NO (Allow)' ?></td></tr>
    </table>
    
    <h2>Test Result:</h2>
    <?php if (!$isDev && $isAdminRoute): ?>
        <p style="color: green; font-weight: bold;">✅ Logic OK - Should show 404</p>
    <?php else: ?>
        <p style="color: red; font-weight: bold;">❌ Logic Issue - Should allow access</p>
    <?php endif; ?>
    
    <p><a href="/">Back to Home</a></p>
</body>
</html>
