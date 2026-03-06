<?php
class User extends Model {
    
    public function findUserByEmail($email) {
        $this->db->query("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function findById($id) {
        $this->db->query("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateProfile($data) {
        $this->db->query("UPDATE users SET name = :name, phone = :phone WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function updatePassword($id, $hashed_password) {
        $this->db->query("UPDATE users SET password = :password WHERE id = :id");
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function register($data) {
        $this->db->query("INSERT INTO users (name, email, password, phone, role_id) VALUES (:name, :email, :password, :phone, :role_id)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role_id', $data['role_id']);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password) {
        $row = $this->findUserByEmail($email);
        if (!$row) return false;

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        }
        
        return false;
    }

    public function getAllUsers() {
        $this->db->query("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC");
        return $this->db->resultSet();
    }

    public function getRoles() {
        $this->db->query("SELECT * FROM roles");
        return $this->db->resultSet();
    }

    public function updateRole($userId, $roleId) {
        $this->db->query("UPDATE users SET role_id = :role_id WHERE id = :id");
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}