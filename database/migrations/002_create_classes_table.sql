-- Classes table (school classes information)
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    age_range VARCHAR(100) NOT NULL,
    duration VARCHAR(100) NOT NULL,
    max_students VARCHAR(100) NOT NULL,
    image VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO classes (name, age_range, duration, max_students, image, display_order) VALUES
('Kelas Toddler', '1,5 - 3 Tahun', '2 jam', '15 anak/kelas', '/images/image_kelas_1.png', 1),
('Kelas Playgroup', '3 - 4 Tahun', '3 jam', '18 anak/kelas', '/images/image_kelas_2.png', 2),
('Kelas Kindergarten', '4 - 6 Tahun', '4 jam', '20 anak/kelas', '/images/image_kelas_3.png', 3);
