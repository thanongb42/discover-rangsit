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

    public function privacy() {
        $this->view('home/privacy', [
            'title' => 'นโยบายความเป็นส่วนตัว - Discover Rangsit'
        ]);
    }

    public function terms() {
        $this->view('home/terms', [
            'title' => 'ข้อกำหนดและเงื่อนไขการใช้งาน - Discover Rangsit'
        ]);
    }
}