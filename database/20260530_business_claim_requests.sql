CREATE TABLE IF NOT EXISTS `business_claim_requests` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `place_id`     INT NOT NULL,
    `user_id`      INT NOT NULL,
    `contact_name` VARCHAR(150) NOT NULL,
    `phone`        VARCHAR(30)  NOT NULL,
    `line_id`      VARCHAR(100) NULL,
    `message`      TEXT         NULL,
    `status`       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `reviewed_by`  INT  NULL,
    `reviewed_at`  DATETIME NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_place_user` (`place_id`, `user_id`),
    KEY `idx_status`   (`status`),
    KEY `idx_place_id` (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
