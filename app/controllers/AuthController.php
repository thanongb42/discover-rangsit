<?php
class AuthController extends Controller {
    public function login() {
        $this->view('auth/login', ['title' => 'Login - Discover Rangsit']);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $userModel = $this->model('User');
            $loggedInUser = $userModel->login($email, $password);

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_email'] = $loggedInUser->email;
                $_SESSION['user_name'] = $loggedInUser->name;
                $_SESSION['user_role'] = $loggedInUser->role_name;
                
                header('Location: ' . BASE_URL . '/dashboard');
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                $this->view('auth/login', ['title' => 'Login - Discover Rangsit']);
            }
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                'phone' => trim($_POST['phone']),
                'role_id' => 3 // Default to 'member'
            ];

            $userModel = $this->model('User');
            if ($userModel->register($data)) {
                $_SESSION['success'] = 'Registration successful. Please login.';
                header('Location: ' . BASE_URL . '/login');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
    }
}