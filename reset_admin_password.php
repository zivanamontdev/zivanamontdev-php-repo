<?php
/**
 * Script untuk reset password admin
 * Akses via: https://yourdomain.com/reset_admin_password.php
 * HAPUS FILE INI setelah selesai digunakan!
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

// Email admin yang akan direset
$adminEmail = 'andirifqialnur276@gmail.com';
$newPassword = 'admin123'; // Ganti dengan password baru yang diinginkan

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Hash password baru
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hashedPassword, $adminEmail]);
    
    if ($stmt->rowCount() > 0) {
        echo "<h2>✅ Password berhasil direset!</h2>";
        echo "<p><strong>Email:</strong> {$adminEmail}</p>";
        echo "<p><strong>Password baru:</strong> {$newPassword}</p>";
        echo "<p><strong>Hash:</strong> {$hashedPassword}</p>";
        echo "<hr>";
        echo "<p style='color: red;'><strong>PENTING: Hapus file ini setelah selesai!</strong></p>";
    } else {
        echo "<h2>❌ User tidak ditemukan!</h2>";
        echo "<p>Email: {$adminEmail} tidak ada di database</p>";
        
        // Tampilkan semua user yang ada
        $stmt = $conn->query("SELECT id, name, email, role FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>User yang tersedia:</h3>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
