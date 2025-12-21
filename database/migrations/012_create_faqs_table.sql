-- FAQs table (Frequently Asked Questions)
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some default FAQs
INSERT INTO faqs (question, answer, display_order) VALUES
('Apa itu Metode Montessori?', 'Metode Montessori adalah pendekatan pendidikan yang dikembangkan oleh Dr. Maria Montessori, yang menekankan pada pembelajaran mandiri, eksplorasi, dan perkembangan alami anak dalam lingkungan yang telah dipersiapkan dengan baik.', 1),
('Berapa usia minimal untuk mendaftar?', 'Usia minimal untuk mendaftar di Zivana Montessori adalah 2 tahun. Kami menerima siswa mulai dari tingkat Toddler hingga Kindergarten.', 2),
('Apa saja fasilitas yang tersedia?', 'Kami menyediakan berbagai fasilitas lengkap termasuk ruang kelas yang dilengkapi dengan alat Montessori, perpustakaan, ruang bermain indoor dan outdoor, serta area khusus untuk kegiatan seni dan musik.', 3);
