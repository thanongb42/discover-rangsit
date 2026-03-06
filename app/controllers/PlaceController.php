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
        
        // Fetch reviews
        $reviews = $placeModel->getReviews($place->id);

        $this->view('places/detail', [
            'title' => $place->name . ' - Discover Rangsit',
            'place' => $place,
            'reviews' => $reviews
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
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name']))),
                'description' => trim($_POST['description']),
                'category_id' => $_POST['category_id'],
                'address' => trim($_POST['address']),
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude'],
                'phone' => trim($_POST['phone']),
                'website' => trim($_POST['website']),
                'facebook' => trim($_POST['facebook']),
                'line' => trim($_POST['line']),
                'instagram' => trim($_POST['instagram']),
                'tiktok' => trim($_POST['tiktok']),
                'x' => '', // Default empty for public form unless added
                'youtube' => '', 
                'owner_user_id' => $_SESSION['user_id'],
                'cover_image' => 'default.jpg',
                'line_qr' => NULL
            ];

            // Handle Cover Image Upload
            if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_cover.' . $ext;
                $target = APP_ROOT . '/public/uploads/covers/' . $filename;
                if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $data['cover_image'] = $filename;
                }
            }

            // Handle LINE QR Upload
            if(isset($_FILES['line_qr']) && $_FILES['line_qr']['error'] == 0) {
                $ext = pathinfo($_FILES['line_qr']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_lineqr.' . $ext;
                $target = APP_ROOT . '/public/uploads/gallery/' . $filename;
                if(move_uploaded_file($_FILES['line_qr']['tmp_name'], $target)) {
                    $data['line_qr'] = $filename;
                }
            }

            $placeModel = $this->model('Place');
            if($placeModel->add($data)) {
                $_SESSION['success'] = 'Business submitted successfully and is pending approval.';
                header('Location: ' . BASE_URL . '/dashboard');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function rate() {
        // Implement rating logic
    }
}