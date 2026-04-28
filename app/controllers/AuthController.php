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
                
                header('Location: ' . BASE_URL . '/my-businesses');
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

    // ─── Google OAuth ──────────────────────────────────────────────────────────
    public function googleLogin() {
        $state = bin2hex(random_bytes(16));
        $_SESSION['google_state'] = $state;

        $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
        ]);
        header('Location: ' . $url);
    }

    public function googleCallback() {
        if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['google_state'] ?? '')) {
            $_SESSION['error'] = 'การยืนยันตัวตนล้มเหลว กรุณาลองใหม่';
            header('Location: ' . BASE_URL . '/login');
            return;
        }

        // Exchange code for access token
        $ch = curl_init("https://oauth2.googleapis.com/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code'          => $_GET['code'],
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code',
        ]));
        $tokenData = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($tokenData['access_token'])) {
            $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อกับ Google ได้';
            header('Location: ' . BASE_URL . '/login');
            return;
        }

        // Get user profile
        $ch = curl_init("https://www.googleapis.com/oauth2/v2/userinfo");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $tokenData['access_token']]);
        $profile = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $userModel = $this->model('User');

        // Case A: Already logged in — link account
        if (isset($_SESSION['user_id'])) {
            if ($userModel->linkGoogleAccount($_SESSION['user_id'], $profile)) {
                $_SESSION['success'] = 'เชื่อมต่อบัญชี Google เรียบร้อยแล้ว';
            } else {
                $_SESSION['error'] = 'บัญชี Google นี้ถูกใช้งานโดยสมาชิกท่านอื่นแล้ว';
            }
            header('Location: ' . BASE_URL . '/profile');
            return;
        }

        // Case B: Login or Register
        $existingUser = $userModel->findByGoogleId($profile['id']);

        if ($existingUser) {
            $this->_setGoogleSession($existingUser, $profile);
            $userModel->updateLastLogin($existingUser->user_id);
            $this->logActivity('LOGIN', "User logged in via Google");
            header('Location: ' . BASE_URL . '/my-businesses');
            return;
        }

        // Check if email already registered — auto-link
        if (!empty($profile['email'])) {
            $emailUser = $userModel->findUserByEmail($profile['email']);
            if ($emailUser) {
                $userModel->linkGoogleAccount($emailUser->user_id, $profile);
                $this->_setGoogleSession($emailUser, $profile);
                $userModel->updateLastLogin($emailUser->user_id);
                $this->logActivity('LOGIN', "User logged in via Google (email match)");
                header('Location: ' . BASE_URL . '/my-businesses');
                return;
            }
        }

        // New user — create account
        $nameParts  = explode(' ', $profile['name'] ?? 'Google User', 2);
        $data = [
            'username'   => 'google_' . substr($profile['id'], 0, 8),
            'first_name' => $nameParts[0],
            'last_name'  => $nameParts[1] ?? '',
            'email'      => $profile['email'] ?? $profile['id'] . '@google.placeholder',
            'password'   => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
            'phone'      => '',
            'role'       => 'user',
            'status'     => 'active',
        ];
        if ($userModel->register($data)) {
            $newUser = $userModel->findByUsername($data['username']);
            $userModel->linkGoogleAccount($newUser->user_id, $profile);
            $this->_setGoogleSession($newUser, $profile);
            $this->logActivity('REGISTER', "New user registered via Google");
            header('Location: ' . BASE_URL . '/my-businesses');
        }
    }

    private function _setGoogleSession($user, $profile) {
        $_SESSION['user_id']      = $user->user_id;
        $_SESSION['user_email']   = $user->email;
        $_SESSION['username']     = $user->username;
        $_SESSION['user_name']    = $user->first_name . ' ' . $user->last_name;
        $_SESSION['user_role']    = $user->role;
        $_SESSION['profile_image'] = $profile['picture'] ?? $user->profile_image;
    }

    public function lineLogin() {
        $state = bin2hex(random_bytes(16));
        $_SESSION['line_state'] = $state;
        setcookie('line_oauth_state', $state, [
            'expires'  => time() + 300,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        $url = "https://access.line.me/oauth2/v2.1/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id'     => LINE_CLIENT_ID,
            'redirect_uri'  => LINE_REDIRECT_URI,
            'state'         => $state,
            'scope'         => 'profile openid email'
        ]);

        header('Location: ' . $url);
    }

    public function lineCallback() {
        $expectedState = $_SESSION['line_state'] ?? ($_COOKIE['line_oauth_state'] ?? '');
        setcookie('line_oauth_state', '', time() - 1, '/');

        if (!isset($_GET['code']) || empty($_GET['state']) || $_GET['state'] !== $expectedState) {
            $_SESSION['error'] = 'การเข้าสู่ระบบด้วย LINE ล้มเหลว กรุณาลองใหม่อีกครั้ง';
            header('Location: ' . BASE_URL . '/login');
            exit;
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

        if (!isset($response['access_token'])) {
            $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อกับ LINE ได้ กรุณาลองใหม่อีกครั้ง';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

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
            header('Location: ' . BASE_URL . '/my-businesses');
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
                header('Location: ' . BASE_URL . '/my-businesses');
            }
        }
    }
}