<?php
/**
 * Application Configuration
 */

// Load environment variables
$envLoaded = false;
$envVars = [];
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    if ($env !== false && !empty($env)) {
        foreach ($env as $key => $value) {
            // Skip commented keys (keys starting with #)
            if (strpos($key, '#') === 0) {
                continue;
            }
            $_ENV[$key] = $value;
            $envVars[$key] = $value;
        }
        $envLoaded = true;
    }
}

// Debug (temporary)
// error_log("envLoaded: " . ($envLoaded ? 'true' : 'false'));
// error_log("APP_URL from envVars: " . ($envVars['APP_URL'] ?? 'NOT SET'));

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
if ($isProduction && (!$envLoaded || empty($envVars['DB_USER']) || $envVars['DB_USER'] === 'root')) {
    // Force production database config
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_NAME', 'u189792424_zivana_dev');
    define('DB_USER', 'u189792424_zivana');
    define('DB_PASS', 'Zivana04112025$');
} else {
    // Use .env values or local defaults
    define('DB_HOST', $envVars['DB_HOST'] ?? 'localhost');
    define('DB_PORT', $envVars['DB_PORT'] ?? '3306');
    define('DB_NAME', $envVars['DB_NAME'] ?? 'zivana_montessori');
    define('DB_USER', $envVars['DB_USER'] ?? 'root');
    define('DB_PASS', $envVars['DB_PASS'] ?? '');
}

// Application configuration
if ($isProduction && (!$envLoaded || empty($envVars['APP_URL']) || strpos($envVars['APP_URL'], 'localhost') !== false)) {
    // Force production app config
    define('APP_NAME', 'Zivana Montessori School');
    define('APP_URL', 'https://dev.sekolahzivanamontessori.sch.id');
    define('APP_ENV', 'production');
    define('APP_DEBUG', false);
} else {
    // Use .env values or local defaults
    define('APP_NAME', $envVars['APP_NAME'] ?? 'Zivana Montessori School');
    define('APP_URL', $envVars['APP_URL'] ?? 'http://localhost');
    define('APP_ENV', $envVars['APP_ENV'] ?? 'local');
    define('APP_DEBUG', filter_var($envVars['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
}

// Session configuration
define('SESSION_LIFETIME', $envVars['SESSION_LIFETIME'] ?? 7200);

// Upload configuration
define('MAX_UPLOAD_SIZE', $envVars['MAX_UPLOAD_SIZE'] ?? 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', explode(',', $envVars['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,gif,webp'));

// Security
define('CSRF_TOKEN_NAME', $envVars['CSRF_TOKEN_NAME'] ?? 'csrf_token');

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
