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

    public function cityDashboard() {
        $placeModel = $this->model('Place');

        $summary        = $placeModel->getCitySummary();
        $categoryStats  = $placeModel->getCategoryStats();
        $topPlaces      = $placeModel->getTopPlacesByViews(10);
        $mapPlaces      = $placeModel->getApprovedWithCoords();
        $newByMonth     = $placeModel->getNewBusinessesByMonth(6);
        $engByMonth     = $placeModel->getEngagementByMonth(6);
        $deliveryAdopt  = $placeModel->getDeliveryAdoption();

        $this->view('admin/city_dashboard', [
            'title'          => 'City Economic Dashboard - เทศบาลนครรังสิต',
            'summary'        => $summary,
            'category_stats' => $categoryStats,
            'top_places'     => $topPlaces,
            'map_places'     => $mapPlaces,
            'new_by_month'   => $newByMonth,
            'eng_by_month'   => $engByMonth,
            'delivery_adopt' => $deliveryAdopt,
            'current_page'   => 'city_dashboard',
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
        $places   = $placeModel->getAll();
        $rejected = $placeModel->getRejected();

        $this->view('admin/places/index', [
            'title'         => 'จัดการสถานที่และธุรกิจ - Admin Dashboard',
            'places'        => $places,
            'rejected'      => $rejected,
            'current_page'  => 'admin_places'
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

    public function mapSettings() {
        $settingsFile = APP_ROOT . '/config/map_settings.json';
        $settings = json_decode(file_get_contents($settingsFile), true);

        $this->view('admin/map_settings', [
            'title' => 'ตั้งค่าแผนที่ - Admin Dashboard',
            'settings' => $settings,
            'current_page' => 'map_settings'
        ]);
    }

    public function settings() {
        $file = APP_ROOT . '/config/site_settings.json';
        $settings = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];

        $this->view('admin/settings', [
            'title'        => 'ตั้งค่าระบบ - Admin Dashboard',
            'settings'     => $settings,
            'current_page' => 'settings'
        ]);
    }

    public function logs() {
        $logModel = $this->model('ActivityLog');
        
        // Pagination & Filters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'action' => $_GET['action'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        $logs = $logModel->getFiltered($filters, $limit, $offset);
        $totalLogs = $logModel->countFiltered($filters);
        $totalPages = ceil($totalLogs / $limit);
        $actions = $logModel->getDistinctActions();

        $this->view('admin/logs', [
            'title' => 'ประวัติการใช้งานระบบ - Admin Dashboard',
            'logs' => $logs,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalLogs' => $totalLogs,
            'filters' => $filters,
            'actions' => $actions,
            'current_page' => 'logs'
        ]);
    }
}