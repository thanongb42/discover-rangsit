<?php
class ActivityLog extends Model {
    
    public function log($data) {
        $this->db->query("INSERT INTO activity_logs (user_id, action, description, page_url, ip_address, user_agent) 
                          VALUES (:user_id, :action, :description, :page_url, :ip_address, :user_agent)");
        $this->db->bind(':user_id', $data['user_id'] ?? null);
        $this->db->bind(':action', $data['action']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':page_url', $data['page_url'] ?? $_SERVER['REQUEST_URI']);
        $this->db->bind(':ip_address', $data['ip_address'] ?? $_SERVER['REMOTE_ADDR']);
        $this->db->bind(':user_agent', $data['user_agent'] ?? $_SERVER['HTTP_USER_AGENT']);
        return $this->db->execute();
    }

    public function getAll($limit = 100) {
        $this->db->query("SELECT l.*, u.username, CONCAT(u.first_name, ' ', u.last_name) as full_name 
                          FROM activity_logs l 
                          LEFT JOIN users u ON l.user_id = u.user_id 
                          ORDER BY l.created_at DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getFiltered($filters, $limit = 50, $offset = 0) {
        $sql = "SELECT l.*, u.username, CONCAT(u.first_name, ' ', u.last_name) as full_name 
                FROM activity_logs l 
                LEFT JOIN users u ON l.user_id = u.user_id 
                WHERE 1=1";
        
        if (!empty($filters['action'])) {
            $sql .= " AND l.action = :action";
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND l.created_at >= :start_date";
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND l.created_at <= :end_date";
        }

        $sql .= " ORDER BY l.created_at DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($filters['action'])) $this->db->bind(':action', $filters['action']);
        if (!empty($filters['start_date'])) $this->db->bind(':start_date', $filters['start_date']);
        if (!empty($filters['end_date'])) $this->db->bind(':end_date', $filters['end_date']);
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    public function countFiltered($filters) {
        $sql = "SELECT COUNT(*) as total FROM activity_logs l WHERE 1=1";
        
        if (!empty($filters['action'])) {
            $sql .= " AND l.action = :action";
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND l.created_at >= :start_date";
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND l.created_at <= :end_date";
        }

        $this->db->query($sql);
        
        if (!empty($filters['action'])) $this->db->bind(':action', $filters['action']);
        if (!empty($filters['start_date'])) $this->db->bind(':start_date', $filters['start_date']);
        if (!empty($filters['end_date'])) $this->db->bind(':end_date', $filters['end_date']);
        
        return $this->db->single()->total;
    }

    public function getDistinctActions() {
        $this->db->query("SELECT DISTINCT action FROM activity_logs ORDER BY action ASC");
        return $this->db->resultSet();
    }
}