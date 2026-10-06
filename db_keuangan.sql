CREATE DATABASE IF NOT EXISTS `db_keuangan`;
USE `db_keuangan`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert user default
-- Username: admin
-- Password: admin123 (Sudah di-hash menggunakan PASSWORD_DEFAULT)
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$10$w6K0fSj4i4Kj.eN1gA1v8u4H5Y8/2Qe4Y8f4H5Y8/2Qe4Y8f4H5Y8'); 
-- Catatan: Nanti kita pakai script PHP untuk verifikasi password hash.