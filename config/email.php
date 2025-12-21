<?php
/**
 * Email Configuration
 * 
 * Gmail SMTP Settings
 */

return [
    // SMTP Configuration
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587, // 587 for TLS, 465 for SSL
    'smtp_encryption' => 'tls', // tls or ssl
    
    // Gmail Credentials (isi setelah setup App Password)
    'smtp_username' => $_ENV['SMTP_USERNAME'] ?? '', // Email Gmail lengkap: example@gmail.com
    'smtp_password' => $_ENV['SMTP_PASSWORD'] ?? '', // App Password 16 karakter dari Gmail
    
    // Sender Info
    'from_email' => $_ENV['SMTP_USERNAME'] ?? '', // Email pengirim (sama dengan smtp_username)
    'from_name' => APP_NAME,
    
    // Environment detection
    'environment' => defined('APP_ENV') ? APP_ENV : 'local',
    
    // Fallback to file logging if SMTP not configured
    'fallback_to_log' => true,
];
