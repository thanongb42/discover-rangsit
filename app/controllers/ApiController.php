<?php
class ApiController extends Controller {
    
    public function places() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved();
        echo json_encode($places);
    }

    public function categories() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $cats = $placeModel->getCategories();
        echo json_encode($cats);
    }

    public function search() {
        header('Content-Type: application/json');
        if (isset($_GET['q'])) {
            $keyword = trim($_GET['q']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $placeModel = $this->model('Place');
            $placeModel->logSearch($keyword, $ip);
            echo json_encode(['status' => 'logged']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No query provided']);
        }
    }

    public function topPlaces() {
        header('Content-Type: application/json');
        $placeModel = $this->model('Place');
        $trending = $placeModel->getTrending();
        echo json_encode($trending);
    }
}