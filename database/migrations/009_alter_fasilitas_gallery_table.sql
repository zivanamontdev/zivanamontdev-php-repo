-- Add description and is_cover columns to fasilitas_gallery table
ALTER TABLE fasilitas_gallery
ADD COLUMN description TEXT AFTER image_path,
ADD COLUMN is_cover TINYINT(1) DEFAULT 0 AFTER description;

-- Create index for faster queries
CREATE INDEX idx_is_cover ON fasilitas_gallery(is_cover);
