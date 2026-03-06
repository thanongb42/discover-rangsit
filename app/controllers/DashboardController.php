<?php
class DashboardController extends Controller {
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $placeModel = $this->model('Place');
        $analyticsModel = $this->model('Analytics');
        
        $viewStats = $placeModel->getViewStats();
        $catStats = $placeModel->getCategoryStats();
        $visitorStats = $analyticsModel->getVisitorStats(7);
        $siteSummary = $analyticsModel->getSummary();

        // General Counts
        $this->db = new Database(); 
        $this->db->query("SELECT COUNT(*) as total FROM places WHERE status = 'approved'");
        $activePlaces = $this->db->single()->total;
        
        $this->db->query("SELECT SUM(views_count) as total FROM places");
        $totalViews = $this->db->single()->total ?: 0;

        $this->view('dashboard/index', [
            'title' => 'Dashboard - Discover Rangsit',
            'current_page' => 'dashboard',
            'viewStats' => $viewStats,
            'catStats' => $catStats,
            'visitorStats' => $visitorStats,
            'siteSummary' => $siteSummary,
            'activePlaces' => $activePlaces,
            'totalViews' => $totalViews
        ]);
    }
}