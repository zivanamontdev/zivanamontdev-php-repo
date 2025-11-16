<?php
/**
 * Application Configuration
 */

// Load environment variables
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'zivana_montessori');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Application configuration
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Zivana Montessori School');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'local');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));

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
