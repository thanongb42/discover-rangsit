<?php
class AuthController extends Controller {
    public function login() {
        $this->view('auth/login', ['title' => 'Login - Discover Rangsit']);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identity = trim($_POST['email']); // Can be email or username
            $password = trim($_POST['password']);

            $userModel = $this->model('User');
            $loggedInUser = $userModel->login($identity, $password);

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->user_id;
                $_SESSION['user_email'] = $loggedInUser->email;
                $_SESSION['username'] = $loggedInUser->username;
                $_SESSION['user_name'] = $loggedInUser->first_name . ' ' . $loggedInUser->last_name;
                $_SESSION['user_role'] = $loggedInUser->role;
                $_SESSION['profile_image'] = $loggedInUser->profile_image;
                
                $userModel->updateLastLogin($loggedInUser->user_id);
                $this->logActivity('LOGIN', "User logged in via standard form");
                
                header('Location: ' . BASE_URL . '/dashboard');
            } else {
                $this->logActivity('LOGIN_FAILED', "Failed login attempt for: " . $identity);
                $_SESSION['error'] = 'Invalid username/email or password';
                $this->view('auth/login', ['title' => 'Login - Discover Rangsit']);
            }
        }
    }

    public function register() {
        $this->view('auth/register', ['title' => 'สมัครสมาชิก - Discover Rangsit']);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Split name into first and last name for compatibility with new structure
            $full_name = trim($_POST['name']);
            $name_parts = explode(' ', $full_name, 2);
            $first_name = $name_parts[0];
            $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

            $data = [
                'username' => trim($_POST['email']), // Default username to email
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => trim($_POST['email']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                'phone' => trim($_POST['phone']),
                'role' => 'user', // Default to 'user' ENUM
                'status' => 'active'
            ];

            $userModel = $this->model('User');
            if ($userModel->register($data)) {
                $_SESSION['success'] = 'ลงทะเบียนสำเร็จ กรุณาเข้าสู่ระบบ';
                header('Location: ' . BASE_URL . '/login');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function logout() {
        $this->logActivity('LOGOUT', "User logged out");
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
    }

    public function lineLogin() {
        $state = bin2hex(random_bytes(16));
        $_SESSION['line_state'] = $state;
        
        $url = "https://access.line.me/oauth2/v2.1/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id' => LINE_CLIENT_ID,
            'redirect_uri' => LINE_REDIRECT_URI,
            'state' => $state,
            'scope' => 'profile openid email'
        ]);
        
        header('Location: ' . $url);
    }

    public function lineCallback() {
        if (!isset($_GET['code']) || $_GET['state'] !== $_SESSION['line_state']) {
            die('Invalid request');
        }

        // 1. Exchange code for Access Token
        $ch = curl_init("https://api.line.me/oauth2/v2.1/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $_GET['code'],
            'redirect_uri' => LINE_REDIRECT_URI,
            'client_id' => LINE_CLIENT_ID,
            'client_secret' => LINE_CLIENT_SECRET
        ]));
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($response['access_token'])) die('Authentication failed');

        // 2. Get User Profile
        $ch = curl_init("https://api.line.me/v2/profile");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $response['access_token']]);
        $profile = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $userModel = $this->model('User');

        // CASE A: User is already logged in (Linking Account)
        if (isset($_SESSION['user_id'])) {
            if ($userModel->linkLineAccount($_SESSION['user_id'], $profile)) {
                $_SESSION['success'] = 'เชื่อมต่อบัญชี LINE เรียบร้อยแล้ว';
                $_SESSION['profile_image'] = $profile['pictureUrl'];
                header('Location: ' . BASE_URL . '/profile');
            } else {
                $_SESSION['error'] = 'บัญชี LINE นี้ถูกใช้งานโดยสมาชิกท่านอื่นแล้ว';
                header('Location: ' . BASE_URL . '/profile');
            }
            return;
        }

        // CASE B: Standard Login/Register
        $existingUser = $userModel->findByLineId($profile['userId']);

        if ($existingUser) {
            // Login existing LINE user
            $_SESSION['user_id'] = $existingUser->user_id;
            $_SESSION['user_name'] = $existingUser->first_name . ' ' . $existingUser->last_name;
            $_SESSION['user_role'] = $existingUser->role;
            $_SESSION['profile_image'] = $existingUser->line_picture_url ?: $existingUser->profile_image;
            
            $userModel->updateLastLogin($existingUser->user_id);
            header('Location: ' . BASE_URL . '/dashboard');
        } else {
            // New user via LINE - Create record
            $data = [
                'username' => 'line_' . substr($profile['userId'], 0, 8),
                'first_name' => $profile['displayName'],
                'last_name' => '',
                'email' => $profile['userId'] . '@line.placeholder', 
                'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                'phone' => '',
                'role' => 'user',
                'status' => 'active'
            ];
            
            if ($userModel->register($data)) {
                $newUser = $userModel->findByUsername($data['username']);
                $userModel->linkLineAccount($newUser->user_id, $profile);
                
                $_SESSION['user_id'] = $newUser->user_id;
                $_SESSION['user_name'] = $data['first_name'];
                $_SESSION['user_role'] = $data['role'];
                $_SESSION['profile_image'] = $profile['pictureUrl'];
                header('Location: ' . BASE_URL . '/dashboard');
            }
        }
    }
}