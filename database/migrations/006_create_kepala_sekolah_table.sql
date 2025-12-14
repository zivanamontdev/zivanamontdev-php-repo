-- Create kepala_sekolah table
CREATE TABLE IF NOT EXISTS kepala_sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data
INSERT INTO kepala_sekolah (name, photo) VALUES
('Adilah Wina Fitria', '');
