<?php
class HomeController extends Controller {
    public function index() {
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved();
        $categories = $placeModel->getCategories();

        $this->view('home/index', [
            'title' => 'Discover Rangsit - ค้นพบทุกสิ่งในเมืองรังสิต',
            'places' => $places,
            'categories' => $categories
        ]);
    }

    public function lineManual() {
        $this->view('home/line_manual', [
            'title' => 'คู่มือการใช้งาน LINE Login - Discover Rangsit'
        ]);
    }
}