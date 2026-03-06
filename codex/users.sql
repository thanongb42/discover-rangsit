-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2026 at 05:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iservice_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL COMMENT 'ชื่อผู้ใช้งาน (สำหรับ Login)',
  `password` varchar(255) NOT NULL,
  `prefix_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff','user') DEFAULT 'user',
  `department_id` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `line_user_id` varchar(50) DEFAULT NULL,
  `line_display_name` varchar(100) DEFAULT NULL,
  `line_picture_url` varchar(500) DEFAULT NULL,
  `line_linked_at` datetime DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `prefix_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `department_id`, `position`, `profile_image`, `line_user_id`, `line_display_name`, `line_picture_url`, `line_linked_at`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$y8f0HNKadjDkdYgvCrhRb.43mTDk84MY939WTIl.aSJ1SSHmU9VVm', 1, 'ผู้ดูแลระบบ', 'กลาง', 'admin@rangsit.go.th', '0910109174', 'admin', 8, 'นักวิชาการคอมพิวเตอร์ปฏิบัติการ', 'uploads/profiles/profile_1_1770177972.jpg', NULL, NULL, NULL, NULL, 'active', '2026-03-06 11:06:23', '2025-12-30 07:16:04', '2026-03-06 04:06:23'),
(2, 'piyanun', '$2y$10$myXtkKWsnWY8lVLgdrUPDuEOIop.9rICH8it/F8Yw.2aL/1qzrmOq', 3, 'ปิยนันท์', 'พูลมีทรัพย์', 'piyanun@rangsit.go.th', '0812345678', 'staff', 8, 'ผู้ช่วยนักจัดการงานทั่วไป', NULL, NULL, NULL, NULL, NULL, 'active', '2026-02-05 10:15:24', '2025-12-30 07:16:04', '2026-02-05 03:15:24'),
(3, 'sira', '$2y$10$QgWQRiT3d1wxOrxcTUK6NuyN8D41gEN1IQHnMrCKnqeLqEhKzsK1e', 1, 'ศิระ', 'แย้มคง', 'sira@rangsit.go.th', '0823456789', 'staff', 8, 'ผู้อำนวยการกองยุทธศาตร์และงบประมาณ', NULL, NULL, NULL, NULL, NULL, 'active', '2026-02-04 11:40:11', '2025-12-30 07:16:04', '2026-02-04 04:40:11'),
(4, 'Beer', '$2y$10$IwgBId6lIxzt1LUcH1HHCu8DGQZ4DME3Qh.gGtPlrvC0K6duHYfhC', 1, 'รัชรกรณ์', 'นครสุวรรณ์', 'ratcharakorn@rangsit.go.th', '0834567890', 'admin', 8, 'นักวิเคราะห์นโยบายและแผน', NULL, NULL, NULL, NULL, NULL, 'active', '2026-02-26 10:17:08', '2025-12-30 07:16:04', '2026-02-26 03:17:08'),
(7, 'thanong', '$2y$10$A22rrPN4h0apR0iUSmxx5eNSMC8DHlDywqjs0pcPLMatx/QEqAJ9K', 1, 'ทนงค์', 'บุญเติม', 'thanongb42@gmail.com', '0910109174', 'admin', 8, 'นักวิชาการคอมพิวเตอร์ปฏิบัติการ', 'uploads/profiles/profile_7_1770178210.png', NULL, NULL, NULL, NULL, 'active', '2026-02-27 07:47:17', '2025-12-30 07:17:33', '2026-02-27 00:47:17'),
(8, 'Rungnaree', '$2y$10$C4zgwdEG7n7W6o626YIOf.f.t2CC3T6TJQIBptnAPBM5CmohFxa5G', 3, 'รุ่งนรี', 'สุขเมือง', 'rungnaree.s@rangsitcity.go.th', '', 'admin', 8, 'นักส่งเสริมการพัฒนาเมือง', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-01-05 09:45:23', '2026-02-04 04:22:12'),
(9, 'Sopida', '$2y$10$UoeLR2kjRRi8rlusw5jqz.J6pYVwz5hvg3frPqgC3Jfg4kqTyQpkm', 3, 'โสภิดา', 'วังไธสง', 'sopida@rangsitcity.go.th', '', 'admin', 8, '', 'uploads/profiles/profile_9_1772076990.jpeg', NULL, NULL, NULL, NULL, 'active', '2026-02-26 11:20:08', '2026-01-05 09:45:23', '2026-02-26 04:20:08'),
(11, 'Kongpop', '$2y$10$S2.Q6mWfC2St9Mf8igyd..eISxJap/ICXO7EdlDdi02FbJEYnd.bW', 1, 'ก้องภพ', 'โชคอนัน', 'kongpop@rangsitcity.go.th', '0910109174', 'admin', 8, 'นักส่งเสริมการพัฒนาเมือง', 'uploads/profiles/profile_11_1770276649.png', NULL, NULL, NULL, NULL, 'active', '2026-02-27 16:55:48', '2026-01-05 09:57:48', '2026-02-27 09:55:48'),
(12, 'Pichapak', '$2y$10$TSPLwzkPazxUkkRROzQE6OS329BTHceQHE7mErjX.UrQT8Ps51Uqa', 3, 'พิชาภัค', 'โสภาคำ', 'porntip.pp695@gmail.com', '0936395914', 'staff', 8, 'นักประชาสัมพันธ์ปฏิบัติงาน', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-02-26 03:28:08', '2026-02-26 04:29:50'),
(13, 'arthit', '$2y$10$TqluSBfoX.PHOMUDLwYD7OND8ldQ/A/yZzIKPbtSF7YaiGg/e89Ke', 1, 'อาทิตย์', 'ทรงกฤษณ์', 'mor_mef@yahoo.com', '0863478281', 'staff', 8, 'ผู้ช่วยนักประชาสัมพันธ์', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-02-26 04:29:11', '2026-02-26 04:29:11'),
(14, 'piboon', '$2y$10$AZSWF58fxfd/CmYRq2.Rx.pDVQlGm7zqQSv4kYVkC5ymU2IAttsvq', 1, 'นายพิบูล', 'พาณิชย์เจริญรัตน์', 'Mikael.nova303@gmail.com', '0863866959', 'user', 8, 'ผู้ช่วยนักประชาสัมพันธ์', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-02-26 04:32:44', '2026-02-26 05:48:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `line_user_id` (`line_user_id`),
  ADD KEY `idx_dept` (`department_id`),
  ADD KEY `idx_prefix` (`prefix_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

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
