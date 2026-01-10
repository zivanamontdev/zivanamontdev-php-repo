<?php
// Test file untuk admin subdomain
// Upload ke: /public_html/subdomain/admin/test.php
// Akses: https://admin.sekolahzivanamontessori.sch.id/test.php

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Subdomain Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f0fdf4; }
        .success { color: #16a34a; font-size: 24px; font-weight: bold; }
        .info { background: white; padding: 15px; margin: 20px 0; border-radius: 8px; }
    </style>
</head>
<body>
    <h1 class="success">✅ Admin Subdomain Working!</h1>
    
    <div class="info">
        <h2>Server Info:</h2>
        <p><strong>HTTP_HOST:</strong> <?= htmlspecialchars($_SERVER['HTTP_HOST']) ?></p>
        <p><strong>REQUEST_URI:</strong> <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></p>
        <p><strong>SCRIPT_FILENAME:</strong> <?= htmlspecialchars($_SERVER['SCRIPT_FILENAME']) ?></p>
        <p><strong>DOCUMENT_ROOT:</strong> <?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?></p>
    </div>
    
    <div class="info">
        <h2>File Check:</h2>
        <?php
        $indexPath = __DIR__ . '/index.php';
        $htaccessPath = __DIR__ . '/.htaccess';
        
        echo '<p><strong>index.php:</strong> ' . (file_exists($indexPath) ? '✅ EXISTS' : '❌ NOT FOUND') . '</p>';
        echo '<p><strong>.htaccess:</strong> ' . (file_exists($htaccessPath) ? '✅ EXISTS' : '❌ NOT FOUND') . '</p>';
        echo '<p><strong>Current Dir:</strong> ' . htmlspecialchars(__DIR__) . '</p>';
        ?>
    </div>
    
    <div class="info">
        <h2>Next Steps:</h2>
        <ol>
            <li>✅ Admin subdomain working</li>
            <li>Test: <a href="/">Root (should redirect to /admin/login)</a></li>
            <li>Test: <a href="/admin/login">/admin/login (should show login page)</a></li>
            <li>If all OK, delete this test.php file</li>
        </ol>
    </div>
    
    <hr>
    <p style="color: #6b7280; font-size: 12px;">Test file - delete after verification</p>
</body>
</html>
