<?php
class MapController extends Controller {
    public function index() {
        $this->view('map/index', [
            'title' => 'Discover Rangsit - Smart City Platform'
        ]);
    }
}