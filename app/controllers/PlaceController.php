<?php
class PlaceController extends Controller {

    public function trending() {
        $placeModel = $this->model('Place');
        $trendingPlaces = $placeModel->getTrending();

        $this->view('places/trending', [
            'title' => 'Trending Places - Discover Rangsit',
            'places' => $trendingPlaces
        ]);
    }

    public function detail($slug) {
        $placeModel = $this->model('Place');
        // We need a getBySlug method in Place model
        $place = $placeModel->getBySlug($slug);

        if (!$place) {
            die("Place not found");
        }

        // Add view count
        $placeModel->addView($place->id, $_SERVER['REMOTE_ADDR']);

        // Track category interest for logged-in users
        if (isset($_SESSION['user_id']) && !empty($place->category_id)) {
            $placeModel->trackInterest($_SESSION['user_id'], $place->category_id);
        }

        // Fetch reviews
        $reviews = $placeModel->getReviews($place->id);

        // Like data
        $likeCount = $placeModel->getLikeCount($place->id);
        $hasLiked  = isset($_SESSION['user_id']) ? $placeModel->hasLiked($place->id, $_SESSION['user_id']) : false;

        // Build SEO description from place data
        $seoDesc = trim(strip_tags($place->description ?? ''));
        if (empty($seoDesc)) {
            $seoDesc = $place->name . ' - ' . ($place->category_name ?? '') . ' ใน' . ($place->address ?? 'เมืองรังสิต');
        }
        $seoDesc = mb_substr($seoDesc, 0, 160);

        $this->view('places/detail', [
            'title'       => $place->name . ' - Discover Rangsit',
            'description' => $seoDesc,
            'keywords'    => $place->name . ', ' . ($place->category_name ?? '') . ', รังสิต, Discover Rangsit, ' . ($place->address ?? ''),
            'og_type'     => 'business.business',
            'og_image'    => BASE_URL . '/uploads/covers/' . ($place->cover_image ?: 'default.jpg'),
            'og_url'      => BASE_URL . '/place/' . $place->slug,
            'place'       => $place,
            'reviews'     => $reviews,
            'like_count'  => $likeCount,
            'has_liked'   => $hasLiked,
        ]);
    }

    public function create() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $placeModel = $this->model('Place');
        $categories = $placeModel->getCategories();

        $this->view('places/create', [
            'title' => 'Add New Business - Discover Rangsit',
            'categories' => $categories
        ]);
    }

    public function store() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if POST is empty (likely exceeded post_max_size)
            if (empty($_POST) && !empty($_SERVER['CONTENT_LENGTH'])) {
                $_SESSION['error'] = 'ขนาดไฟล์รูปภาพใหญ่เกินไป (รวมกันห้ามเกิน 8MB) กรุณาลดขนาดรูปภาพก่อนอัปโหลด';
                header('Location: ' . BASE_URL . '/dashboard/add-place');
                exit;
            }

            // Slug generation supporting Thai and avoiding empty values
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $_SESSION['error'] = 'กรุณาระบุชื่อธุรกิจ';
                header('Location: ' . BASE_URL . '/dashboard/add-place');
                exit;
            }

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            if (empty($slug) || $slug === '-') {
                $slug = 'place-' . time() . '-' . rand(100, 999);
            }

            $data = [
                'name' => $name,
                'slug' => $slug,
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $_POST['category_id'] ?? null,
                'address' => trim($_POST['address'] ?? ''),
                'latitude' => !empty($_POST['latitude']) ? $_POST['latitude'] : NULL,
                'longitude' => !empty($_POST['longitude']) ? $_POST['longitude'] : NULL,
                'phone' => trim($_POST['phone'] ?? ''),
                'website' => trim($_POST['website'] ?? ''),
                'facebook' => trim($_POST['facebook'] ?? ''),
                'line' => trim($_POST['line'] ?? ''),
                'instagram' => trim($_POST['instagram'] ?? ''),
                'tiktok' => trim($_POST['tiktok'] ?? ''),
                'x' => '', 
                'youtube' => '', 
                'owner_user_id' => $_SESSION['user_id'],
                'cover_image' => 'default.jpg',
                'line_qr' => NULL
            ];

            // Handle Cover Image Upload (Prioritize Base64 from client-side resizing)
            if(!empty($_POST['cover_base64'])) {
                $base64Data = $_POST['cover_base64'];
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $ext = strtolower($type[1]); // jpg, png, etc
                    $filename = time() . '_cover.' . $ext;
                    $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                    
                    if(file_put_contents($target, base64_decode($base64Data))) {
                        $data['cover_image'] = $filename;
                    }
                }
            } else if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
                $filename = time() . '_cover.' . $ext;
                $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                
                if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $data['cover_image'] = $filename;
                }
            }

            // Handle LINE QR Upload (Prioritize Base64)
            if(!empty($_POST['qr_base64'])) {
                $base64Data = $_POST['qr_base64'];
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $ext = strtolower($type[1]);
                    $filename = time() . '_lineqr.' . $ext;
                    $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                    
                    if(file_put_contents($target, base64_decode($base64Data))) {
                        $data['line_qr'] = $filename;
                    }
                }
            } else if(isset($_FILES['line_qr']) && $_FILES['line_qr']['error'] == 0) {
                $ext = strtolower(pathinfo($_FILES['line_qr']['name'], PATHINFO_EXTENSION));
                $filename = time() . '_lineqr.' . $ext;
                $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                
                if(move_uploaded_file($_FILES['line_qr']['tmp_name'], $target)) {
                    $data['line_qr'] = $filename;
                }
            }

            $placeModel = $this->model('Place');
            
            try {
                if($placeModel->add($data)) {
                    $this->logActivity('PLACE_CREATE', "User submitted new business: " . $name);
                    $_SESSION['success'] = 'บันทึกข้อมูลธุรกิจเรียบร้อยแล้ว กรุณารอเจ้าหน้าที่ตรวจสอบและอนุมัติ';
                    header('Location: ' . BASE_URL . '/dashboard');
                    exit;
                } else {
                    $_SESSION['error'] = 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง';
                    header('Location: ' . BASE_URL . '/dashboard/add-place');
                    exit;
                }
            } catch (Exception $e) {
                $this->logActivity('ERROR', "Failed to create place: " . $e->getMessage());
                $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                header('Location: ' . BASE_URL . '/dashboard/add-place');
                exit;
            }
        } else {
            // Not a POST request
            header('Location: ' . BASE_URL . '/dashboard/add-place');
            exit;
        }
    }

    public function rate() {
        // Implement rating logic
    }
}