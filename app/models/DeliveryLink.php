<?php
class DeliveryLink extends Model
{
    public function getByPlace(int $placeId): array
    {
        $this->db->query('SELECT * FROM place_delivery_links WHERE place_id = :place_id AND is_active = 1 ORDER BY sort_order ASC, id ASC');
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function getByPlaceAdmin(int $placeId): array
    {
        $this->db->query('SELECT * FROM place_delivery_links WHERE place_id = :place_id ORDER BY sort_order ASC, id ASC');
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function upsert(int $placeId, string $platform, string $url, ?string $label, bool $isActive, int $sortOrder = 0): bool
    {
        $this->db->query('
            INSERT INTO place_delivery_links (place_id, platform, url, display_label, is_active, sort_order)
            VALUES (:place_id, :platform, :url, :label, :is_active, :sort_order)
            ON DUPLICATE KEY UPDATE
                url           = VALUES(url),
                display_label = VALUES(display_label),
                is_active     = VALUES(is_active),
                sort_order    = VALUES(sort_order)
        ');
        $this->db->bind(':place_id',   $placeId);
        $this->db->bind(':platform',   $platform);
        $this->db->bind(':url',        $url);
        $this->db->bind(':label',      $label);
        $this->db->bind(':is_active',  (int)$isActive);
        $this->db->bind(':sort_order', $sortOrder);
        return $this->db->execute();
    }

    public function delete(int $placeId, string $platform): bool
    {
        $this->db->query('DELETE FROM place_delivery_links WHERE place_id = :place_id AND platform = :platform');
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':platform', $platform);
        return $this->db->execute();
    }

    public function logClick(int $placeId, string $platform, string $ipHash, string $ua, string $referrer): void
    {
        $this->db->query('
            INSERT INTO place_delivery_clicks (place_id, platform, ip_hash, user_agent, referrer)
            VALUES (:place_id, :platform, :ip_hash, :user_agent, :referrer)
        ');
        $this->db->bind(':place_id',   $placeId);
        $this->db->bind(':platform',   $platform);
        $this->db->bind(':ip_hash',    $ipHash);
        $this->db->bind(':user_agent', substr($ua, 0, 255));
        $this->db->bind(':referrer',   substr($referrer, 0, 500));
        $this->db->execute();
    }

    public function incrementClick(int $placeId, string $platform): void
    {
        $this->db->query('UPDATE place_delivery_links SET click_count = click_count + 1 WHERE place_id = :place_id AND platform = :platform');
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':platform', $platform);
        $this->db->execute();
    }

    public function getClickStats(int $placeId): array
    {
        $this->db->query('
            SELECT platform, click_count,
                   (SELECT COUNT(*) FROM place_delivery_clicks c WHERE c.place_id = l.place_id AND c.platform = l.platform AND c.clicked_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) AS clicks_30d
            FROM place_delivery_links l
            WHERE place_id = :place_id
            ORDER BY click_count DESC
        ');
        $this->db->bind(':place_id', $placeId);
        return $this->db->resultSet();
    }

    public function getClicksByDay(int $placeId, int $days = 30): array
    {
        $this->db->query("
            SELECT DATE(clicked_at) as date, platform, COUNT(*) as clicks
            FROM place_delivery_clicks
            WHERE place_id = :place_id
              AND clicked_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(clicked_at), platform
            ORDER BY date ASC
        ");
        $this->db->bind(':place_id', $placeId);
        $this->db->bind(':days',     $days);
        return $this->db->resultSet();
    }

    public function getOwnerPlaceId(int $linkId): ?int
    {
        $this->db->query('SELECT place_id FROM place_delivery_links WHERE id = :id');
        $this->db->bind(':id', $linkId);
        $row = $this->db->single();
        return $row ? (int)$row->place_id : null;
    }
}
