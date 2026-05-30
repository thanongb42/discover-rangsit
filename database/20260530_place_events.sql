CREATE TABLE IF NOT EXISTS `place_events` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `place_id`    INT          NOT NULL,
    `title`       VARCHAR(255) NOT NULL,
    `description` TEXT         NULL,
    `start_date`  DATE         NOT NULL,
    `end_date`    DATE         NULL,
    `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `idx_place_id`  (`place_id`),
    KEY `idx_status`    (`status`),
    KEY `idx_end_date`  (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
