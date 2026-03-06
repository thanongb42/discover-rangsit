<?php
class User extends Model {
    
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM v_users_full WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function findByUsername($username) {
        $this->db->query("SELECT * FROM v_users_full WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function findById($id) {
        $this->db->query("SELECT * FROM v_users_full WHERE user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findByLineId($line_id) {
        $this->db->query("SELECT * FROM v_users_full WHERE line_user_id = :line_id");
        $this->db->bind(':line_id', $line_id);
        return $this->db->single();
    }

    public function linkLineAccount($user_id, $line_data) {
        $this->db->query("UPDATE users SET line_user_id = :line_id, line_display_name = :display_name, line_picture_url = :picture_url, line_linked_at = NOW() WHERE user_id = :id");
        $this->db->bind(':line_id', $line_data['userId']);
        $this->db->bind(':display_name', $line_data['displayName']);
        $this->db->bind(':picture_url', $line_data['pictureUrl']);
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }

    public function updateProfile($data) {
        $this->db->query("UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, prefix_id = :prefix_id WHERE user_id = :id");
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':prefix_id', $data['prefix_id']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function fullUpdate($data) {
        $this->db->query("UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, role = :role, department_id = :department_id, position = :position, status = :status WHERE user_id = :id");
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function updatePassword($id, $hashed_password) {
        $this->db->query("UPDATE users SET password = :password WHERE user_id = :id");
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateAvatar($id, $filename) {
        $this->db->query("UPDATE users SET profile_image = :image WHERE user_id = :id");
        $this->db->bind(':image', $filename);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function register($data) {
        $this->db->query("INSERT INTO users (username, first_name, last_name, email, password, phone, role, status) VALUES (:username, :first_name, :last_name, :email, :password, :phone, :role, :status)");
        $this->db->bind(':username', $data['username'] ?? $data['email']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name'] ?? '');
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', $data['role'] ?? 'user');
        $this->db->bind(':status', $data['status'] ?? 'active');
        
        return $this->db->execute();
    }

    public function login($identity, $password) {
        // Find by email or username
        $this->db->query("SELECT * FROM users WHERE email = :identity OR username = :identity");
        $this->db->bind(':identity', $identity);
        $row = $this->db->single();
        
        if (!$row) return false;

        if (password_verify($password, $row->password)) {
            return $row;
        }
        
        return false;
    }

    public function updateLastLogin($id) {
        $this->db->query("UPDATE users SET last_login = NOW() WHERE user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllUsers() {
        $this->db->query("SELECT * FROM v_users_full ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getPrefixes() {
        $this->db->query("SELECT * FROM prefixes WHERE is_active = 1 ORDER BY display_order");
        return $this->db->resultSet();
    }

    public function getDepartments() {
        $this->db->query("SELECT * FROM departments WHERE status = 'active' ORDER BY department_name");
        return $this->db->resultSet();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM users WHERE user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getStats() {
        $this->db->query("SELECT
            COUNT(*) as total_users,
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_count,
            SUM(CASE WHEN role = 'staff' THEN 1 ELSE 0 END) as staff_count,
            SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as user_count,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_count,
            SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended_count
        FROM users");
        return $this->db->single();
    }
}