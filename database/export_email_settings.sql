-- ============================================
-- EXPORT TABLE: email_settings
-- Database: zivana_montessori (Local)
-- Date: 2025-12-21
-- ============================================

-- Untuk import ke production database: u189792424_zivana_dev

-- 1. CREATE TABLE
CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT 'Zivana Montessori School',
  `is_enabled` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- CARA IMPORT KE PRODUCTION (via phpMyAdmin):
-- ============================================
-- 1. Login ke cPanel Hostinger
-- 2. Buka phpMyAdmin
-- 3. Pilih database: u189792424_zivana_dev
-- 4. Klik tab "SQL"
-- 5. Copy-paste query di atas
-- 6. Klik "Go" / "Jalankan"
-- 7. Selesai! ✅
--
-- Atau via cPanel File Manager:
-- 1. Upload file ini ke folder: public_html/dev/database/
-- 2. Akses: https://dev.sekolahzivanamontessori.sch.id/scripts/migrate.php
-- 3. Migration akan otomatis berjalan
-- ============================================
