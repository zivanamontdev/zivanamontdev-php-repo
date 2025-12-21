<?php
/**
 * Application Configuration
 */

// Load environment variables
$envLoaded = false;
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    if ($env !== false && !empty($env)) {
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }
        $envLoaded = true;
    }
}

// Try to detect production environment
$isProduction = false;
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    // Check if running on production domain
    if (strpos($host, 'sekolahzivanamontessori.sch.id') !== false) {
        $isProduction = true;
    }
}

// Database configuration
// Use production credentials if on production domain and .env not loaded properly
if ($isProduction && (!$envLoaded || empty($_ENV['DB_USER']) || $_ENV['DB_USER'] === 'root')) {
    // Force production database config
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_NAME', 'u189792424_zivana_dev');
    define('DB_USER', 'u189792424_zivana');
    define('DB_PASS', 'Zivana04112025$');
} else {
    // Use .env values or local defaults
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'zivana_montessori');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? '');
}

// Application configuration
if ($isProduction && (!$envLoaded || empty($_ENV['APP_URL']) || strpos($_ENV['APP_URL'], 'localhost') !== false)) {
    // Force production app config
    define('APP_NAME', 'Zivana Montessori School');
    define('APP_URL', 'https://dev.sekolahzivanamontessori.sch.id');
    define('APP_ENV', 'production');
    define('APP_DEBUG', false);
} else {
    // Use .env values or local defaults
    define('APP_NAME', $_ENV['APP_NAME'] ?? 'Zivana Montessori School');
    define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'local');
    define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
}

// Session configuration
define('SESSION_LIFETIME', $_ENV['SESSION_LIFETIME'] ?? 7200);

// Upload configuration
define('MAX_UPLOAD_SIZE', $_ENV['MAX_UPLOAD_SIZE'] ?? 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', explode(',', $_ENV['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,gif,webp'));

// Security
define('CSRF_TOKEN_NAME', $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token');

// Set error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
