<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

$sql = "CREATE TABLE IF NOT EXISTS site_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(50),
    user_agent VARCHAR(255),
    page_url VARCHAR(255),
    user_id INT NULL,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (visited_at),
    INDEX (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    $db->query($sql);
    $db->execute();
    echo "Migration successful: Created site_visits table.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
