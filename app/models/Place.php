<?php
class Place extends Model {
    
    public function getAllApproved() {
        $this->db->query("SELECT p.*, c.name as category_name FROM places p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'approved'");
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
            SELECT p.*, c.name as category_name, 
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
        $this->db->query("SELECT p.*, c.name as category_name FROM places p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = :slug");
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
        $this->db->query("INSERT INTO places (name, slug, description, category_id, address, latitude, longitude, phone, website, cover_image, owner_user_id, status) VALUES (:name, :slug, :description, :category_id, :address, :latitude, :longitude, :phone, :website, :cover_image, :owner_user_id, 'pending')");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':cover_image', $data['cover_image']);
        $this->db->bind(':owner_user_id', $data['owner_user_id']);
        
        return $this->db->execute();
    }

    public function getPending() {
        $this->db->query("SELECT p.*, c.name as category_name, u.name as owner_name FROM places p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          LEFT JOIN users u ON p.owner_user_id = u.id
                          WHERE p.status = 'pending' ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status) {
        $this->db->query("UPDATE places SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}