<?php
/**
 * Zivana Montessori School Website
 * Front Controller
 */

// CRITICAL: Block admin routes on main domain IMMEDIATELY before anything else
$host = $_SERVER['HTTP_HOST'] ?? '';
$uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($uri, PHP_URL_PATH);

// Check if NOT admin subdomain AND accessing admin route
$isAdminSubdomain = (strpos($host, 'admin.') === 0 || defined('IS_ADMIN_SUBDOMAIN'));
$isAdminRoute = (strpos($path, '/admin') === 0);

if (!$isAdminSubdomain && $isAdminRoute) {
    // Immediately return 404 without any processing
    http_response_code(404);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; text-align: center; padding: 100px 20px; background: #f5f5f5; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { color: #C92C2F; font-size: 96px; margin: 0; font-weight: 700; }
        h2 { color: #333; font-size: 24px; margin: 20px 0; }
        p { color: #666; font-size: 16px; line-height: 1.6; }
        a { display: inline-block; margin-top: 20px; padding: 12px 30px; background: #C92C2F; color: white; text-decoration: none; border-radius: 5px; transition: background 0.3s; }
        a:hover { background: #a52426; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Maaf, halaman yang Anda cari tidak dapat ditemukan atau tidak tersedia.</p>
        <a href="/">← Kembali ke Beranda</a>
    </div>
</body>
</html>';
    exit;
}

// Start session
session_start();

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEW_PATH', APP_PATH . '/views');
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/core/',
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/middleware/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load helpers
require_once APP_PATH . '/helpers/functions.php';
require_once APP_PATH . '/helpers/geoip.php';

// Global error handler for security (prevent 500 on admin routes)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    
    // Check if this is admin route access attempt
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $path = parse_url($uri, PHP_URL_PATH);
    $path = rtrim($path, '/');
    
    if (strpos($path, '/admin') === 0) {
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>404</title></head><body><h1>404 - Halaman Tidak Ditemukan</h1><p><a href="/">Kembali ke Beranda</a></p></body></html>';
        exit;
    }
    
    return false; // Let PHP default error handler run
});

// Check subdomain and handle restrictions
if (class_exists('SubdomainMiddleware')) {
    SubdomainMiddleware::handle();
}

// Initialize router
$router = new Router();

// Load routes
require_once ROOT_PATH . '/routes/web.php';

// Error handling
try {
    // Dispatch request
    $router->dispatch();
} catch (Exception $e) {
    // Log error
    error_log("Application Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // SECURITY: Check if this is admin route - always return 404, never 500
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $path = parse_url($uri, PHP_URL_PATH);
    $path = rtrim($path, '/');
    
    if (strpos($path, '/admin') === 0) {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>404</title><style>body{font-family:sans-serif;text-align:center;padding:50px;background:#f5f5f5;}h1{color:#C92C2F;font-size:72px;margin:0;}p{color:#666;font-size:18px;}a{color:#C92C2F;text-decoration:none;}</style></head><body><h1>404</h1><p>Halaman yang Anda cari tidak ditemukan.</p><p><a href="/">← Kembali ke Beranda</a></p></body></html>';
        exit;
    }
    
    // Check if it's a not found error or server error
    if ($e->getCode() == 404 || strpos($e->getMessage(), '404') !== false || strpos($e->getMessage(), 'not found') !== false) {
        http_response_code(404);
        if (file_exists(VIEW_PATH . '/errors/404.php')) {
            require VIEW_PATH . '/errors/404.php';
        } else {
            echo "<h1>404 - Page Not Found</h1>";
        }
    } else {
        // Show generic error page in production
        http_response_code(500);
        if (file_exists(VIEW_PATH . '/errors/500.php')) {
            require VIEW_PATH . '/errors/500.php';
        } else {
            if (APP_ENV === 'development') {
                echo "<h1>500 - Server Error</h1>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            } else {
                echo "<h1>500 - Something went wrong</h1>";
                echo "<p>We're sorry, but something went wrong. Please try again later.</p>";
            }
        }
    }
}
