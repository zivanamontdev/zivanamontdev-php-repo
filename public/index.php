<?php
/**
 * Zivana Montessori School Website
 * Front Controller
 */

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
