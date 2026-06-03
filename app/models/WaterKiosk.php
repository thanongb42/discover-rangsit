<?php
class WaterKiosk extends Model {

    public function getActive(): array {
        try {
            $this->db->query("
                SELECT *
                FROM water_kiosks
                WHERE status = 'active'
                ORDER BY kiosk_code ASC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }
}
