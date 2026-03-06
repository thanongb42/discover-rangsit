<?php
class Analytics extends Model {
    
    public function logVisit($data) {
        // Simple throttling: Don't log the same IP for the same page within 1 hour
        $this->db->query("SELECT id FROM site_visits 
                          WHERE ip_address = :ip AND page_url = :url 
                          AND visited_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) 
                          LIMIT 1");
        $this->db->bind(':ip', $data['ip_address']);
        $this->db->bind(':url', $data['page_url']);
        if ($this->db->single()) {
            return; // Already logged recently
        }

        $this->db->query("INSERT INTO site_visits (ip_address, user_agent, page_url, user_id) 
                          VALUES (:ip, :ua, :url, :user_id)");
        $this->db->bind(':ip', $data['ip_address']);
        $this->db->bind(':ua', $data['user_agent']);
        $this->db->bind(':url', $data['page_url']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->execute();
    }

    public function getVisitorStats($days = 30) {
        $this->db->query("SELECT DATE(visited_at) as date, COUNT(*) as page_views, COUNT(DISTINCT ip_address) as unique_visitors
                          FROM site_visits 
                          WHERE visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY) 
                          GROUP BY DATE(visited_at) 
                          ORDER BY date ASC");
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function getSummary() {
        $this->db->query("SELECT 
            (SELECT COUNT(*) FROM site_visits) as total_page_views,
            (SELECT COUNT(DISTINCT ip_address) FROM site_visits) as total_unique_visitors,
            (SELECT COUNT(*) FROM site_visits WHERE visited_at >= CURDATE()) as today_page_views,
            (SELECT COUNT(DISTINCT ip_address) FROM site_visits WHERE visited_at >= CURDATE()) as today_unique_visitors
        ");
        return $this->db->single();
    }

    public function getTopPages() {
        $this->db->query("SELECT page_url, COUNT(*) as count 
                          FROM site_visits 
                          GROUP BY page_url 
                          ORDER BY count DESC 
                          LIMIT 5");
        return $this->db->resultSet();
    }
}