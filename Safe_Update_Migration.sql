-- DISCOVER RANGSIT - SAFE UPDATE MIGRATION
-- ใช้สำหรับอัปเดตโครงสร้างโดยไม่ลบข้อมูลเดิม

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- 1. สร้างตารางใหม่ที่ยังไม่มี
CREATE TABLE IF NOT EXISTS `prefixes` (
  `prefix_id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix_name` varchar(50) NOT NULL,
  `prefix_type` enum('male','female','other') DEFAULT 'other',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`prefix_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(150) NOT NULL,
  `department_code` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `visited_at` (`visited_at`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. อัปเดตตาราง users (เพิ่มคอลัมน์ใหม่ที่ยังไม่มี)
-- หมายเหตุ: หากมีการเปลี่ยนชื่อ id เป็น user_id ต้องเช็ค FK ให้ดี
-- ในที่นี้จะเน้นเพิ่มคอลัมน์ Social และ Profile
ALTER TABLE `users` 
  ADD COLUMN IF NOT EXISTS `username` varchar(50) DEFAULT NULL AFTER `user_id`,
  ADD COLUMN IF NOT EXISTS `prefix_id` int(11) DEFAULT NULL AFTER `password`,
  ADD COLUMN IF NOT EXISTS `first_name` varchar(100) DEFAULT NULL AFTER `prefix_id`,
  ADD COLUMN IF NOT EXISTS `last_name` varchar(100) DEFAULT NULL AFTER `first_name`,
  ADD COLUMN IF NOT EXISTS `department_id` int(11) DEFAULT NULL AFTER `role`,
  ADD COLUMN IF NOT EXISTS `position` varchar(100) DEFAULT NULL AFTER `department_id`,
  ADD COLUMN IF NOT EXISTS `profile_image` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `last_login` datetime DEFAULT NULL;

-- 3. อัปเดตตาราง places (เพิ่มช่อง Social Media)
ALTER TABLE `places`
  ADD COLUMN IF NOT EXISTS `x` varchar(255) DEFAULT NULL AFTER `line`,
  ADD COLUMN IF NOT EXISTS `instagram` varchar(255) DEFAULT NULL AFTER `x`,
  ADD COLUMN IF NOT EXISTS `youtube` varchar(255) DEFAULT NULL AFTER `youtube`,
  ADD COLUMN IF NOT EXISTS `tiktok` varchar(255) DEFAULT NULL AFTER `youtube`,
  ADD COLUMN IF NOT EXISTS `line_qr` varchar(255) DEFAULT NULL AFTER `tiktok`;

-- 4. สร้าง View ใหม่ (ถ้ายังไม่มี)
CREATE OR REPLACE VIEW `v_users_full` AS 
SELECT `u`.*, `p`.`prefix_name`, `d`.`department_name`, `d`.`department_code` 
FROM `users` `u` 
LEFT JOIN `prefixes` `p` ON `u`.`prefix_id` = `p`.`prefix_id` 
LEFT JOIN `departments` `d` ON `u`.`department_id` = `d`.`department_id`;

COMMIT;
