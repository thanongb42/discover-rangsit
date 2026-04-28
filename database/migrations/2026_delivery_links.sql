CREATE TABLE IF NOT EXISTS `place_delivery_links` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `place_id`      INT UNSIGNED  NOT NULL,
    `platform`      VARCHAR(32)   NOT NULL,
    `url`           VARCHAR(500)  NOT NULL,
    `display_label` VARCHAR(120)  NULL,
    `is_active`     TINYINT(1)    NOT NULL DEFAULT 1,
    `sort_order`    SMALLINT      NOT NULL DEFAULT 0,
    `click_count`   INT UNSIGNED  NOT NULL DEFAULT 0,
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_place_platform` (`place_id`, `platform`),
    KEY `idx_place` (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `place_delivery_clicks` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `place_id`   INT UNSIGNED    NOT NULL,
    `platform`   VARCHAR(32)     NOT NULL,
    `ip_hash`    CHAR(64)        NULL,
    `user_agent` VARCHAR(255)    NULL,
    `referrer`   VARCHAR(500)    NULL,
    `clicked_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_place_platform` (`place_id`, `platform`),
    KEY `idx_clicked_at` (`clicked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
