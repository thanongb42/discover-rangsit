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
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            $currentPlace = $placeModel->getById($id);

            $isAdmin = $_SESSION['user_role'] === 'admin';
            $isOwner = $currentPlace && (int)$currentPlace->owner_user_id === (int)$_SESSION['user_id'];
            if (!$isAdmin && !$isOwner) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

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
                'status' => $isAdmin ? $_POST['status'] : $currentPlace->status,
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

    public function placeTrash() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)$_POST['id'];
            $placeModel = $this->model('Place');

            // Admin can trash any place; owner can trash their own
            $isAdmin = $_SESSION['user_role'] === 'admin';
            $ownerId = $placeModel->getOwnerUserId($id);
            $isOwner = $ownerId !== null && $ownerId === (int)$_SESSION['user_id'];

            if (!$isAdmin && !$isOwner) {
                echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์ดำเนินการ']);
                return;
            }

            if ($placeModel->trash($id)) {
                $this->logActivity('PLACE_TRASH', "Moved to trash place ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'ย้ายไปถังขยะแล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถดำเนินการได้']);
            }
        }
    }

    public function placeRestore() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)$_POST['id'];
            $placeModel = $this->model('Place');
            if ($placeModel->restore($id)) {
                $this->logActivity('PLACE_RESTORE', "Restored place ID: " . $id);
                echo json_encode(['success' => true, 'message' => 'กู้คืนสถานที่แล้ว']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถกู้คืนได้']);
            }
        }
    }

    public function getTrashedPlaces() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        $placeModel = $this->model('Place');
        $places = $placeModel->getTrashed();
        echo json_encode(['success' => true, 'places' => $places]);
    }

    public function placeCoverUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            $currentPlace = $placeModel->getById($id);
            $isAdmin = $_SESSION['user_role'] === 'admin';
            $isOwner = $currentPlace && (int)$currentPlace->owner_user_id === (int)$_SESSION['user_id'];
            if (!$isAdmin && !$isOwner) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }
            $filename = null;

            // Priority 1: Base64 data (resized on client)
            if (!empty($_POST['cover_base64'])) {
                $base64Data = $_POST['cover_base64'];
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $ext = strtolower($type[1]);
                    $filename = time() . '_cover.' . $ext;
                    $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                    if (!file_put_contents($target, base64_decode($base64Data))) {
                        $filename = null;
                    }
                }
            } 
            // Priority 2: Standard file upload
            else if (isset($_FILES['cover_image'])) {
                $fileErr = $_FILES['cover_image']['error'];
                if ($fileErr !== UPLOAD_ERR_OK) {
                    $errMap = [
                        UPLOAD_ERR_INI_SIZE   => 'ไฟล์ใหญ่เกิน upload_max_filesize ใน php.ini',
                        UPLOAD_ERR_FORM_SIZE  => 'ไฟล์ใหญ่เกิน MAX_FILE_SIZE ใน form',
                        UPLOAD_ERR_PARTIAL    => 'อัปโหลดไม่ครบ (partial upload)',
                        UPLOAD_ERR_NO_FILE    => 'ไม่พบไฟล์ที่อัปโหลด',
                        UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบ tmp directory บนเซิร์ฟเวอร์',
                        UPLOAD_ERR_CANT_WRITE => 'เขียนไฟล์ไม่ได้ (disk full?)',
                        UPLOAD_ERR_EXTENSION  => 'PHP extension บล็อกการอัปโหลด',
                    ];
                    echo json_encode(['success' => false, 'message' => $errMap[$fileErr] ?? "Upload error code: $fileErr"]);
                    return;
                }
                $uploadDir = APP_ROOT . '/public/uploads/covers/';
                if (!is_dir($uploadDir)) {
                    echo json_encode(['success' => false, 'message' => "Directory ไม่มีอยู่: $uploadDir"]);
                    return;
                }
                if (!is_writable($uploadDir)) {
                    echo json_encode(['success' => false, 'message' => "ไม่มีสิทธิ์เขียนไฟล์ใน: $uploadDir"]);
                    return;
                }
                $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION)) ?: 'jpg';
                $filename = time() . '_cover.' . $ext;
                $target = $uploadDir . $filename;
                if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $err = error_get_last();
                    echo json_encode(['success' => false, 'message' => 'move_uploaded_file ล้มเหลว: ' . ($err['message'] ?? 'unknown')]);
                    return;
                }
            }

            if ($filename) {
                $placeModel = $this->model('Place');
                if ($placeModel->updateCover($id, $filename)) {
                    $this->logActivity('PLACE_UPDATE_COVER', "Updated cover for place ID: " . $id);
                    echo json_encode(['success' => true, 'message' => 'อัปเดตรูปหน้าปกสำเร็จ']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database error: updateCover ล้มเหลว']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบไฟล์ (cover_base64 และ cover_image ว่างเปล่า)']);
            }
        }
    }

    public function placeLineQrUpdate() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $placeModel = $this->model('Place');
            $currentPlace = $placeModel->getById($id);
            $isAdmin = $_SESSION['user_role'] === 'admin';
            $isOwner = $currentPlace && (int)$currentPlace->owner_user_id === (int)$_SESSION['user_id'];
            if (!$isAdmin && !$isOwner) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }
            $filename = null;

            // Priority 1: Base64
            if (!empty($_POST['qr_base64'])) {
                $base64Data = $_POST['qr_base64'];
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $ext = strtolower($type[1]);
                    $filename = time() . '_lineqr.' . $ext;
                    $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                    if (!file_put_contents($target, base64_decode($base64Data))) {
                        $filename = null;
                    }
                }
            }
            // Priority 2: Standard file
            else if (isset($_FILES['line_qr'])) {
                $fileErr = $_FILES['line_qr']['error'];
                if ($fileErr !== UPLOAD_ERR_OK) {
                    $errMap = [
                        UPLOAD_ERR_INI_SIZE   => 'ไฟล์ใหญ่เกิน upload_max_filesize',
                        UPLOAD_ERR_PARTIAL    => 'อัปโหลดไม่ครบ',
                        UPLOAD_ERR_NO_FILE    => 'ไม่พบไฟล์',
                        UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบ tmp directory',
                        UPLOAD_ERR_CANT_WRITE => 'เขียนไฟล์ไม่ได้',
                    ];
                    echo json_encode(['success' => false, 'message' => $errMap[$fileErr] ?? "Upload error code: $fileErr"]);
                    return;
                }
                $uploadDir = APP_ROOT . '/public/uploads/gallery/';
                if (!is_writable($uploadDir)) {
                    echo json_encode(['success' => false, 'message' => "ไม่มีสิทธิ์เขียนไฟล์ใน: $uploadDir"]);
                    return;
                }
                $ext = strtolower(pathinfo($_FILES['line_qr']['name'], PATHINFO_EXTENSION)) ?: 'jpg';
                $filename = time() . '_lineqr.' . $ext;
                $target = $uploadDir . $filename;
                if (!move_uploaded_file($_FILES['line_qr']['tmp_name'], $target)) {
                    $err = error_get_last();
                    echo json_encode(['success' => false, 'message' => 'move_uploaded_file ล้มเหลว: ' . ($err['message'] ?? 'unknown')]);
                    return;
                }
            }

            if ($filename) {
                $placeModel = $this->model('Place');
                if ($placeModel->updateLineQr($id, $filename)) {
                    $this->logActivity('PLACE_UPDATE_QR', "Updated LINE QR for place ID: " . $id);
                    echo json_encode(['success' => true, 'message' => 'อัปเดต LINE QR สำเร็จ']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database error: updateLineQr ล้มเหลว']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Upload failed']);
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
            $place_id = intval($_POST['place_id'] ?? 0);
            if (!$place_id) {
                echo json_encode(['success' => false, 'message' => 'Invalid place_id']);
                return;
            }
            if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
                $errCode = $_FILES['image']['error'] ?? -1;
                echo json_encode(['success' => false, 'message' => 'File upload error code: ' . $errCode]);
                return;
            }
            // Always save as .jpg (client sends canvas JPEG blob)
            $filename = time() . '_' . rand(1000,9999) . '.jpg';
            $target = APP_ROOT . '/public/uploads/gallery/' . $filename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $err = error_get_last();
                echo json_encode(['success' => false, 'message' => 'move_uploaded_file failed: ' . ($err['message'] ?? 'unknown')]);
                return;
            }

            $placeModel = $this->model('Place');
            $newId = $placeModel->addGalleryImage($place_id, $filename);
            if ($newId) {
                echo json_encode(['success' => true, 'id' => $newId, 'filename' => $filename, 'message' => 'อัปโหลดรูปภาพสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database insert failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
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

    public function getMapSettings() {
        header('Content-Type: application/json');
        $settingsFile = APP_ROOT . '/config/map_settings.json';
        if (file_exists($settingsFile)) {
            echo file_get_contents($settingsFile);
        } else {
            echo json_encode([
                'clustering_enabled' => true,
                'disable_clustering_at_zoom' => 14,
                'max_cluster_radius' => 50,
                'spiderfy_on_max_zoom' => true
            ]);
        }
    }

    public function saveMapSettings() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $settings = [
            'clustering_enabled' => (bool)($input['clustering_enabled'] ?? true),
            'disable_clustering_at_zoom' => (int)($input['disable_clustering_at_zoom'] ?? 14),
            'max_cluster_radius' => (int)($input['max_cluster_radius'] ?? 50),
            'spiderfy_on_max_zoom' => (bool)($input['spiderfy_on_max_zoom'] ?? true)
        ];

        $settingsFile = APP_ROOT . '/config/map_settings.json';
        if (file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT)) !== false) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้']);
        }
    }

    public function saveSettings() {
        ob_start();
        try {
            header('Content-Type: application/json');
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
                return;
            }
            $allowed_sections = ['hero', 'footer', 'email'];
            $file = APP_ROOT . '/config/site_settings.json';
            $current = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
            $section = $input['section'] ?? '';
            if (!in_array($section, $allowed_sections)) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Invalid section']);
                return;
            }
            $current[$section] = $input['data'] ?? [];
            ob_end_clean();
            if (file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
                echo json_encode(['success' => true, 'message' => 'บันทึกการตั้งค่าสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถบันทึกไฟล์ได้ (Permission denied?)']);
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function dbBackup() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.0 403 Forbidden');
            echo 'Unauthorized';
            return;
        }
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $output  = "-- Discover Rangsit DB Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $output .= "-- Database: " . DB_NAME . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $createRow = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
                $createSql = array_values($createRow)[1];
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $output .= $createSql . ";\n\n";

                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $cols = '`' . implode('`, `', array_keys($rows[0])) . '`';
                    $chunks = array_chunk($rows, 100);
                    foreach ($chunks as $chunk) {
                        $values = [];
                        foreach ($chunk as $row) {
                            $escaped = array_map(function($v) use ($pdo) {
                                return is_null($v) ? 'NULL' : $pdo->quote($v);
                            }, array_values($row));
                            $values[] = '(' . implode(', ', $escaped) . ')';
                        }
                        $output .= "INSERT INTO `$table` ($cols) VALUES\n" . implode(",\n", $values) . ";\n";
                    }
                    $output .= "\n";
                }
            }
            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $filename = 'backup_' . DB_NAME . '_' . date('Ymd_His') . '.sql';
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($output));
            echo $output;
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Backup failed: ' . $e->getMessage();
        }
    }

    public function dbRestore() {
        ob_start();
        try {
            header('Content-Type: application/json');
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }
            if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'ไม่พบไฟล์ SQL ที่ส่งมา']);
                return;
            }
            $ext = strtolower(pathinfo($_FILES['sql_file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'sql') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'รองรับเฉพาะไฟล์ .sql เท่านั้น']);
                return;
            }
            $sql = file_get_contents($_FILES['sql_file']['tmp_name']);
            if (empty($sql)) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'ไฟล์ SQL ว่างเปล่า']);
                return;
            }
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
            $statements = array_filter(
                array_map('trim', preg_split('/;\s*[\r\n]+/', $sql)),
                fn($s) => $s !== '' && strpos($s, '--') !== 0
            );
            foreach ($statements as $stmt) {
                if (!empty(trim($stmt))) {
                    $pdo->exec($stmt);
                }
            }
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => 'คืนค่าฐานข้อมูลสำเร็จ ' . count($statements) . ' statements']);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Restore failed: ' . $e->getMessage()]);
        }
    }

    public function placeLike() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนกดถูกใจ']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $place_id = (int)($input['place_id'] ?? 0);
        if (!$place_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid place']);
            return;
        }

        $placeModel = $this->model('Place');
        $result = $placeModel->toggleLike($place_id, $_SESSION['user_id']);
        echo json_encode(['success' => true, 'liked' => $result['liked'], 'count' => $result['count']]);
    }

    public function placeLikers() {
        header('Content-Type: application/json');
        $place_id = (int)($_GET['place_id'] ?? 0);
        if (!$place_id) {
            echo json_encode([]);
            return;
        }
        $placeModel = $this->model('Place');
        $likers = $placeModel->getLikers($place_id);
        echo json_encode($likers);
    }

    public function getWeather() {
        header('Content-Type: application/json');
        $cacheFile = APP_ROOT . '/config/cache/weather.json';
        $cacheTTL  = 3600; // 1 hour

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
            echo file_get_contents($cacheFile);
            return;
        }

        $url = 'https://wttr.in/Rangsit,Pathum+Thani?format=j1';
        $ctx = stream_context_create(['http' => ['timeout' => 8, 'header' => 'User-Agent: DiscoverRangsit/1.0']]);
        $raw = @file_get_contents($url, false, $ctx);

        if ($raw === false) {
            if (file_exists($cacheFile)) {
                echo file_get_contents($cacheFile);
            } else {
                echo json_encode(['success' => false, 'message' => 'Weather API unavailable']);
            }
            return;
        }

        $w = json_decode($raw, true);
        if (!isset($w['current_condition'][0])) {
            echo json_encode(['success' => false, 'message' => 'Invalid weather data']);
            return;
        }

        $cur  = $w['current_condition'][0];
        $code = (int)($cur['weatherCode'] ?? 113);

        // Weather code → [FA icon, color, Thai desc, English desc]
        $weatherMap = [
            113 => ['fa-sun',            '#f59e0b', 'ท้องฟ้าแจ่มใส',   'Sunny'],
            116 => ['fa-cloud-sun',      '#fb923c', 'มีเมฆบางส่วน',    'Partly Cloudy'],
            119 => ['fa-cloud',          '#94a3b8', 'มีเมฆมาก',        'Cloudy'],
            122 => ['fa-cloud',          '#64748b', 'มืดครึ้ม',         'Overcast'],
            143 => ['fa-smog',           '#a3a3a3', 'หมอก',            'Mist'],
            176 => ['fa-cloud-rain',     '#60a5fa', 'ฝนปรอย',          'Patchy Rain'],
            179 => ['fa-snowflake',      '#93c5fd', 'หิมะปรอย',         'Patchy Sleet'],
            182 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บ',          'Sleet'],
            185 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บเล็กน้อย',  'Light Sleet'],
            200 => ['fa-bolt',           '#a78bfa', 'ฟ้าผ่า',          'Thundery'],
            227 => ['fa-snowflake',      '#bfdbfe', 'หิมะตก',          'Blowing Snow'],
            230 => ['fa-snowflake',      '#bfdbfe', 'หิมะพายุ',         'Blizzard'],
            248 => ['fa-smog',           '#a3a3a3', 'หมอกหนา',         'Fog'],
            260 => ['fa-smog',           '#a3a3a3', 'หมอกน้ำค้าง',      'Freezing Fog'],
            263 => ['fa-cloud-drizzle',  '#60a5fa', 'ฝนปรอยเบา',       'Light Drizzle'],
            266 => ['fa-cloud-drizzle',  '#60a5fa', 'ฝนปรอย',          'Drizzle'],
            281 => ['fa-cloud-sleet',    '#93c5fd', 'ละอองน้ำค้าง',     'Freezing Drizzle'],
            284 => ['fa-cloud-sleet',    '#93c5fd', 'ละอองน้ำค้างหนัก', 'Heavy Freezing Drizzle'],
            293 => ['fa-cloud-rain',     '#60a5fa', 'ฝนเบา',           'Light Rain'],
            296 => ['fa-cloud-rain',     '#60a5fa', 'ฝนเบา',           'Light Rain'],
            299 => ['fa-cloud-showers-heavy', '#3b82f6', 'ฝนปานกลาง',  'Moderate Rain'],
            302 => ['fa-cloud-showers-heavy', '#3b82f6', 'ฝนปานกลาง',  'Moderate Rain'],
            305 => ['fa-cloud-showers-heavy', '#1d4ed8', 'ฝนหนัก',     'Heavy Rain'],
            308 => ['fa-cloud-showers-heavy', '#1d4ed8', 'ฝนหนักมาก',  'Very Heavy Rain'],
            311 => ['fa-cloud-sleet',    '#93c5fd', 'ฝนน้ำแข็ง',       'Light Freezing Rain'],
            314 => ['fa-cloud-sleet',    '#93c5fd', 'ฝนน้ำแข็งหนัก',   'Heavy Freezing Rain'],
            317 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บ',          'Light Sleet'],
            320 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บปานกลาง',  'Moderate Sleet'],
            323 => ['fa-snowflake',      '#bfdbfe', 'หิมะเบา',          'Light Snow'],
            326 => ['fa-snowflake',      '#bfdbfe', 'หิมะเบา',          'Light Snow'],
            329 => ['fa-snowflake',      '#93c5fd', 'หิมะปานกลาง',      'Moderate Snow'],
            332 => ['fa-snowflake',      '#93c5fd', 'หิมะปานกลาง',      'Moderate Snow'],
            335 => ['fa-snowflake',      '#60a5fa', 'หิมะหนัก',         'Heavy Snow'],
            338 => ['fa-snowflake',      '#60a5fa', 'หิมะหนัก',         'Heavy Snow'],
            350 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บน้ำแข็ง',   'Ice Pellets'],
            353 => ['fa-cloud-sun-rain', '#60a5fa', 'ฝนเล็กน้อย',       'Light Rain Shower'],
            356 => ['fa-cloud-showers-heavy', '#3b82f6', 'ฝนหนัก',     'Heavy Rain Shower'],
            359 => ['fa-cloud-showers-heavy', '#1d4ed8', 'ฝนหนักมาก',  'Torrential Shower'],
            362 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บเบา',       'Light Sleet Showers'],
            365 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บ',          'Moderate Sleet Showers'],
            368 => ['fa-snowflake',      '#bfdbfe', 'หิมะเบา',          'Light Snow Showers'],
            371 => ['fa-snowflake',      '#93c5fd', 'หิมะหนัก',         'Heavy Snow Showers'],
            374 => ['fa-cloud-sleet',    '#93c5fd', 'ลูกเห็บน้ำแข็ง',   'Light Ice Pellet Showers'],
            377 => ['fa-cloud-sleet',    '#60a5fa', 'ลูกเห็บน้ำแข็งหนัก', 'Moderate Ice Pellet Showers'],
            386 => ['fa-bolt',           '#a78bfa', 'พายุฝนฟ้าคะนอง',  'Thundery Shower'],
            389 => ['fa-bolt',           '#7c3aed', 'พายุฝนหนัก',      'Heavy Thunder Shower'],
            392 => ['fa-bolt',           '#a78bfa', 'พายุหิมะ',         'Thundery Snow Showers'],
            395 => ['fa-bolt',           '#7c3aed', 'พายุหิมะหนัก',    'Heavy Snow Thundershower'],
        ];

        // Find closest code
        $info = $weatherMap[$code] ?? null;
        if (!$info) {
            // fallback: find nearest key
            $keys  = array_keys($weatherMap);
            $diffs = array_map(fn($k) => abs($k - $code), $keys);
            $info  = $weatherMap[$keys[array_search(min($diffs), $diffs)]];
        }

        $result = [
            'success'     => true,
            'temp_c'      => (int)($cur['temp_C'] ?? 0),
            'feels_like'  => (int)($cur['FeelsLikeC'] ?? 0),
            'humidity'    => (int)($cur['humidity'] ?? 0),
            'wind_kmph'   => (int)($cur['windspeedKmph'] ?? 0),
            'weather_code'=> $code,
            'icon'        => $info[0],
            'color'       => $info[1],
            'desc_th'     => $info[2],
            'desc_en'     => $info[3],
        ];

        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) mkdir($cacheDir, 0755, true);
        file_put_contents($cacheFile, json_encode($result));

        echo json_encode($result);
    }

    public function getAirQuality() {
        header('Content-Type: application/json');
        header('Cache-Control: max-age=1800'); // 30 min browser cache

        $cacheFile = APP_ROOT . '/config/cache/air_quality.json';
        $cacheTTL  = 1800; // 30 minutes

        // Serve from cache if fresh
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
            echo file_get_contents($cacheFile);
            return;
        }

        // Fetch from Air4Thai API
        $apiUrl = 'http://air4thai.pcd.go.th/services/getNewAQI_JSON.php';
        $ctx = stream_context_create(['http' => ['timeout' => 8]]);
        $raw = @file_get_contents($apiUrl, false, $ctx);

        if ($raw === false) {
            // Return cached stale data if available, else error
            if (file_exists($cacheFile)) {
                echo file_get_contents($cacheFile);
            } else {
                echo json_encode(['success' => false, 'message' => 'API unavailable']);
            }
            return;
        }

        $json = json_decode($raw, true);
        if (!isset($json['stations'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid API response']);
            return;
        }

        // Find Rangsit-area station(s)
        $rangsitStation = null;
        foreach ($json['stations'] as $station) {
            $nameEN = $station['nameEN'] ?? '';
            $nameTH = $station['nameTH'] ?? '';
            if (stripos($nameEN, 'Rangsit') !== false || mb_strpos($nameTH, 'รังสิต') !== false) {
                $rangsitStation = $station;
                break;
            }
        }

        if (!$rangsitStation) {
            echo json_encode(['success' => false, 'message' => 'No Rangsit station found']);
            return;
        }

        $aqiLast = $rangsitStation['AQILast'] ?? [];
        $pm25    = $aqiLast['PM25'] ?? [];
        $aqi     = $aqiLast['AQI']  ?? [];

        $colorMap = [
            '0' => ['hex' => '#9e9e9e', 'labelTH' => 'ไม่มีข้อมูล', 'labelEN' => 'No Data'],
            '1' => ['hex' => '#4daf4e', 'labelTH' => 'ดีมาก',       'labelEN' => 'Very Good'],
            '2' => ['hex' => '#a8d08d', 'labelTH' => 'ดี',           'labelEN' => 'Good'],
            '3' => ['hex' => '#ffd700', 'labelTH' => 'ปานกลาง',      'labelEN' => 'Moderate'],
            '4' => ['hex' => '#ff7e00', 'labelTH' => 'เริ่มมีผลต่อสุขภาพ', 'labelEN' => 'Unhealthy for Sensitive'],
            '5' => ['hex' => '#ee3126', 'labelTH' => 'มีผลต่อสุขภาพ', 'labelEN' => 'Unhealthy'],
            '6' => ['hex' => '#8f3f97', 'labelTH' => 'อันตราย',       'labelEN' => 'Hazardous'],
        ];

        $colorId = (string)($pm25['color_id'] ?? '0');
        $color   = $colorMap[$colorId] ?? $colorMap['0'];

        $result = [
            'success'   => true,
            'stationTH' => $rangsitStation['nameTH'] ?? '',
            'stationEN' => $rangsitStation['nameEN'] ?? '',
            'areaTH'    => $rangsitStation['areaTH'] ?? '',
            'areaEN'    => $rangsitStation['areaEN'] ?? '',
            'date'      => $aqiLast['date'] ?? '',
            'time'      => $aqiLast['time'] ?? '',
            'pm25_value'=> $pm25['value'] ?? '-',
            'pm25_aqi'  => $pm25['aqi']   ?? '-',
            'aqi_value' => $aqi['aqi']    ?? '-',
            'color_hex' => $color['hex'],
            'labelTH'   => $color['labelTH'],
            'labelEN'   => $color['labelEN'],
        ];

        // Save to cache
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) mkdir($cacheDir, 0755, true);
        file_put_contents($cacheFile, json_encode($result));

        echo json_encode($result);
    }
}