<?php
class Place extends Model {
    
    public function getAll() {
        $this->db->query("SELECT p.*, c.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as owner_name 
                          FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          LEFT JOIN users u ON p.owner_user_id = u.user_id
                          ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT p.*, c.name as category_name FROM places p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function update($data) {
        $this->db->query("UPDATE places SET name = :name, category_id = :category_id, description = :description, address = :address, latitude = :latitude, longitude = :longitude, phone = :phone, website = :website, facebook = :facebook, line = :line, x = :x, instagram = :instagram, youtube = :youtube, tiktok = :tiktok, line_qr = :line_qr, cover_image = :cover_image, status = :status WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':facebook', $data['facebook']);
        $this->db->bind(':line', $data['line']);
        $this->db->bind(':x', $data['x']);
        $this->db->bind(':instagram', $data['instagram']);
        $this->db->bind(':youtube', $data['youtube']);
        $this->db->bind(':tiktok', $data['tiktok']);
        $this->db->bind(':line_qr', $data['line_qr']);
        $this->db->bind(':cover_image', $data['cover_image']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function updateCover($id, $filename) {
        $this->db->query("UPDATE places SET cover_image = :cover WHERE id = :id");
        $this->db->bind(':cover', $filename);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateLineQr($id, $filename) {
        $this->db->query("UPDATE places SET line_qr = :qr WHERE id = :id");
        $this->db->bind(':qr', $filename);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete($id) {
        // Delete gallery images from disk first (simplified for now)
        $this->db->query("DELETE FROM place_images WHERE place_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM places WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getGallery($place_id) {
        $this->db->query("SELECT * FROM place_images WHERE place_id = :place_id ORDER BY created_at DESC");
        $this->db->bind(':place_id', $place_id);
        return $this->db->resultSet();
    }

    public function addGalleryImage($place_id, $path) {
        $this->db->query("INSERT INTO place_images (place_id, image_path) VALUES (:place_id, :image_path)");
        $this->db->bind(':place_id', $place_id);
        $this->db->bind(':image_path', $path);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function deleteGalleryImage($image_id) {
        $this->db->query("DELETE FROM place_images WHERE id = :id");
        $this->db->bind(':id', $image_id);
        return $this->db->execute();
    }

    public function getAllApproved() {
        $this->db->query("SELECT p.*, c.name as category_name, c.icon as category_icon, c.color as category_color 
                          FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.status = 'approved'
                          ORDER BY p.rating_avg DESC, p.rating_count DESC");
        return $this->db->resultSet();
    }

    public function getCategories() {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function logSearch($keyword, $ip) {
        $this->db->query("INSERT INTO search_logs (keyword, result_count, ip_address) VALUES (:keyword, 0, :ip)");
        $this->db->bind(':keyword', $keyword);
        $this->db->bind(':ip', $ip);
        $this->db->execute();
    }

    public function getTrending() {
        // Simple trending logic based on views and rating
        $this->db->query("
            SELECT p.*, c.name as category_name, c.icon as category_icon, c.color as category_color,
            (p.views_count * 0.4 + p.rating_avg * 20 * 0.6) as trending_score
            FROM places p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'approved'
            ORDER BY trending_score DESC 
            LIMIT 10
        ");
        return $this->db->resultSet();
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId) {
            $this->db->query("SELECT id FROM places WHERE slug = :slug AND id != :id");
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query("SELECT id FROM places WHERE slug = :slug");
        }
        $this->db->bind(':slug', $slug);
        return $this->db->single() ? true : false;
    }

    public function getBySlug($slug) {
        $this->db->query("SELECT p.*, c.name as category_name, c.icon as category_icon, c.color as category_color,
                          CONCAT(u.first_name, ' ', u.last_name) as owner_name, u.profile_image as owner_avatar
                          FROM places p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN users u ON p.owner_user_id = u.user_id
                          WHERE p.slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    public function addView($place_id, $ip) {
        $this->db->query("INSERT INTO place_views (place_id, ip_address) VALUES (:place_id, :ip)");
        $this->db->bind(':place_id', $place_id);
        $this->db->bind(':ip', $ip);
        $this->db->execute();

        $this->db->query("UPDATE places SET views_count = views_count + 1 WHERE id = :id");
        $this->db->bind(':id', $place_id);
        $this->db->execute();
    }

    public function add($data) {
        $this->db->query("INSERT INTO places (name, slug, description, category_id, address, latitude, longitude, phone, website, facebook, line, x, instagram, youtube, tiktok, line_qr, cover_image, owner_user_id, status) VALUES (:name, :slug, :description, :category_id, :address, :latitude, :longitude, :phone, :website, :facebook, :line, :x, :instagram, :youtube, :tiktok, :line_qr, :cover_image, :owner_user_id, 'pending')");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':facebook', $data['facebook']);
        $this->db->bind(':line', $data['line']);
        $this->db->bind(':x', $data['x']);
        $this->db->bind(':instagram', $data['instagram']);
        $this->db->bind(':youtube', $data['youtube']);
        $this->db->bind(':tiktok', $data['tiktok']);
        $this->db->bind(':line_qr', $data['line_qr']);
        $this->db->bind(':cover_image', $data['cover_image']);
        $this->db->bind(':owner_user_id', $data['owner_user_id']);
        
        return $this->db->execute();
    }

    public function getPending() {
        $this->db->query("SELECT p.*, c.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as owner_name FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          LEFT JOIN users u ON p.owner_user_id = u.user_id
                          WHERE p.status = 'pending' ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    public function getRejected() {
        $this->db->query("SELECT p.*, c.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as owner_name FROM places p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN users u ON p.owner_user_id = u.user_id
                          WHERE p.status = 'rejected' ORDER BY p.updated_at DESC");
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status) {
        $this->db->query("UPDATE places SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function addReview($place_id, $user_id, $rating, $comment) {
        $this->db->query("INSERT INTO ratings (place_id, user_id, rating, comment) VALUES (:place_id, :user_id, :rating, :comment)");
        $this->db->bind(':place_id', $place_id);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':rating', $rating);
        $this->db->bind(':comment', $comment);
        
        if($this->db->execute()) {
            return $this->updateRatingStats($place_id);
        }
        return false;
    }

    private function updateRatingStats($place_id) {
        // Calculate new average and count
        $this->db->query("SELECT AVG(rating) as avg, COUNT(*) as total FROM ratings WHERE place_id = :id");
        $this->db->bind(':id', $place_id);
        $stats = $this->db->single();

        $this->db->query("UPDATE places SET rating_avg = :avg, rating_count = :total WHERE id = :id");
        $this->db->bind(':avg', $stats->avg);
        $this->db->bind(':total', $stats->total);
        $this->db->bind(':id', $place_id);
        return $this->db->execute();
    }

    public function getReviews($place_id) {
        $this->db->query("SELECT r.*, u.username, u.first_name, u.last_name, u.profile_image 
                          FROM ratings r 
                          JOIN users u ON r.user_id = u.user_id 
                          WHERE r.place_id = :id 
                          ORDER BY r.created_at DESC");
        $this->db->bind(':id', $place_id);
        return $this->db->resultSet();
    }

    public function getCitySummary(): object|false
    {
        $this->db->query("
            SELECT
                COUNT(*)                                                         AS total_businesses,
                SUM(status = 'approved')                                         AS approved,
                SUM(status = 'pending')                                          AS pending,
                SUM(status = 'rejected')                                         AS rejected,
                COALESCE(SUM(views_count), 0)                                    AS total_views,
                ROUND(AVG(CASE WHEN rating_count > 0 THEN rating_avg END), 2)    AS avg_rating,
                SUM(rating_count > 0)                                            AS has_reviews,
                (SELECT COUNT(*) FROM users WHERE status = 'active')             AS total_users,
                (SELECT COUNT(*) FROM place_likes)                               AS total_likes,
                (SELECT COUNT(*) FROM ratings)                                   AS total_reviews,
                (SELECT COUNT(*) FROM place_delivery_clicks)                     AS total_delivery_clicks,
                (SELECT COUNT(DISTINCT place_id) FROM place_delivery_links WHERE is_active = 1) AS delivery_shops
            FROM places
            WHERE status != 'trash'
        ");
        return $this->db->single();
    }

    public function getNewBusinessesByMonth(int $months = 6): array
    {
        $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                   DATE_FORMAT(created_at, '%b %Y')  AS label,
                   COUNT(*)                           AS count
            FROM places
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL :months MONTH)
              AND status != 'trash'
            GROUP BY month, label
            ORDER BY month ASC
        ");
        $this->db->bind(':months', $months);
        return $this->db->resultSet();
    }

    public function getTopPlacesByViews(int $limit = 10): array
    {
        $this->db->query("
            SELECT p.id, p.name, p.slug, p.cover_image, p.views_count,
                   p.rating_avg, p.rating_count,
                   c.name AS category_name
            FROM places p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'approved'
            ORDER BY p.views_count DESC
            LIMIT :lim
        ");
        $this->db->bind(':lim', $limit);
        return $this->db->resultSet();
    }

    public function getApprovedWithCoords(): array
    {
        $this->db->query("
            SELECT id, name, slug, latitude, longitude, views_count,
                   rating_avg, cover_image
            FROM places
            WHERE status = 'approved'
              AND latitude IS NOT NULL AND longitude IS NOT NULL
        ");
        return $this->db->resultSet();
    }

    public function getDeliveryAdoption(): array
    {
        $this->db->query("
            SELECT dl.platform,
                   COUNT(DISTINCT dl.place_id)  AS shop_count,
                   SUM(dl.click_count)          AS total_clicks
            FROM place_delivery_links dl
            WHERE dl.is_active = 1
            GROUP BY dl.platform
            ORDER BY shop_count DESC
        ");
        return $this->db->resultSet();
    }

    public function getEngagementByMonth(int $months = 6): array
    {
        $this->db->query("
            SELECT DATE_FORMAT(viewed_at, '%Y-%m') AS month,
                   DATE_FORMAT(viewed_at, '%b %Y')  AS label,
                   COUNT(*)                          AS views
            FROM place_views
            WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL :months MONTH)
            GROUP BY month, label
            ORDER BY month ASC
        ");
        $this->db->bind(':months', $months);
        return $this->db->resultSet();
    }

    public function getViewStats() {
        // Get views for the last 7 days
        $this->db->query("SELECT DATE(viewed_at) as date, COUNT(*) as count 
                          FROM place_views 
                          WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                          GROUP BY DATE(viewed_at) 
                          ORDER BY date ASC");
        return $this->db->resultSet();
    }

    public function getCategoryStats() {
        $this->db->query("SELECT c.id, c.name, c.icon, c.color,
                          COUNT(CASE WHEN p.status = 'approved' THEN 1 END) as approved_count,
                          COUNT(p.id) as total_count
                          FROM categories c
                          LEFT JOIN places p ON c.id = p.category_id
                          GROUP BY c.id, c.name, c.icon, c.color
                          HAVING approved_count > 0
                          ORDER BY approved_count DESC");
        return $this->db->resultSet();
    }

    public function getViewsByPlace(int $placeId, int $days = 30): array
    {
        $this->db->query("
            SELECT DATE(viewed_at) as date, COUNT(*) as views
            FROM place_views
            WHERE place_id = :place_id
              AND viewed_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(viewed_at)
            ORDER BY date ASC
        ");
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function getLikesByPlace(int $placeId, int $days = 30): array
    {
        $this->db->query("
            SELECT DATE(created_at) as date, COUNT(*) as likes
            FROM place_likes
            WHERE place_id = :place_id
              AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function getRatingDistribution(int $placeId): array
    {
        $this->db->query("
            SELECT rating, COUNT(*) as count
            FROM ratings
            WHERE place_id = :place_id
            GROUP BY rating
            ORDER BY rating DESC
        ");
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function getCategoryBenchmark(int $categoryId): object|false
    {
        $this->db->query("
            SELECT
                AVG(views_count)  as avg_views,
                AVG(rating_avg)   as avg_rating,
                AVG(rating_count) as avg_reviews
            FROM places
            WHERE category_id = :cat_id AND status = 'approved'
        ");
        $this->db->bind(':cat_id', $categoryId);
        return $this->db->single();
    }

    public function getAnalyticsSummary(int $placeId, int $days = 30): object|false
    {
        $this->db->query("
            SELECT
                p.views_count,
                p.rating_avg,
                p.rating_count,
                p.category_id,
                (SELECT COUNT(*) FROM place_likes  WHERE place_id = p.id) as like_count,
                (SELECT COUNT(*) FROM place_views  WHERE place_id = p.id AND viewed_at >= DATE_SUB(NOW(), INTERVAL :days DAY)) as views_period,
                (SELECT COUNT(*) FROM ratings      WHERE place_id = p.id AND created_at >= DATE_SUB(NOW(), INTERVAL :days2 DAY)) as reviews_period
            FROM places p
            WHERE p.id = :place_id
        ");
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':days',     $days);
        $this->db->bind(':days2',    $days);
        return $this->db->single();
    }

    public function getPlacesByOwner($owner_id) {
        $this->db->query("SELECT p.*, c.name as category_name 
                          FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.owner_user_id = :owner_id 
                          ORDER BY p.created_at DESC");
        $this->db->bind(':owner_id', $owner_id);
        return $this->db->resultSet();
    }

    public function getReviewsByUser($user_id) {
        $this->db->query("SELECT r.*, p.name as place_name, p.slug as place_slug, p.cover_image 
                          FROM ratings r 
                          JOIN places p ON r.place_id = p.id 
                          WHERE r.user_id = :user_id 
                          ORDER BY r.created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // ─── Like System ─────────────────────────────────────────────────────────

    private function ensureLikesTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS place_likes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            place_id INT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_like (place_id, user_id),
            KEY idx_place_id (place_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->execute();
    }

    public function toggleLike($place_id, $user_id) {
        $this->ensureLikesTable();

        $this->db->query("SELECT id FROM place_likes WHERE place_id = :place_id AND user_id = :user_id");
        $this->db->bind(':place_id', $place_id);
        $this->db->bind(':user_id', $user_id);
        $existing = $this->db->single();

        if ($existing) {
            $this->db->query("DELETE FROM place_likes WHERE place_id = :place_id AND user_id = :user_id");
            $this->db->bind(':place_id', $place_id);
            $this->db->bind(':user_id', $user_id);
            $this->db->execute();
            $liked = false;
        } else {
            $this->db->query("INSERT INTO place_likes (place_id, user_id) VALUES (:place_id, :user_id)");
            $this->db->bind(':place_id', $place_id);
            $this->db->bind(':user_id', $user_id);
            $this->db->execute();
            $liked = true;
        }

        $this->db->query("SELECT COUNT(*) as cnt FROM place_likes WHERE place_id = :place_id");
        $this->db->bind(':place_id', $place_id);
        $row = $this->db->single();

        return ['liked' => $liked, 'count' => (int)$row->cnt];
    }

    public function getLikeCount($place_id) {
        $this->ensureLikesTable();
        $this->db->query("SELECT COUNT(*) as cnt FROM place_likes WHERE place_id = :place_id");
        $this->db->bind(':place_id', $place_id);
        $row = $this->db->single();
        return (int)$row->cnt;
    }

    public function hasLiked($place_id, $user_id) {
        $this->ensureLikesTable();
        $this->db->query("SELECT id FROM place_likes WHERE place_id = :place_id AND user_id = :user_id");
        $this->db->bind(':place_id', $place_id);
        $this->db->bind(':user_id', $user_id);
        return (bool)$this->db->single();
    }

    public function getLikers($place_id) {
        $this->ensureLikesTable();
        $this->db->query("SELECT u.user_id, u.first_name, u.last_name, u.profile_image, u.username, pl.created_at as liked_at
                          FROM place_likes pl
                          JOIN users u ON pl.user_id = u.user_id
                          WHERE pl.place_id = :place_id
                          ORDER BY pl.created_at DESC");
        $this->db->bind(':place_id', $place_id);
        return $this->db->resultSet();
    }

    // ─── Personalization ───────────────────────────────────────────────────

    private function ensureInterestsTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS user_interests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            category_id INT NOT NULL,
            score INT DEFAULT 1,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_cat (user_id, category_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->execute();
    }

    public function trackInterest($user_id, $category_id) {
        $this->ensureInterestsTable();
        $this->db->query("INSERT INTO user_interests (user_id, category_id, score)
                          VALUES (:uid, :cid, 1)
                          ON DUPLICATE KEY UPDATE score = score + 1, updated_at = NOW()");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':cid', $category_id);
        $this->db->execute();
    }

    public function hasInterests($user_id) {
        $this->ensureInterestsTable();
        $this->db->query("SELECT COUNT(*) as cnt FROM user_interests WHERE user_id = :uid");
        $this->db->bind(':uid', $user_id);
        $row = $this->db->single();
        return (int)$row->cnt > 0;
    }

    // ─── Trash / Restore ───────────────────────────────────────────────────

    public function trash($id) {
        return $this->updateStatus($id, 'trash');
    }

    public function restore($id) {
        return $this->updateStatus($id, 'approved');
    }

    public function getTrashed() {
        $this->db->query("SELECT p.*, c.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as owner_name
                          FROM places p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN users u ON p.owner_user_id = u.user_id
                          WHERE p.status = 'trash'
                          ORDER BY p.updated_at DESC");
        return $this->db->resultSet();
    }

    public function getOwnerUserId($id) {
        $this->db->query("SELECT owner_user_id FROM places WHERE id = :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row ? (int)$row->owner_user_id : null;
    }

    public function getAlsoViewed(int $placeId, int $limit = 6): array
    {
        $this->db->query("
            SELECT p.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color,
                   COUNT(pv2.place_id) AS co_views
            FROM place_views pv1
            JOIN place_views pv2
                ON pv1.ip_address = pv2.ip_address
               AND pv2.place_id  != :pid
               AND pv2.viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            JOIN places p     ON pv2.place_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE pv1.place_id  = :pid2
              AND pv1.viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              AND p.status = 'approved'
            GROUP BY p.id
            ORDER BY co_views DESC, p.rating_avg DESC
            LIMIT :lim
        ");
        $this->db->bind(':pid',  $placeId);
        $this->db->bind(':pid2', $placeId);
        $this->db->bind(':lim',  $limit);
        return $this->db->resultSet();
    }

    public function getHotNow(int $limit = 8, int $hours = 48): array
    {
        $this->db->query("
            SELECT p.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color,
                   COUNT(pv.id) AS recent_views
            FROM places p
            JOIN place_views pv ON p.id = pv.place_id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'approved'
              AND pv.viewed_at >= DATE_SUB(NOW(), INTERVAL :hrs HOUR)
            GROUP BY p.id
            ORDER BY recent_views DESC, p.rating_avg DESC
            LIMIT :lim
        ");
        $this->db->bind(':hrs', $hours);
        $this->db->bind(':lim', $limit);
        return $this->db->resultSet();
    }

    public function getSimilarByCategory(int $placeId, int $categoryId, int $limit = 6): array
    {
        $this->db->query("
            SELECT p.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color
            FROM places p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'approved'
              AND p.category_id = :cat_id
              AND p.id != :pid
            ORDER BY p.rating_avg DESC, p.views_count DESC
            LIMIT :lim
        ");
        $this->db->bind(':cat_id', $categoryId);
        $this->db->bind(':pid',    $placeId);
        $this->db->bind(':lim',    $limit);
        return $this->db->resultSet();
    }

    public function getPersonalizedScore(int $userId, int $limit = 8): array
    {
        $this->ensureInterestsTable();
        $this->db->query("
            SELECT p.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color,
                   COALESCE(ui.score, 0)                             AS interest_score,
                   COALESCE(pl.liked, 0)                            AS user_liked,
                   (COALESCE(ui.score, 0) * 3
                    + COALESCE(pl.liked, 0) * 5
                    + (p.rating_avg * 2)
                    + LOG(p.views_count + 1))                        AS ai_score
            FROM places p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN user_interests ui
                ON ui.category_id = p.category_id AND ui.user_id = :uid
            LEFT JOIN (SELECT place_id, 1 AS liked FROM place_likes WHERE user_id = :uid2) pl
                ON pl.place_id = p.id
            WHERE p.status = 'approved'
              AND p.id NOT IN (
                  SELECT DISTINCT place_id FROM place_views WHERE ip_address IN (
                      SELECT ip_address FROM place_views pv2
                      JOIN users u ON u.user_id = :uid3
                      WHERE pv2.viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                      LIMIT 5
                  ) AND viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
              )
            ORDER BY ai_score DESC
            LIMIT :lim
        ");
        $this->db->bind(':uid',  $userId);
        $this->db->bind(':uid2', $userId);
        $this->db->bind(':uid3', $userId);
        $this->db->bind(':lim',  $limit);
        return $this->db->resultSet();
    }

    public function getRecommendations($user_id, $limit = 6) {
        $this->ensureInterestsTable();
        // Use derived table instead of LIMIT inside IN() — MySQL doesn't support LIMIT in subqueries with IN
        $this->db->query("
            SELECT p.*, c.name as category_name, c.icon as category_icon, c.color as category_color
            FROM places p
            LEFT JOIN categories c ON p.category_id = c.id
            INNER JOIN (
                SELECT category_id FROM user_interests
                WHERE user_id = :uid
                ORDER BY score DESC LIMIT 3
            ) top_cats ON p.category_id = top_cats.category_id
            WHERE p.status = 'approved'
            ORDER BY p.rating_avg DESC, p.views_count DESC
            LIMIT :lim
        ");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':lim', (int)$limit);
        return $this->db->resultSet();
    }
}