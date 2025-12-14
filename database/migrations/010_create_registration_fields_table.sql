-- Create registration_fields table
CREATE TABLE IF NOT EXISTS registration_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field_name VARCHAR(100) NOT NULL COMMENT 'Nama field untuk placeholder (ex: parentName)',
    field_label VARCHAR(255) NOT NULL COMMENT 'Label yang ditampilkan di form (ex: Nama Orang Tua)',
    field_type VARCHAR(50) NOT NULL DEFAULT 'text' COMMENT 'Type input: text, tel, number, email, textarea',
    placeholder_text VARCHAR(255) DEFAULT NULL COMMENT 'Placeholder text untuk input',
    is_required TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=optional, 1=required',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=inactive, 1=active',
    order_index INT NOT NULL DEFAULT 0 COMMENT 'Urutan tampilan field',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_field_name (field_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default fields
INSERT INTO registration_fields (field_name, field_label, field_type, placeholder_text, is_required, order_index) VALUES
('parentName', 'Nama Orang Tua', 'text', 'Isi nama orang tua', 1, 1),
('childName', 'Nama Anak', 'text', 'Isi nama anak', 1, 2),
('childAge', 'Usia Anak', 'text', 'Isi usia anak', 1, 3),
('address', 'Alamat', 'text', 'Isi alamat', 1, 4),
('phone', 'Nomor Telepon', 'tel', 'Isi nomor telepon', 1, 5);

-- Create settings table for storing WhatsApp config
CREATE TABLE IF NOT EXISTS registration_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO registration_settings (setting_key, setting_value) VALUES
('whatsapp_number', ''),
('whatsapp_template', '*PENDAFTARAN BARU - Zivana Montessori School*\n\n*Nama Anak:* {childName}\n*Nama Orang Tua:* {parentName}\n*Nomor Telepon:* {phone}\n*Alamat:* {address}\n\nTerima kasih telah mendaftar di Zivana Montessori School!');
