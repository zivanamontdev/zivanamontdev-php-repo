-- Testimonials table (Parent testimonials)
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_name VARCHAR(100) NOT NULL,
    child_name VARCHAR(100) NOT NULL,
    testimonial_text TEXT NOT NULL,
    highlight_text VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some default testimonials
INSERT INTO testimonials (parent_name, child_name, testimonial_text, highlight_text, display_order) VALUES
('Ibu Sarah Wijaya', 'Alya Putri', 'Anak saya sangat senang belajar di sini. Metode pengajarannya sangat baik dan guru-gurunya sangat perhatian terhadap perkembangan anak.', 'Metode pengajaran sangat baik', 1),
('Bapak Ahmad Hidayat', 'Faris Ahmad', 'Fasilitas yang lengkap dan lingkungan yang nyaman membuat anak-anak betah belajar. Perkembangan anak saya sangat pesat sejak bersekolah di sini.', 'Perkembangan anak sangat pesat', 2),
('Ibu Kartika Sari', 'Nadira Sari', 'Sangat puas dengan program pembelajaran yang ditawarkan. Anak saya menjadi lebih mandiri dan percaya diri setelah bersekolah di sini.', 'Anak menjadi lebih mandiri', 3);
