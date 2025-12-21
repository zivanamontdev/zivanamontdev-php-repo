<?php
define('ROOT_PATH', dirname(__DIR__));
require __DIR__ . '/../config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?)');
    
    $result = $stmt->execute([
        'andi.rifqi',
        'andirifqialnur276@gmail.com',
        '$2y$12$yfWvGbWCAymG7NBHzqY99OibbiBDV6VcrAnmU7D298ZDB5vpmLRY.',
        'Andi Rifqi Alnur',
        'admin',
        1
    ]);
    
    if ($result) {
        echo "✓ User berhasil ditambahkan!\n";
        echo "Email: andirifqialnur276@gmail.com\n";
        echo "Password: password123\n";
    } else {
        echo "✗ Gagal menambahkan user\n";
    }
    
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "✓ User dengan email ini sudah ada di database!\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}
