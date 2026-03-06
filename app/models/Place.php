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
        return $this->db->execute();
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

    public function getBySlug($slug) {
        $this->db->query("SELECT p.*, c.name as category_name, c.icon as category_icon, c.color as category_color 
                          FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
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
        $this->db->query("SELECT c.name, COUNT(p.id) as count 
                          FROM categories c 
                          LEFT JOIN places p ON c.id = p.category_id 
                          GROUP BY c.id");
        return $this->db->resultSet();
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
}