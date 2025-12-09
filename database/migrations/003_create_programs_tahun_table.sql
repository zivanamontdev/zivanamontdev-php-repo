-- Create programs_tahun table
CREATE TABLE IF NOT EXISTS programs_tahun (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO programs_tahun (name, description, image, is_active, display_order) VALUES
('Nama Program Tahun Ajaran', 'Deskripsi singkat mengenai program tahun ajaran.', '/images/image_kelas_1.png', 1, 1),
('Aktivitas Sensori', 'Aktivitas sensori membantu anak mengenal dunia melalui sentuhan, warna, suara, dan benda konkret.', '/images/image_kelas_1.png', 1, 2);
