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

    public function myBusinesses() {
        $placeModel = $this->model('Place');
        $businesses = $placeModel->getPlacesByOwner($_SESSION['user_id']);

        $this->view('user/my_businesses', [
            'title' => 'ธุรกิจของฉัน - Discover Rangsit',
            'businesses' => $businesses,
            'current_page' => 'my_businesses'
        ]);
    }

    public function myReviews() {
        $placeModel = $this->model('Place');
        $reviews = $placeModel->getReviewsByUser($_SESSION['user_id']);

        $this->view('user/my_reviews', [
            'title' => 'รีวิวของฉัน - Discover Rangsit',
            'reviews' => $reviews,
            'current_page' => 'my_reviews'
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
                $this->logActivity('PROFILE_UPDATE', "Updated personal profile information");
                $_SESSION['success'] = 'Profile updated successfully.';
                header('Location: ' . BASE_URL . '/profile');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function updateAvatar() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_FILES['avatar'])) {
                if ($_FILES['avatar']['error'] !== 0) {
                    $errors = [
                        1 => 'ไฟล์มีขนาดใหญ่เกินกว่าที่เซิร์ฟเวอร์กำหนด (php.ini)',
                        2 => 'ไฟล์มีขนาดใหญ่เกินไป',
                        3 => 'ไฟล์ถูกอัปโหลดมาไม่สมบูรณ์',
                        4 => 'ไม่มีการส่งไฟล์มา',
                        6 => 'ไม่พบโฟลเดอร์ชั่วคราวสำหรับอัปโหลด',
                        7 => 'ไม่สามารถเขียนไฟล์ลงดิสก์ได้',
                        8 => 'PHP extension หยุดการอัปโหลด'
                    ];
                    $msg = $errors[$_FILES['avatar']['error']] ?? 'Unknown upload error';
                    echo json_encode(['success' => false, 'message' => $msg]);
                    return;
                }

                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($ext, $allowed)) {
                    echo json_encode(['success' => false, 'message' => 'รองรับเฉพาะไฟล์รูปภาพ (jpg, png, webp) เท่านั้น']);
                    return;
                }

                $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                $upload_dir = APP_ROOT . '/public/uploads/avatars/';
                
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $target = $upload_dir . $filename;
                
                if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                    $userModel = $this->model('User');
                    $db_path = 'uploads/avatars/' . $filename;
                    if($userModel->updateAvatar($_SESSION['user_id'], $db_path)) {
                        $_SESSION['profile_image'] = $db_path;
                        $this->logActivity('AVATAR_UPDATE', "Uploaded new profile picture");
                        echo json_encode(['success' => true, 'message' => 'อัปโหลดรูปโปรไฟล์สำเร็จ', 'path' => $db_path]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่สามารถย้ายไฟล์ไปยังโฟลเดอร์ปลายทางได้ (Permission denied?)']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลไฟล์ที่ส่งมา']);
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
                $this->logActivity('PASSWORD_CHANGE_FAILED', "Incorrect current password attempt");
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
                $this->logActivity('PASSWORD_CHANGE', "Successfully changed account password");
                $_SESSION['success'] = 'Password changed successfully.';
                header('Location: ' . BASE_URL . '/profile');
            } else {
                die('Something went wrong');
            }
        }
    }
}