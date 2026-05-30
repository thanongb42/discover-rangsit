<?php
class Claim extends Model {

    public function create($data) {
        $this->db->query("
            INSERT INTO business_claim_requests
                (place_id, user_id, contact_name, phone, line_id, message)
            VALUES
                (:place_id, :user_id, :contact_name, :phone, :line_id, :message)
        ");
        $this->db->bind(':place_id',     $data['place_id']);
        $this->db->bind(':user_id',      $data['user_id']);
        $this->db->bind(':contact_name', $data['contact_name']);
        $this->db->bind(':phone',        $data['phone']);
        $this->db->bind(':line_id',      $data['line_id'] ?: null);
        $this->db->bind(':message',      $data['message'] ?: null);
        return $this->db->execute();
    }

    public function getByPlaceAndUser($placeId, $userId) {
        $this->db->query("
            SELECT * FROM business_claim_requests
            WHERE place_id = :place_id AND user_id = :user_id
            LIMIT 1
        ");
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':user_id',  $userId);
        return $this->db->single();
    }

    public function getAll() {
        $this->db->query("
            SELECT r.*,
                   p.name AS place_name, p.slug AS place_slug, p.cover_image,
                   p.owner_user_id,
                   CONCAT(u.first_name,' ',u.last_name) AS user_name,
                   u.profile_image AS user_avatar,
                   CONCAT(a.first_name,' ',a.last_name) AS reviewer_name
            FROM business_claim_requests r
            JOIN places p ON r.place_id = p.id
            JOIN users  u ON r.user_id  = u.user_id
            LEFT JOIN users a ON r.reviewed_by = a.user_id
            ORDER BY FIELD(r.status,'pending','rejected','approved'), r.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("
            SELECT r.*, p.name AS place_name, p.owner_user_id
            FROM business_claim_requests r
            JOIN places p ON r.place_id = p.id
            WHERE r.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function approve($id, $adminId) {
        $this->db->query("
            UPDATE business_claim_requests
            SET status = 'approved', reviewed_by = :admin_id, reviewed_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':admin_id', $adminId);
        $this->db->bind(':id',       $id);
        return $this->db->execute();
    }

    public function reject($id, $adminId) {
        $this->db->query("
            UPDATE business_claim_requests
            SET status = 'rejected', reviewed_by = :admin_id, reviewed_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':admin_id', $adminId);
        $this->db->bind(':id',       $id);
        return $this->db->execute();
    }

    public function countPending() {
        $this->db->query("SELECT COUNT(*) AS cnt FROM business_claim_requests WHERE status = 'pending'");
        $row = $this->db->single();
        return (int)($row->cnt ?? 0);
    }
}
