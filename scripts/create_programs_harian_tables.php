<?php
/**
 * Script to create programs_harian tables
 */
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance();

echo "Creating programs_harian tables...\n\n";

try {
    // Create programs_harian table
    $db->query("
        CREATE TABLE IF NOT EXISTS programs_harian (
            id INT AUTO_INCREMENT PRIMARY KEY,
            day_name VARCHAR(50) NOT NULL COMMENT 'Senin, Selasa, Rabu, Kamis, Jumat',
            program_name VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255) DEFAULT NULL,
            is_active TINYINT(1) DEFAULT 1,
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_day_name (day_name),
            INDEX idx_is_active (is_active),
            INDEX idx_display_order (display_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ programs_harian table created\n";
    
    // Create program_harian_gallery table
    $db->query("
        CREATE TABLE IF NOT EXISTS program_harian_gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            program_harian_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            description TEXT,
            is_cover TINYINT(1) DEFAULT 0 COMMENT '1 = cover/sampul',
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (program_harian_id) REFERENCES programs_harian(id) ON DELETE CASCADE,
            INDEX idx_program_harian_id (program_harian_id),
            INDEX idx_is_cover (is_cover),
            INDEX idx_display_order (display_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ program_harian_gallery table created\n";
    
    // Check if data exists
    $existing = $db->query("SELECT COUNT(*) as count FROM programs_harian")->fetch();
    
    if ($existing['count'] == 0) {
        // Insert default data for 5 days
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($days as $index => $day) {
            $db->query("
                INSERT INTO programs_harian (day_name, program_name, description, display_order) 
                VALUES (?, 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', ?)
            ", [$day, $index]);
        }
        echo "✓ Default data inserted for 5 days\n";
    } else {
        echo "⚠ Data already exists, skipping insert\n";
    }
    
    echo "\n✅ Programs Harian tables created successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
