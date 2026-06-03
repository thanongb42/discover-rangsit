<?php
class CivicController extends Controller {

    public function tourism(): void {
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved('tourism');

        $this->view('civic/tourism', [
            'title'       => 'Tourism Rangsit - Discover Rangsit',
            'description' => 'รวมสถานที่ท่องเที่ยว จุดเช็คอิน และแหล่งเรียนรู้ในนครรังสิต',
            'places'      => $places,
        ]);
    }

    public function events(): void {
        $eventModel = $this->model('PlaceEvent');
        $events = $eventModel->getUpcomingPublic(120);

        $this->view('civic/events', [
            'title'       => 'Events Calendar - Discover Rangsit',
            'description' => 'ปฏิทินกิจกรรมและโปรโมชั่นในเมืองรังสิต',
            'events'      => $events,
        ]);
    }

    public function openData(): void {
        $placeModel = $this->model('Place');
        $eventModel = $this->model('PlaceEvent');

        $this->view('civic/open_data', [
            'title'          => 'Open Data - Discover Rangsit',
            'description'    => 'ชุดข้อมูลเปิดของ Discover Rangsit สำหรับต่อยอดด้านเมืองอัจฉริยะ',
            'stats'          => $placeModel->getOpenDataStats(),
            'category_stats' => $placeModel->getCategoryOpenDataStats(),
            'upcoming_events'=> $eventModel->countUpcomingPublic(),
        ]);
    }

    public function openDataPlacesJson(): void {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="discover-rangsit-places.json"');
        $placeModel = $this->model('Place');
        echo json_encode([
            'source' => 'Discover Rangsit',
            'updated_at' => date('c'),
            'license' => 'Public open data for civic and research use',
            'places' => $placeModel->getPublicOpenDataPlaces(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function openDataPlacesCsv(): void {
        $placeModel = $this->model('Place');
        $places = $placeModel->getPublicOpenDataPlaces();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="discover-rangsit-places.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['id', 'name', 'slug', 'category', 'type', 'address', 'latitude', 'longitude', 'phone', 'website', 'rating_avg', 'rating_count', 'views_count']);
        foreach ($places as $p) {
            fputcsv($out, [
                $p->id,
                $p->name,
                $p->slug,
                $p->category_name,
                $p->place_type,
                $p->address,
                $p->latitude,
                $p->longitude,
                $p->phone,
                $p->website,
                $p->rating_avg,
                $p->rating_count,
                $p->views_count,
            ]);
        }
        fclose($out);
    }

    public function refillCity(): void {
        $placeModel = $this->model('Place');
        $kioskModel = $this->model('WaterKiosk');
        $kiosks = $kioskModel->getActive();
        $places = $placeModel->getAllApproved('facility');

        $this->view('civic/refill_city', [
            'title'       => 'Refill City - Discover Rangsit',
            'description' => 'จุดเติมน้ำสะอาดและจุดบริการสาธารณะในเมืองรังสิต',
            'kiosks'      => $kiosks,
            'places'      => $places,
        ]);
    }
}
