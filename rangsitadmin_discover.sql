-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2026 at 01:11 PM
-- Server version: 10.11.16-MariaDB-ubu2204
-- PHP Version: 8.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rangsitadmin_discover`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `color`, `created_at`) VALUES
(1, 'Restaurant', 'fa-utensils', '#ff5722', '2026-03-06 01:13:05'),
(2, 'Cafe', 'fa-coffee', '#795548', '2026-03-06 01:13:05'),
(3, 'Service', 'fa-concierge-bell', '#2196f3', '2026-03-06 01:13:05'),
(4, 'Tourism', 'fa-map-marked-alt', '#4caf50', '2026-03-06 01:13:05'),
(5, 'ของกิน อาหาร', 'fas fa-bacon', '#A5A8A4', '2026-03-06 02:13:37'),
(6, 'เสริมสวย', 'fa-user', '#07461A', '2026-03-06 02:25:09'),
(7, 'ตลาด', 'fas fa-cart-arrow-down', '#F34444', '2026-03-06 03:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) NOT NULL,
  `department_code` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_code`, `status`) VALUES
(1, 'กองยุทธศาสตร์และงบประมาณ', 'STRAT', 'active'),
(2, 'สำนักช่าง', 'ENG', 'active'),
(3, 'สำนักสาธารณสุข', 'HEALTH', 'active'),
(4, 'กองยุทธศาสตร์และงบประมาณ', 'STRAT', 'active'),
(5, 'สำนักช่าง', 'ENG', 'active'),
(6, 'สำนักสาธารณสุข', 'HEALTH', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `line` varchar(100) DEFAULT NULL,
  `x` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `line_qr` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `views_count` int(11) DEFAULT 0,
  `rating_avg` float DEFAULT 0,
  `rating_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `slug`, `description`, `category_id`, `address`, `district`, `province`, `latitude`, `longitude`, `phone`, `website`, `facebook`, `line`, `x`, `instagram`, `youtube`, `tiktok`, `line_qr`, `cover_image`, `owner_user_id`, `status`, `views_count`, `rating_avg`, `rating_count`, `created_at`, `updated_at`) VALUES
(1, 'ร้านก๋วยเตี๋ยวเรือ ป้าศรี', 'rangsit-boat-noodle', 'Famous authentic boat noodle near the canal.', 5, 'Rangsit Canal, Pathum Thani', NULL, NULL, 13.98857300, 100.60879200, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1772764970_cover.png', NULL, 'approved', 1505, 4.8, 120, '2026-03-06 01:13:05', NULL),
(2, 'Future Park Rangsit', 'future-park-rangsit', 'One of the largest shopping malls in Asia.', 4, 'Phahonyothin Road, Rangsit', NULL, NULL, 13.98920000, 100.61770000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1772765191_cover.jpg', NULL, 'approved', 5002, 4.5, 300, '2026-03-06 01:13:05', NULL),
(3, 'อาคมบะหมี่เกี๊ยวปู', 'cozy-coffee-rangsit', 'อาคมบะหมี่เกี๊ยวปู หมู่บ้านรัตนโกสินทร์200ปี', 5, 'หมู่บ้านรัตนโกสินทร์ 200 ปี', NULL, NULL, 13.98719809, 100.60586585, '', '', '', '', '', '', '', '', NULL, '1772767058_cover.png', NULL, 'approved', 802, 4.2, 50, '2026-03-06 01:13:05', NULL),
(4, 'ไอติมตี๋ 200 ปี', 'dream-world-thailand', 'ของกิน', 5, 'หมู่บ้านรัตนโกสินทร์ 200 ปี', NULL, NULL, 13.98832783, 100.60885047, '', '', '', '', '', '', '', '', NULL, '1772768009_cover.png', NULL, 'approved', 3500, 4.6, 250, '2026-03-06 01:13:05', NULL),
(5, 'ตลาดรังสิต', 'rangsit-car-repair', 'ตลาดรังสิต', 7, 'ตลาดรังสิต', NULL, NULL, 13.98629794, 100.61353012, '', '', '', '', '', '', '', '', NULL, '1772768632_cover.png', NULL, 'approved', 200, 3.8, 15, '2026-03-06 01:13:05', NULL),
(6, 'ถนนคนเดินรังสิต', '-', 'ถนนคนเดินรังสิต | Walking Street @Rangsit) \" เดิน กิน ชิม เที่ยว \" 🚶‍♂️🚶‍♀️🚶', 4, '151 ถนนรังสิตขปทุมธานี', NULL, NULL, 13.98273300, 100.61077800, '', 'https://www.rangsitcity.go.th', '', '', '', '', '', '', NULL, '1772766803_cover.png', 1, 'approved', 5, 0, 0, '2026-03-06 01:26:12', NULL),
(7, 'เทศบาลนครรังสิต', '-', 'อาคารสำนักงานเทศบาลนครรังสิต', 3, '151 ถนนรังสิตขปทุมธานี', NULL, NULL, 13.98685900, 100.60957700, '025676000', 'https://www.rangsitcity.go.th', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1772760462.png', 1, 'approved', 0, 0, 0, '2026-03-06 01:27:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `place_images`
--

CREATE TABLE `place_images` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_cover` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `place_images`
--

INSERT INTO `place_images` (`id`, `place_id`, `image_path`, `is_cover`, `created_at`) VALUES
(1, 3, '1772767551_4065.jpg', 0, '2026-03-06 03:25:51'),
(2, 3, '1772767551_9063.jpg', 0, '2026-03-06 03:25:51'),
(3, 3, '1772767551_2701.jpg', 0, '2026-03-06 03:25:51'),
(4, 3, '1772767551_7072.webp', 0, '2026-03-06 03:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `place_tags`
--

CREATE TABLE `place_tags` (
  `place_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `place_views`
--

CREATE TABLE `place_views` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `place_views`
--

INSERT INTO `place_views` (`id`, `place_id`, `user_id`, `ip_address`, `viewed_at`) VALUES
(1, 3, NULL, '::1', '2026-03-06 03:26:31'),
(2, 1, NULL, '::1', '2026-03-06 03:54:48'),
(3, 2, NULL, '::1', '2026-03-06 03:55:06'),
(4, 6, NULL, '::1', '2026-03-06 03:58:29'),
(5, 6, NULL, '::1', '2026-03-06 03:58:41'),
(6, 2, NULL, '::1', '2026-03-06 04:18:12'),
(7, 1, NULL, '118.174.138.142', '2026-03-06 05:17:10'),
(8, 1, NULL, '118.174.138.142', '2026-03-06 05:37:15'),
(9, 1, NULL, '118.174.138.142', '2026-03-06 05:37:20'),
(10, 6, NULL, '118.174.138.142', '2026-03-06 05:44:41'),
(11, 6, NULL, '118.174.138.142', '2026-03-06 05:47:25'),
(12, 3, NULL, '118.174.138.142', '2026-03-06 05:47:39'),
(13, 1, NULL, '118.174.138.142', '2026-03-06 05:52:49'),
(14, 6, NULL, '118.174.138.142', '2026-03-06 05:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `prefixes`
--

CREATE TABLE `prefixes` (
  `prefix_id` int(11) NOT NULL,
  `prefix_name` varchar(50) NOT NULL,
  `prefix_type` enum('male','female','other') DEFAULT 'other',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prefixes`
--

INSERT INTO `prefixes` (`prefix_id`, `prefix_name`, `prefix_type`, `display_order`, `is_active`) VALUES
(1, 'นาย', 'male', 1, 1),
(2, 'นาง', 'female', 2, 1),
(3, 'นางสาว', 'female', 3, 1),
(4, 'นาย', 'male', 1, 1),
(5, 'นาง', 'female', 2, 1),
(6, 'นางสาว', 'female', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'operator'),
(3, 'member'),
(4, 'admin'),
(5, 'operator'),
(6, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `search_logs`
--

CREATE TABLE `search_logs` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `result_count` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `searched_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `search_logs`
--

INSERT INTO `search_logs` (`id`, `keyword`, `result_count`, `ip_address`, `searched_at`) VALUES
(1, 'เทศบาล', 0, '::1', '2026-03-06 02:33:50'),
(2, 'เทศบาล', 0, '::1', '2026-03-06 02:33:58'),
(3, 'เทศบาล', 0, '::1', '2026-03-06 02:34:00'),
(4, 'เทศบาล', 0, '::1', '2026-03-06 02:34:02'),
(5, 'เทศบาล', 0, '::1', '2026-03-06 02:34:05'),
(6, 'เทศบาล', 0, '::1', '2026-03-06 02:34:06'),
(7, 'เทศบาล', 0, '::1', '2026-03-06 02:34:08'),
(8, 'เทศบาล', 0, '::1', '2026-03-06 02:35:12'),
(9, 'เทศบาล', 0, '::1', '2026-03-06 02:35:13'),
(10, 'เทศบาล', 0, '::1', '2026-03-06 02:35:15'),
(11, 'เทศบาล', 0, '::1', '2026-03-06 02:35:16'),
(12, 'เทศบาล', 0, '::1', '2026-03-06 02:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `prefix_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` enum('admin','staff','user') DEFAULT 'user',
  `department_id` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `line_user_id` varchar(50) DEFAULT NULL,
  `line_display_name` varchar(100) DEFAULT NULL,
  `line_picture_url` varchar(500) DEFAULT NULL,
  `line_linked_at` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `name`, `email`, `password`, `prefix_id`, `first_name`, `last_name`, `phone`, `role`, `department_id`, `position`, `profile_image`, `line_user_id`, `line_display_name`, `line_picture_url`, `line_linked_at`, `avatar`, `role_id`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'thanong@rangsitcity.go.th', 'Admin User', 'thanong@rangsitcity.go.th', '$2y$10$y8f0HNKadjDkdYgvCrhRb.43mTDk84MY939WTIl.aSJ1SSHmU9VVm', NULL, 'ทนงค์', 'บุญเติม', '0910109174', 'admin', 1, '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'active', '2026-03-06 11:23:12', '2026-03-06 01:13:05', NULL),
(2, 'kongpop@rangsitcity.go.th', 'ก้องภพ โชคธนอนันคต์', 'kongpop@rangsitcity.go.th', '$2y$10$APu1jGHHB66wxIFe3kwcse.i7RuZTyeV5DeAFXxU.KMn22uhbM.wK', NULL, 'ก้องภพ โชคธนอนันคต์', NULL, '0895017204', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'active', NULL, '2026-03-06 04:01:10', NULL),
(3, 'rungnaree.s@rangsitcity.go.th', 'รุ่งนรี สุขเมือง', 'rungnaree.s@rangsitcity.go.th', '$2y$10$XBwhkpRhfM7cGzQyDBCd6OfO4Zc458ro4iNSSdEfC4MYXVKJMPSpy', NULL, 'รุ่งนรี สุขเมือง', NULL, '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'active', NULL, '2026-03-06 04:02:02', NULL),
(4, 'ratcharakorn@rangsitcity.go.th', 'รัชรกรณ์ นครสุวรรณ์', 'ratcharakorn@rangsitcity.go.th', '$2y$10$/.gEwG.re8npSM0jA0CAr.6r4Hz5frSoMKDXFtb0kdek22vjnk0ia', NULL, 'รัชรกรณ์ นครสุวรรณ์', NULL, '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'active', NULL, '2026-03-06 04:03:45', NULL),
(5, 'sopida@rangsitcity.go.th', 'โสภิดา วังไธสง', 'sopida@rangsitcity.go.th', '$2y$10$Qzva/Uca.3SIkXdymILUROlVy9Tcc7xbTKILRRZSLS6MMdle3xsyC', NULL, 'โสภิดา วังไธสง', NULL, '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'active', NULL, '2026-03-06 04:04:58', NULL),
(6, 'admin', NULL, 'admin@rangsit.go.th', '\\.Z.vQ.DPtm.vEqXLoYUp.syZsqXidIdZghEpIsfSpAnmxIDclZye', NULL, 'System', 'Administrator', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-03-06 04:22:57', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_users_full`
-- (See below for the actual view)
--
CREATE TABLE `v_users_full` (
`user_id` int(11)
,`username` varchar(50)
,`name` varchar(150)
,`email` varchar(150)
,`password` varchar(255)
,`prefix_id` int(11)
,`first_name` varchar(100)
,`last_name` varchar(100)
,`phone` varchar(50)
,`role` enum('admin','staff','user')
,`department_id` int(11)
,`position` varchar(100)
,`profile_image` varchar(255)
,`line_user_id` varchar(50)
,`line_display_name` varchar(100)
,`line_picture_url` varchar(500)
,`line_linked_at` datetime
,`avatar` varchar(255)
,`role_id` int(11)
,`status` enum('active','inactive','suspended')
,`last_login` datetime
,`created_at` timestamp
,`updated_at` timestamp
,`prefix_name` varchar(50)
,`department_name` varchar(150)
,`department_code` varchar(20)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `owner_user_id` (`owner_user_id`);

--
-- Indexes for table `place_images`
--
ALTER TABLE `place_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `place_tags`
--
ALTER TABLE `place_tags`
  ADD PRIMARY KEY (`place_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `place_views`
--
ALTER TABLE `place_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `prefixes`
--
ALTER TABLE `prefixes`
  ADD PRIMARY KEY (`prefix_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_logs`
--
ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `fk_user_dept` (`department_id`),
  ADD KEY `fk_user_prefix` (`prefix_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `place_images`
--
ALTER TABLE `place_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `place_views`
--
ALTER TABLE `place_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `prefixes`
--
ALTER TABLE `prefixes`
  MODIFY `prefix_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `search_logs`
--
ALTER TABLE `search_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- --------------------------------------------------------

--
-- Structure for view `v_users_full`
--
DROP TABLE IF EXISTS `v_users_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`rangsitadmin`@`localhost` SQL SECURITY DEFINER VIEW `v_users_full`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`password` AS `password`, `u`.`prefix_id` AS `prefix_id`, `u`.`first_name` AS `first_name`, `u`.`last_name` AS `last_name`, `u`.`phone` AS `phone`, `u`.`role` AS `role`, `u`.`department_id` AS `department_id`, `u`.`position` AS `position`, `u`.`profile_image` AS `profile_image`, `u`.`line_user_id` AS `line_user_id`, `u`.`line_display_name` AS `line_display_name`, `u`.`line_picture_url` AS `line_picture_url`, `u`.`line_linked_at` AS `line_linked_at`, `u`.`avatar` AS `avatar`, `u`.`role_id` AS `role_id`, `u`.`status` AS `status`, `u`.`last_login` AS `last_login`, `u`.`created_at` AS `created_at`, `u`.`updated_at` AS `updated_at`, `p`.`prefix_name` AS `prefix_name`, `d`.`department_name` AS `department_name`, `d`.`department_code` AS `department_code` FROM ((`users` `u` left join `prefixes` `p` on(`u`.`prefix_id` = `p`.`prefix_id`)) left join `departments` `d` on(`u`.`department_id` = `d`.`department_id`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `places_ibfk_2` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `place_images`
--
ALTER TABLE `place_images`
  ADD CONSTRAINT `place_images_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`);

--
-- Constraints for table `place_tags`
--
ALTER TABLE `place_tags`
  ADD CONSTRAINT `place_tags_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`),
  ADD CONSTRAINT `place_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

--
-- Constraints for table `place_views`
--
ALTER TABLE `place_views`
  ADD CONSTRAINT `place_views_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_prefix` FOREIGN KEY (`prefix_id`) REFERENCES `prefixes` (`prefix_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
