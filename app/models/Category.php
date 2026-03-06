<?php
class Category extends Model {
    public function getAll() {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data) {
        $this->db->query("INSERT INTO categories (name, icon, color) VALUES (:name, :icon, :color)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':color', $data['color']);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query("UPDATE categories SET name = :name, icon = :icon, color = :color WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function delete($id) {
        // Check if category is used in places
        $this->db->query("SELECT COUNT(*) as total FROM places WHERE category_id = :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        
        if ($row->total > 0) {
            return false; // Cannot delete if used
        }

        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}