<?php
class ApiController extends Controller {
    
    public function places() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved();
        echo json_encode($places);
    }

    public function categories() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $cats = $placeModel->getCategories();
        echo json_encode($cats);
    }

    public function search() {
        header('Content-Type: application/json');
        if (isset($_GET['q'])) {
            $keyword = trim($_GET['q']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $placeModel = $this->model('Place');
            $placeModel->logSearch($keyword, $ip);
            echo json_encode(['status' => 'logged']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No query provided']);
        }
    }

    public function topPlaces() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $trending = $placeModel->getTrending();
        echo json_encode($trending);
    }

    public function categoriesAdd() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = $this->model('Category');
            $data = [
                'name' => trim($_POST['name']),
                'icon' => trim($_POST['icon']),
                'color' => trim($_POST['color'])
            ];
            
            if ($categoryModel->add($data)) {
                $this->logActivity('CATEGORY_ADD', "Created new category: " . $data['name']);
                echo json_encode(['success' => true, 'message' => 'เพิ่มหมวดหมู่สำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถเพิ่มหมวดหมู่ได้']);
            }
        }
    }

    public function categoriesUpdate() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = $this->model('Category');
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'icon' => trim($_POST['icon']),
                'color' => trim($_POST['color'])
            ];
            
            if ($categoryModel->update($data)) {
                $this->logActivity('CATEGORY_UPDATE', "Updated category ID: " . $data['id']);
                echo json_encode(['success' => true, 'message' => 'อัปเดตหมวดหมู่สำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตหมวดหมู่ได้']);
            }
        }
    }

    public function categoriesDelete() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $categoryModel = $this->model('Category');
            
            if ($categoryModel->delete($id)) {
                $this->logActivity('CATEGORY_DELETE', "Deleted category ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'ลบหมวดหมู่สำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ เนื่องจากมีธุรกิจอยู่ในหมวดหมู่ชุดนี้']);
            }
        }
    }

    public function placeApprove() {
        header('Content-Type: application/json');
        // Admin check
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            if ($placeModel->updateStatus($id, 'approved')) {
                $this->logActivity('PLACE_APPROVE', "Approved place ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'อนุมัติธุรกิจเรียบร้อยแล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอนุมัติ']);
            }
        }
    }

    public function placeReject() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            if ($placeModel->updateStatus($id, 'rejected')) {
                $this->logActivity('PLACE_REJECT', "Rejected place ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'ปฏิเสธธุรกิจเรียบร้อยแล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการปฏิเสธ']);
            }
        }
    }

    public function placeUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            $currentPlace = $placeModel->getById($id);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'category_id' => $_POST['category_id'],
                'description' => trim($_POST['description']),
                'address' => trim($_POST['address']),
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude'],
                'phone' => trim($_POST['phone']),
                'website' => trim($_POST['website']),
                'facebook' => trim($_POST['facebook']),
                'line' => trim($_POST['line']),
                'x' => trim($_POST['x']),
                'instagram' => trim($_POST['instagram']),
                'youtube' => trim($_POST['youtube']),
                'tiktok' => trim($_POST['tiktok']),
                'status' => $_POST['status'],
                'cover_image' => $currentPlace->cover_image,
                'line_qr' => $currentPlace->line_qr
            ];

            // Handle New Cover Image
            if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_cover.' . $ext;
                $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $data['cover_image'] = $filename;
                }
            }

            // Handle LINE QR Image
            if(isset($_FILES['line_qr']) && $_FILES['line_qr']['error'] == 0) {
                $ext = pathinfo($_FILES['line_qr']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_lineqr.' . $ext;
                $target = APP_ROOT . '/public/uploads/gallery/' . $filename; // Use gallery folder for simplicity or create a separate one
                if(move_uploaded_file($_FILES['line_qr']['tmp_name'], $target)) {
                    $data['line_qr'] = $filename;
                }
            }

            if ($placeModel->update($data)) {
                $this->logActivity('PLACE_UPDATE', "Updated place: " . $data['name']);
                echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
            }
        }
    }

    public function placeDelete() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            if ($placeModel->delete($id)) {
                $this->logActivity('PLACE_DELETE', "Deleted place ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'ลบสถานที่เรียบร้อยแล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
            }
        }
    }

    public function placeCoverUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_cover.' . $ext;
                $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                
                if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $placeModel = $this->model('Place');
                    // We need a method in Place model to update just the cover
                    // For now, let's use a dynamic update or just add the method to model
                    if($placeModel->updateCover($id, $filename)) {
                        echo json_encode(['success' => true, 'message' => 'อัปเดตรูปหน้าปกสำเร็จ']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Database error']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Upload failed']);
                }
            }
        }
    }

    public function placeLineQrUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            if(isset($_FILES['line_qr']) && $_FILES['line_qr']['error'] == 0) {
                $ext = pathinfo($_FILES['line_qr']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_lineqr.' . $ext;
                $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                
                if(move_uploaded_file($_FILES['line_qr']['tmp_name'], $target)) {
                    $placeModel = $this->model('Place');
                    if($placeModel->updateLineQr($id, $filename)) {
                        echo json_encode(['success' => true, 'message' => 'อัปเดต LINE QR สำเร็จ']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Database error']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Upload failed']);
                }
            }
        }
    }

    public function galleryUpload() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $place_id = $_POST['place_id'];
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . rand(1000,9999) . '.' . $ext;
                $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                
                if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $placeModel = $this->model('Place');
                    if($placeModel->addGalleryImage($place_id, $filename)) {
                        echo json_encode(['success' => true, 'message' => 'อัปโหลดรูปภาพสำเร็จ', 'filename' => $filename]);
                    }
                }
            }
        }
    }

    public function galleryDelete() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image_id = $_POST['id'];
            $placeModel = $this->model('Place');
            if($placeModel->deleteGalleryImage($image_id)) {
                echo json_encode(['success' => true, 'message' => 'ลบรูปภาพแล้ว']);
            }
        }
    }

    public function placeReview() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนให้คะแนน']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $place_id = $_POST['place_id'] ?? null;
                $rating = $_POST['rating'] ?? null;
                $comment = trim($_POST['comment'] ?? '');
                $user_id = $_SESSION['user_id'];

                if (!$place_id || !$rating) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาเลือกคะแนนดาวก่อนส่ง']);
                    return;
                }

                $placeModel = $this->model('Place');
                if ($placeModel->addReview($place_id, $user_id, $rating, $comment)) {
                    echo json_encode(['success' => true, 'message' => 'ขอบคุณสำหรับการให้คะแนน!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
            }
        }
    }

    public function userGet($id) {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        if ($user) {
            unset($user->password);
            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }

    public function userAdd() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            
            // Check if email or username exists
            if ($userModel->findUserByEmail($_POST['email'])) {
                echo json_encode(['success' => false, 'message' => 'อีเมลนี้ถูกใช้งานแล้ว']);
                return;
            }
            if ($userModel->findByUsername($_POST['username'])) {
                echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้งานนี้ถูกใช้งานแล้ว']);
                return;
            }

            $data = [
                'username' => trim($_POST['username']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role'],
                'status' => 'active'
            ];

            if ($userModel->register($data)) {
                $this->logActivity('USER_ADD', "Created new user: " . $data['username']);
                echo json_encode(['success' => true, 'message' => 'เพิ่มผู้ใช้งานสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
            }
        }
    }

    public function userUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $data = [
                'id' => $_POST['id'],
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone']),
                'role' => $_POST['role'],
                'department_id' => $_POST['department_id'] ?: NULL,
                'position' => trim($_POST['position']),
                'status' => $_POST['status']
            ];

            if ($userModel->fullUpdate($data)) {
                $this->logActivity('USER_UPDATE', "Updated user ID: " . $data['id']);
                echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
            }
        }
    }

    public function userResetPassword() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userModel = $this->model('User');
            if ($userModel->updatePassword($id, $new_password)) {
                $this->logActivity('USER_PASSWORD_RESET', "Reset password for user ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'รีเซ็ตรหัสผ่านสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
            }
        }
    }

    public function userDelete() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'คุณไม่สามารถลบบัญชีตัวเองได้']);
                return;
            }
            $userModel = $this->model('User');
            if ($userModel->delete($id)) {
                $this->logActivity('USER_DELETE', "Deleted user ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'ลบผู้ใช้งานสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
            }
        }
    }
}