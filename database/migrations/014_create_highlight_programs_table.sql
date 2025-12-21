-- Highlight Programs table (links to programs_tahun, max 3)
CREATE TABLE IF NOT EXISTS highlight_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_tahun_id INT NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_tahun_id) REFERENCES programs_tahun(id) ON DELETE CASCADE,
    UNIQUE KEY unique_program (program_tahun_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default highlight programs (using first 3 programs)
INSERT INTO highlight_programs (program_tahun_id, display_order)
SELECT id, ROW_NUMBER() OVER (ORDER BY display_order, id)
FROM programs_tahun
WHERE is_active = 1
LIMIT 3;
