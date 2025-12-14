-- Create fasilitas table
CREATE TABLE IF NOT EXISTS fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create fasilitas_gallery table for multiple images
CREATE TABLE IF NOT EXISTS fasilitas_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fasilitas_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fasilitas_id) REFERENCES fasilitas(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO fasilitas (name, image) VALUES
('Ruang Kelas Montessori', NULL),
('Perpustakaan', NULL),
('Playground Outdoor', NULL),
('Ruang Seni & Kreativitas', NULL);

-- Insert sample gallery images (placeholder paths)
INSERT INTO fasilitas_gallery (fasilitas_id, image_path) VALUES
(1, '/uploads/fasilitas/placeholder1.jpg'),
(1, '/uploads/fasilitas/placeholder2.jpg'),
(2, '/uploads/fasilitas/placeholder3.jpg');
