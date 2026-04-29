<?php
class HomeController extends Controller {
    public function index() {
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved();
        $categories = $placeModel->getCategories();

        $recommendations = [];
        $hasInterests    = false;
        if (isset($_SESSION['user_id'])) {
            try {
                $hasInterests = $placeModel->hasInterests($_SESSION['user_id']);
                if ($hasInterests) {
                    $recommendations = $placeModel->getPersonalizedScore($_SESSION['user_id'], 6);
                    if (empty($recommendations)) {
                        $recommendations = $placeModel->getRecommendations($_SESSION['user_id'], 6);
                    }
                }
            } catch (Exception $e) {
                $hasInterests    = false;
                $recommendations = [];
            }
        }

        $hotNow = [];
        try {
            $hotNow = $placeModel->getHotNow(8, 48);
        } catch (Exception $e) {}

        $this->view('home/index', [
            'title'           => 'Discover Rangsit — ของดีรังสิต ของดีนครรังสิต ร้านค้า ร้านอาหาร คาเฟ่ สถานที่ท่องเที่ยวในเมืองรังสิต',
            'description'     => 'ค้นหาของดีรังสิต ของดีเมืองรังสิต ของดีนครรังสิต ร้านค้ารังสิต ร้านอาหาร คาเฟ่ สถานที่ท่องเที่ยว และบริการในเทศบาลนครรังสิต ครบจบในที่เดียว พร้อมแผนที่และรีวิวจากผู้ใช้จริง',
            'keywords'        => 'ของดีรังสิต, ของดีเมืองรังสิต, ของดีนครรังสิต, ร้านค้ารังสิต, ร้านอาหารรังสิต, คาเฟ่รังสิต, ที่เที่ยวรังสิต, ของเด็ดรังสิต, ของดังรังสิต, ก๋วยเตี๋ยวเรือรังสิต, เทศบาลนครรังสิต, Discover Rangsit',
            'og_url'          => BASE_URL . '/',
            'places'          => $places,
            'categories'      => $categories,
            'recommendations' => $recommendations,
            'has_interests'   => $hasInterests,
            'hot_now'         => $hotNow,
        ]);
    }

    public function lineManual() {
        $this->view('home/line_manual', [
            'title' => 'คู่มือการใช้งาน LINE Login - Discover Rangsit'
        ]);
    }

    public function pr() {
        $this->view('home/pr', [
            'title'       => 'ฝากร้านกับ Discover Rangsit — โปรโมทร้านดังรังสิตของคุณ ฟรี!',
            'description' => 'ร้านค้า ร้านอาหาร คาเฟ่ในรังสิต มาฝากร้านกับ Discover Rangsit ฟรี! โพสต์ข้อมูลร้าน แสดงบนแผนที่ รับรีวิว และเชื่อมต่อลูกค้าได้ง่ายขึ้น',
            'keywords'    => 'ฝากร้านรังสิต, โปรโมทร้านรังสิต, ลงร้านฟรี, Discover Rangsit, ร้านค้ารังสิต, ร้านอาหารรังสิต, เพิ่มร้านค้า',
            'og_image'    => BASE_URL . '/images/rangsit-logo.png',
            'og_url'      => BASE_URL . '/pr',
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