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

    public function users() {
        $userModel = $this->model('User');
        $users = $userModel->getAllUsers();
        $prefixes = $userModel->getPrefixes();
        $departments = $userModel->getDepartments();
        $stats = $userModel->getStats();

        $this->view('admin/users/index', [
            'title' => 'จัดการผู้ใช้งาน - Admin Dashboard',
            'users' => $users,
            'prefixes' => $prefixes,
            'departments' => $departments,
            'stats' => $stats,
            'current_page' => 'users'
        ]);
    }

    public function categories() {
        $this->view('admin/categories', [
            'title' => 'จัดการหมวดหมู่ธุรกิจ - Admin Dashboard',
            'current_page' => 'categories'
        ]);
    }

    public function places() {
        $placeModel = $this->model('Place');
        $places = $placeModel->getAll();

        $this->view('admin/places/index', [
            'title' => 'จัดการสถานที่และธุรกิจ - Admin Dashboard',
            'places' => $places,
            'current_page' => 'admin_places'
        ]);
    }

    public function placeEdit($id) {
        $placeModel = $this->model('Place');
        $place = $placeModel->getById($id);
        $categories = $placeModel->getCategories();
        $gallery = $placeModel->getGallery($id);

        if (!$place) {
            header('Location: ' . BASE_URL . '/admin/places');
            exit;
        }

        $this->view('admin/places/edit', [
            'title' => 'แก้ไขข้อมูลสถานที่ - Admin Dashboard',
            'place' => $place,
            'categories' => $categories,
            'gallery' => $gallery,
            'current_page' => 'admin_places'
        ]);
    }

    public function userDetail($id) {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);

        if (!$user) {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $this->view('admin/users/detail', [
            'title' => 'รายละเอียดผู้ใช้งาน - Admin Dashboard',
            'user' => $user,
            'current_page' => 'users'
        ]);
    }
}