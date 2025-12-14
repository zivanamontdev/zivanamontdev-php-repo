-- Create karyawan table
CREATE TABLE IF NOT EXISTS karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(100) NOT NULL,
    photo VARCHAR(255) NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert seed data
INSERT INTO karyawan (name, role, photo, sort_order) VALUES
('Siti Nurhaliza', 'Guru Kelas', '', 1),
('Budi Santoso', 'Guru Pendamping', '', 2),
('Ani Wijaya', 'Staff Administrasi', '', 3),
('Rina Kusuma', 'Guru Kelas', '', 4),
('Ahmad Fauzi', 'Staff Kebersihan', '', 5);
