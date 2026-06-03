<?php
class PlaceEvent extends Model {

    public function getByPlace(int $placeId): array {
        $this->db->query("
            SELECT * FROM place_events
            WHERE place_id = :place_id
            ORDER BY start_date DESC
        ");
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function getActiveByPlace(int $placeId): array {
        $this->db->query("
            SELECT * FROM place_events
            WHERE place_id = :place_id
              AND status = 'active'
              AND (end_date IS NULL OR end_date >= CURDATE())
            ORDER BY start_date ASC
        ");
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function getById(int $id): object|false {
        $this->db->query("SELECT * FROM place_events WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create(array $data): bool {
        $this->db->query("
            INSERT INTO place_events (place_id, title, description, start_date, end_date, status)
            VALUES (:place_id, :title, :description, :start_date, :end_date, :status)
        ");
        $this->db->bind(':place_id',    $data['place_id']);
        $this->db->bind(':title',       $data['title']);
        $this->db->bind(':description', $data['description'] ?: null);
        $this->db->bind(':start_date',  $data['start_date']);
        $this->db->bind(':end_date',    $data['end_date'] ?: null);
        $this->db->bind(':status',      $data['status'] ?? 'active');
        return $this->db->execute();
    }

    public function update(array $data): bool {
        $this->db->query("
            UPDATE place_events
            SET title = :title, description = :description,
                start_date = :start_date, end_date = :end_date, status = :status
            WHERE id = :id
        ");
        $this->db->bind(':title',       $data['title']);
        $this->db->bind(':description', $data['description'] ?: null);
        $this->db->bind(':start_date',  $data['start_date']);
        $this->db->bind(':end_date',    $data['end_date'] ?: null);
        $this->db->bind(':status',      $data['status'] ?? 'active');
        $this->db->bind(':id',          $data['id']);
        return $this->db->execute();
    }

    public function delete(int $id): bool {
        $this->db->query("DELETE FROM place_events WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getOwnerPlaceId(int $eventId): ?int {
        $this->db->query("SELECT place_id FROM place_events WHERE id = :id");
        $this->db->bind(':id', $eventId);
        $row = $this->db->single();
        return $row ? (int)$row->place_id : null;
    }

    public function getAllWithPlace(): array {
        $this->db->query("
            SELECT e.*, p.name AS place_name, p.slug AS place_slug
            FROM place_events e
            JOIN places p ON e.place_id = p.id
            ORDER BY e.status ASC, e.start_date DESC
        ");
        return $this->db->resultSet();
    }

    public function getUpcomingPublic(int $limit = 100): array {
        $this->db->query("
            SELECT
                e.*, p.name AS place_name, p.slug AS place_slug,
                p.cover_image, p.address, p.latitude, p.longitude,
                c.name AS category_name, c.color AS category_color
            FROM place_events e
            JOIN places p ON e.place_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE e.status = 'active'
              AND p.status = 'approved'
              AND (e.end_date IS NULL OR e.end_date >= CURDATE())
            ORDER BY e.start_date ASC, e.id DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function countUpcomingPublic(): int {
        $this->db->query("
            SELECT COUNT(*) AS total
            FROM place_events e
            JOIN places p ON e.place_id = p.id
            WHERE e.status = 'active'
              AND p.status = 'approved'
              AND (e.end_date IS NULL OR e.end_date >= CURDATE())
        ");
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }
}
