-- Create prakata table for school introduction/foreword
CREATE TABLE IF NOT EXISTS prakata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default prakata data
INSERT INTO prakata (title, description) VALUES 
('Sejarah Singkat/Prakata Sekolah', 'TK Zivana Montessori didirikan pada tahun 2021 di bawah naungan Yayasan Zivana Insan Mandiri. TK Zivana Montessori  merupakan salah satu satuan pendidikan non formal yang terletak di daerah perkotaan. Lokasi satuan pendidikan agak jauh dari jalan raya, sehingga satuan pendidikan dan masyarakat sekitar aman dan tidak terganggu oleh hiruk pikuk dan kebisingan lalu lintas di perkotaan. Tokoh yang paling berjasa dalam lahirnya TK Zivana Montessori yakni Ibu Adilah Wina Fitria.');
