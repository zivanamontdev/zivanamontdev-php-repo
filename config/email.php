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
    'smtp_username' => '', // Email Gmail lengkap: example@gmail.com
    'smtp_password' => '', // App Password 16 karakter dari Gmail
    
    // Sender Info
    'from_email' => '', // Email pengirim (sama dengan smtp_username)
    'from_name' => APP_NAME,
    
    // Environment (auto-detect)
    'environment' => $_SERVER['SERVER_NAME'] === 'localhost' ? 'local' : 'production',
];
