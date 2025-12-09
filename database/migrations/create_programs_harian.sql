-- Create programs_harian table
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create program_harian_gallery table (similar to program_gallery)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default data for 5 days
INSERT INTO programs_harian (day_name, program_name, description, display_order) VALUES
('Senin', 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', 0),
('Selasa', 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', 1),
('Rabu', 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', 2),
('Kamis', 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', 3),
('Jumat', 'Nama Program Harian', 'Deskripsi singkat mengenai program harian', 4);
