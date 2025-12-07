<?php
/**
 * Update Admin Password Script
 * Run this once to fix admin password
 */

// Define ROOT_PATH
define('ROOT_PATH', dirname(__DIR__));

// Load config
require_once ROOT_PATH . '/config/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate new password hash for 'admin123'
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Update admin user
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = 'admin'");
    $stmt->execute(['password' => $newPassword]);
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Admin password updated successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        // Check if user exists
        $check = $pdo->query("SELECT id, username FROM users WHERE username = 'admin'")->fetch();
        if (!$check) {
            // Insert admin user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role, is_active) VALUES ('admin', 'admin@zivanamontessori.com', :password, 'Administrator', 'super_admin', 1)");
            $stmt->execute(['password' => $newPassword]);
            echo "✅ Admin user created successfully!\n";
            echo "Username: admin\n";
            echo "Password: admin123\n";
        } else {
            echo "⚠️ No rows updated. User exists: " . $check['username'] . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
