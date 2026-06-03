<?php
class MapController extends Controller {
    public function index() {
        $this->view('map/index', [
            'title' => 'Discover Rangsit - Smart City Platform'
        ]);
    }

    public function threeD() {
        $this->view('map/three_d', [
            'title' => 'Discover Rangsit - 3D City Map'
        ]);
    }
}
