<?php
/**
 * Application Configuration
 */

// Load environment variables
$envLoaded = false;
$envVars = [];
if (file_exists(ROOT_PATH . '/.env')) {
    // Try parse_ini_file first
    $env = parse_ini_file(ROOT_PATH . '/.env');
    
    // If parse_ini_file fails, use manual parsing (fallback for shared hosting)
    if ($env === false) {
        $content = file_get_contents(ROOT_PATH . '/.env');
        $lines = explode("\n", $content);
        $env = [];
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip empty lines and comments
            if (empty($line) || strpos($line, '#') === 0) continue;
            
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Remove quotes if any
                $value = trim($value, '"\'');
                $env[$key] = $value;
            }
        }
    }
    
    if (!empty($env)) {
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
    define('ADMIN_URL', 'https://admin.sekolahzivanamontessori.sch.id');
    define('APP_ENV', 'production');
    define('APP_DEBUG', false);
} else {
    // Use .env values or local defaults
    define('APP_NAME', $envVars['APP_NAME'] ?? 'Zivana Montessori School');
    define('APP_URL', $envVars['APP_URL'] ?? 'http://localhost');
    define('ADMIN_URL', $envVars['ADMIN_URL'] ?? 'http://localhost');
    define('APP_ENV', $envVars['APP_ENV'] ?? 'local');
    define('APP_DEBUG', filter_var($envVars['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
}

// Session configuration
define('SESSION_LIFETIME', $envVars['SESSION_LIFETIME'] ?? 7200);

// Upload configuration
define('MAX_UPLOAD_SIZE', $envVars['MAX_UPLOAD_SIZE'] ?? 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', explode(',', $envVars['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,gif,webp'));

// Cloudflare R2 Configuration
define('R2_ENABLED', filter_var($envVars['R2_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN));
define('R2_ACCESS_KEY_ID', $envVars['R2_ACCESS_KEY_ID'] ?? '');
define('R2_SECRET_ACCESS_KEY', $envVars['R2_SECRET_ACCESS_KEY'] ?? '');
define('R2_ACCOUNT_ID', $envVars['R2_ACCOUNT_ID'] ?? '');
define('R2_ENDPOINT', $envVars['R2_ENDPOINT'] ?? '');

// Public Bucket (untuk assets website yang bisa diakses publik)
define('R2_PUBLIC_BUCKET', $envVars['R2_PUBLIC_BUCKET'] ?? 'zivana-public');
define('R2_PUBLIC_URL', $envVars['R2_PUBLIC_URL'] ?? '');

// Private Bucket (untuk file internal/dokumen private)
define('R2_PRIVATE_BUCKET', $envVars['R2_PRIVATE_BUCKET'] ?? 'zivana-private');

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
