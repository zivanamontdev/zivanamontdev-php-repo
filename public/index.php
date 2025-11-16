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

// Initialize router
$router = new Router();

// Load routes
require_once ROOT_PATH . '/routes/web.php';

// Dispatch request
$router->dispatch();
