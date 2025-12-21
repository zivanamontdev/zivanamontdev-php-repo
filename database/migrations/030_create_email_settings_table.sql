-- Create email_settings table
CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT 'Zivana Montessori School',
  `is_enabled` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
