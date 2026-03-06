<?php
class DashboardController extends Controller {
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->view('dashboard/index', [
            'title' => 'Dashboard - Discover Rangsit',
            'current_page' => 'dashboard'
        ]);
    }
}