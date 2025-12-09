-- Create program_gallery table
CREATE TABLE IF NOT EXISTS program_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    description TEXT,
    is_cover TINYINT(1) DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs_tahun(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create index for faster queries
CREATE INDEX idx_program_id ON program_gallery(program_id);
CREATE INDEX idx_is_cover ON program_gallery(is_cover);
CREATE INDEX idx_display_order ON program_gallery(display_order);
