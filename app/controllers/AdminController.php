<?php
class AdminController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function pending() {
        $placeModel = $this->model('Place');
        $pendingPlaces = $placeModel->getPending();

        $this->view('admin/pending', [
            'title' => 'Pending Approvals - Admin Dashboard',
            'places' => $pendingPlaces,
            'current_page' => 'pending'
        ]);
    }

    public function approve() {
        // ... previous code
    }

    public function reject() {
        // ... previous code
    }

    public function users() {
        $userModel = $this->model('User');
        $users = $userModel->getAllUsers();
        $roles = $userModel->getRoles();

        $this->view('admin/users', [
            'title' => 'Manage Users - Admin Dashboard',
            'users' => $users,
            'roles' => $roles,
            'current_page' => 'users'
        ]);
    }

    public function updateUserRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $roleId = $_POST['role_id'];
            $userModel = $this->model('User');
            if ($userModel->updateRole($userId, $roleId)) {
                $_SESSION['success'] = 'User role updated successfully.';
                header('Location: ' . BASE_URL . '/admin/users');
            }
        }
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['user_id'];
            
            // Prevent self-deletion
            if ($id == $_SESSION['user_id']) {
                $_SESSION['error'] = 'You cannot delete your own account.';
                header('Location: ' . BASE_URL . '/admin/users');
                return;
            }

            $userModel = $this->model('User');
            if ($userModel->delete($id)) {
                $_SESSION['success'] = 'User deleted successfully.';
                header('Location: ' . BASE_URL . '/admin/users');
            }
        }
    }
}