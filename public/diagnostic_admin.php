<?php
/**
 * Admin Subdomain Diagnostic Tool
 * Upload ke /public_html/public/diagnostic_admin.php
 * Akses: https://sekolahzivanamontessori.sch.id/diagnostic_admin.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Subdomain Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #2563eb; }
        .ok { color: #16a34a; }
        .error { color: #dc2626; }
        .warning { color: #ea580c; }
        h2 { margin-top: 0; color: #1e40af; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <h1>🔍 Admin Subdomain Diagnostic</h1>
    
    <div class="section">
        <h2>1. Server Information</h2>
        <table>
            <tr>
                <td>HTTP_HOST</td>
                <td><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>SERVER_NAME</td>
                <td><?= htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>REQUEST_URI</td>
                <td><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>DOCUMENT_ROOT</td>
                <td><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') ?></td>
            </tr>
            <tr>
                <td>SCRIPT_FILENAME</td>
                <td><?= htmlspecialchars($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') ?></td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <h2>2. File Structure Check</h2>
        <?php
        $docRoot = $_SERVER['DOCUMENT_ROOT'];
        $checks = [
            'index.php' => $docRoot . '/index.php',
            '.htaccess' => $docRoot . '/.htaccess',
            'Admin subdomain index.php' => $docRoot . '/subdomain/admin/index.php',
            'Admin subdomain .htaccess' => $docRoot . '/subdomain/admin/.htaccess',
        ];
        
        echo '<table>';
        foreach ($checks as $name => $path) {
            $exists = file_exists($path);
            $class = $exists ? 'ok' : 'error';
            $status = $exists ? '✓ EXISTS' : '✗ NOT FOUND';
            echo "<tr><td>$name</td><td class='$class'>$status<br><small>$path</small></td></tr>";
        }
        echo '</table>';
        ?>
    </div>
    
    <div class="section">
        <h2>3. .htaccess Content Check</h2>
        <?php
        $htaccessPath = $docRoot . '/.htaccess';
        if (file_exists($htaccessPath)) {
            $content = file_get_contents($htaccessPath);
            $hasAdminRedirect = (strpos($content, 'admin.sekolahzivanamontessori.sch.id') !== false);
            
            if ($hasAdminRedirect) {
                echo '<p class="ok">✓ Admin redirect rule found in .htaccess</p>';
            } else {
                echo '<p class="error">✗ Admin redirect rule NOT found in .htaccess</p>';
                echo '<p class="warning">⚠️ You need to add the redirect rule from root_htaccess_config.txt</p>';
            }
            
            echo '<h3>Current .htaccess content (first 50 lines):</h3>';
            echo '<pre style="background:#f9f9f9;padding:10px;border-radius:4px;overflow:auto;">';
            $lines = explode("\n", $content);
            echo htmlspecialchars(implode("\n", array_slice($lines, 0, 50)));
            echo '</pre>';
        } else {
            echo '<p class="error">✗ .htaccess file not found</p>';
        }
        ?>
    </div>
    
    <div class="section">
        <h2>4. Config Check</h2>
        <?php
        // Try to load config
        define('ROOT_PATH', dirname(__DIR__));
        define('CONFIG_PATH', ROOT_PATH . '/config');
        
        if (file_exists(CONFIG_PATH . '/config.php')) {
            require_once CONFIG_PATH . '/config.php';
            
            echo '<table>';
            echo '<tr><td>APP_URL</td><td>' . (defined('APP_URL') ? htmlspecialchars(APP_URL) : '<span class="error">NOT DEFINED</span>') . '</td></tr>';
            echo '<tr><td>ADMIN_URL</td><td>' . (defined('ADMIN_URL') ? htmlspecialchars(ADMIN_URL) : '<span class="error">NOT DEFINED</span>') . '</td></tr>';
            echo '<tr><td>APP_ENV</td><td>' . (defined('APP_ENV') ? htmlspecialchars(APP_ENV) : '<span class="error">NOT DEFINED</span>') . '</td></tr>';
            echo '</table>';
        } else {
            echo '<p class="error">✗ config.php not found</p>';
        }
        ?>
    </div>
    
    <div class="section">
        <h2>5. Recommendations</h2>
        <ul>
            <li>✓ Upload admin/.htaccess to /subdomain/admin/.htaccess</li>
            <li>✓ Upload admin/index.php to /subdomain/admin/index.php</li>
            <li>✓ Add redirect rule from root_htaccess_config.txt to /public/.htaccess (at the top)</li>
            <li>✓ Make sure cPanel subdomain "admin" document root is /public_html/subdomain/admin</li>
            <li>✓ Check error logs: cPanel → Errors → View last 300 errors</li>
        </ul>
    </div>
    
    <div class="section">
        <h2>6. Test URLs</h2>
        <p>After fixing, test these URLs:</p>
        <ol>
            <li><a href="https://sekolahzivanamontessori.sch.id/admin" target="_blank">sekolahzivanamontessori.sch.id/admin</a> → should redirect to admin subdomain</li>
            <li><a href="https://admin.sekolahzivanamontessori.sch.id" target="_blank">admin.sekolahzivanamontessori.sch.id</a> → should redirect to /admin/login</li>
            <li><a href="https://admin.sekolahzivanamontessori.sch.id/admin/login" target="_blank">admin.sekolahzivanamontessori.sch.id/admin/login</a> → should show login page</li>
        </ol>
    </div>
    
    <hr style="margin: 30px 0;">
    <p style="text-align: center; color: #6b7280;">
        Delete this file after diagnosis: /public_html/public/diagnostic_admin.php
    </p>
</body>
</html>
