<?php
class UserController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function profile() {
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);
        $prefixes = $userModel->getPrefixes();

        $this->view('user/profile', [
            'title' => 'My Profile - Discover Rangsit',
            'user' => $user,
            'prefixes' => $prefixes,
            'current_page' => 'profile'
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_SESSION['user_id'],
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'prefix_id' => $_POST['prefix_id'],
                'phone' => trim($_POST['phone'])
            ];

            $userModel = $this->model('User');
            if ($userModel->updateProfile($data)) {
                $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                $_SESSION['success'] = 'Profile updated successfully.';
                header('Location: ' . BASE_URL . '/profile');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->findById($_SESSION['user_id']);

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (!password_verify($current_password, $user->password)) {
                $_SESSION['error'] = 'Current password is incorrect.';
                header('Location: ' . BASE_URL . '/profile');
                return;
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = 'New passwords do not match.';
                header('Location: ' . BASE_URL . '/profile');
                return;
            }

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            if ($userModel->updatePassword($_SESSION['user_id'], $hashed_password)) {
                $_SESSION['success'] = 'Password changed successfully.';
                header('Location: ' . BASE_URL . '/profile');
            } else {
                die('Something went wrong');
            }
        }
    }
}