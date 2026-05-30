CREATE TABLE IF NOT EXISTS `search_logs` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `keyword`      VARCHAR(255) NOT NULL,
    `result_count` INT          NOT NULL DEFAULT 0,
    `user_id`      INT          NULL,
    `ip_address`   VARCHAR(45)  NULL,
    `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_keyword`    (`keyword`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
