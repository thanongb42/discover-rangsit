-- DISCOVER RANGSIT - DATA RESTORE SCRIPT (V5 - AUTO-FIX STRUCTURE)
-- กู้คืนข้อมูลและแก้ไขปัญหาตารางที่หายไป (Fix Error #1146)

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- 1. สร้างตารางที่จำเป็นเพื่อให้แน่ใจว่าลบข้อมูลได้ไม่ผิดพลาด
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','staff','user') DEFAULT 'user',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('approved','pending','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `place_tags` (
  `place_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`place_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `place_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `place_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ล้างข้อมูลเดิมในตารางหลัก
DELETE FROM `place_images`;
DELETE FROM `place_tags`;
DELETE FROM `ratings`;
DELETE FROM `places`;
DELETE FROM `categories`;
DELETE FROM `users`;
DELETE FROM `departments`;
DELETE FROM `prefixes`;
DELETE FROM `roles`;

-- 3. กู้คืน Roles
INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'), (2, 'operator'), (3, 'member');

-- 4. กู้คืน Prefixes
INSERT INTO `prefixes` (`prefix_id`, `prefix_name`, `prefix_type`, `display_order`, `is_active`) VALUES
(1, 'นาย', 'male', 1, 1), (2, 'นาง', 'female', 2, 1), (3, 'นางสาว', 'female', 3, 1);

-- 5. กู้คืน Departments
INSERT INTO `departments` (`department_id`, `department_name`, `department_code`, `status`) VALUES
(1, 'กองยุทธศาสตร์และงบประมาณ', 'STRAT', 'active'),
(2, 'สำนักช่าง', 'ENG', 'active'),
(3, 'สำนักสาธารณสุข', 'HEALTH', 'active');

-- 6. กู้คืน Categories
INSERT INTO `categories` (`id`, `name`, `icon`, `color`, `created_at`) VALUES
(1, 'Restaurant', 'fa-utensils', '#ff5722', '2026-03-06 01:13:05'),
(2, 'Cafe', 'fa-coffee', '#795548', '2026-03-06 01:13:05'),
(3, 'Service', 'fa-concierge-bell', '#2196f3', '2026-03-06 01:13:05'),
(4, 'Tourism', 'fa-map-marked-alt', '#4caf50', '2026-03-06 01:13:05'),
(5, 'ของกิน อาหาร', 'fas fa-bacon', '#A5A8A4', '2026-03-06 02:13:37'),
(6, 'เสริมสวย', 'fa-user', '#07461A', '2026-03-06 02:25:09'),
(7, 'ตลาด', 'fas fa-cart-arrow-down', '#F34444', '2026-03-06 03:07:55');

-- 7. กู้คืน Users
INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `department_id`, `status`, `created_at`) VALUES
(1, 'thanong@rangsitcity.go.th', 'thanong@rangsitcity.go.th', '$2y$10$y8f0HNKadjDkdYgvCrhRb.43mTDk84MY939WTIl.aSJ1SSHmU9VVm', 'ทนงค์', 'บุญเติม', '0910109174', 'admin', 1, 'active', '2026-03-06 01:13:05'),
(2, 'kongpop@rangsitcity.go.th', 'kongpop@rangsitcity.go.th', '$2y$10$APu1jGHHB66wxIFe3kwcse.i7RuZTyeV5DeAFXxU.KMn22uhbM.wK', 'ก้องภพ', 'โชคธนอนันคต์', '0895017204', 'user', NULL, 'active', '2026-03-06 04:01:10'),
(3, 'rungnaree.s@rangsitcity.go.th', 'rungnaree.s@rangsitcity.go.th', '$2y$10$XBwhkpRhfM7cGzQyDBCd6OfO4Zc458ro4iNSSdEfC4MYXVKJMPSpy', 'รุ่งนรี', 'สุขเมือง', '', 'user', NULL, 'active', '2026-03-06 04:02:02'),
(4, 'ratcharakorn@rangsitcity.go.th', 'ratcharakorn@rangsitcity.go.th', '$2y$10$/.gEwG.re8npSM0jA0CAr.6r4Hz5frSoMKDXFtb0kdek22vjnk0ia', 'รัชรกรณ์', 'นครสุวรรณ์', '', 'user', NULL, 'active', '2026-03-06 04:03:45'),
(5, 'sopida@rangsitcity.go.th', 'sopida@rangsitcity.go.th', '$2y$10$Qzva/Uca.3SIkXdymILUROlVy9Tcc7xbTKILRRZSLS6MMdle3xsyC', 'โสภิดา', 'วังไธสง', '', 'user', NULL, 'active', '2026-03-06 04:04:58'),
(6, 'admin', 'admin@rangsit.go.th', '$2y$10$8.Z.vQ.DPtm.vEqXLoYUp.syZsqXidIdZghEpIsfSpAnmxIDclZye', 'System', 'Administrator', NULL, 'admin', NULL, 'active', '2026-03-06 04:22:57');

-- 8. กู้คืน Places
INSERT INTO `places` (`id`, `name`, `slug`, `description`, `category_id`, `address`, `latitude`, `longitude`, `phone`, `website`, `cover_image`, `status`, `views_count`, `rating_avg`, `rating_count`, `created_at`) VALUES
(1, 'ร้านก๋วยเตี๋ยวเรือ ป้าศรี', 'rangsit-boat-noodle', 'Famous authentic boat noodle near the canal.', 5, 'Rangsit Canal, Pathum Thani', 13.98857300, 100.60879200, '', '', '1772764970_cover.png', 'approved', 1505, 4.8, 120, '2026-03-06 01:13:05'),
(2, 'Future Park Rangsit', 'future-park-rangsit', 'One of the largest shopping malls in Asia.', 4, 'Phahonyothin Road, Rangsit', 13.98920000, 100.61770000, NULL, NULL, '1772765191_cover.jpg', 'approved', 5002, 4.5, 300, '2026-03-06 01:13:05'),
(3, 'อาคมบะหมี่เกี๊ยวปู', 'cozy-coffee-rangsit', 'อาคมบะหมี่เกี๊ยวปู หมู่บ้านรัตนโกสินทร์200ปี', 5, 'หมู่บ้านรัตนโกสินทร์ 200 ปี', 13.98719809, 100.60586585, '', '', '1772767058_cover.png', 'approved', 802, 4.2, 50, '2026-03-06 01:13:05'),
(4, 'ไอติมตี๋ 200 ปี', 'dream-world-thailand', 'ของกิน', 5, 'หมู่บ้านรัตนโกสินทร์ 200 ปี', 13.98832783, 100.60885047, '', '', '1772768009_cover.png', 'approved', 3500, 4.6, 250, '2026-03-06 01:13:05'),
(5, 'ตลาดรังสิต', 'rangsit-car-repair', 'ตลาดรังสิต', 7, 'ตลาดรังสิต', 13.98629794, 100.61353012, '', '', '1772768632_cover.png', 'approved', 200, 3.8, 15, '2026-03-06 01:13:05'),
(6, 'ถนนคนเดินรังสิต', 'walking-street-rangsit', 'ถนนคนเดินรังสิต | Walking Street @Rangsit) \" เดิน กิน ชิม เที่ยว \"', 4, '151 ถนนรังสิตขปทุมธานี', 13.98273300, 100.61077800, '', 'https://www.rangsitcity.go.th', '1772766803_cover.png', 'approved', 5, 0, 0, '2026-03-06 01:26:12'),
(7, 'เทศบาลนครรังสิต', 'rangsit-municipality', 'อาคารสำนักงานเทศบาลนครรังสิต', 3, '151 ถนนรังสิตขปทุมธานี', 13.98685900, 100.60957700, '025676000', 'https://www.rangsitcity.go.th', '1772760462.png', 'approved', 0, 0, 0, '2026-03-06 01:27:42');

-- 9. กู้คืน Place Images
INSERT INTO `place_images` (`id`, `place_id`, `image_path`, `created_at`) VALUES
(1, 3, '1772767551_4065.jpg', '2026-03-06 03:25:51'),
(2, 3, '1772767551_9063.jpg', '2026-03-06 03:25:51'),
(3, 3, '1772767551_2701.jpg', '2026-03-06 03:25:51'),
(4, 3, '1772767551_7072.webp', '2026-03-06 03:25:51');

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
