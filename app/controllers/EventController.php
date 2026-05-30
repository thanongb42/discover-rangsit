<?php
class EventController extends Controller {

    private function requireLogin(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) ||
            !in_array($_SESSION['user_role'], ['admin', 'operator'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    private function ownsPlace(int $placeId): bool {
        $placeModel = $this->model('Place');
        $place      = $placeModel->getById($placeId);
        if (!$place) return false;
        $isOwner    = (int)($place->owner_user_id ?? 0) === (int)$_SESSION['user_id'];
        $isAdmin    = in_array($_SESSION['user_role'] ?? '', ['admin', 'operator']);
        return $isOwner || $isAdmin;
    }

    public function index($id) {
        $this->requireLogin();
        $placeId = (int)$id;

        if (!$this->ownsPlace($placeId)) {
            header('Location: ' . BASE_URL . '/my-businesses');
            exit;
        }

        $placeModel = $this->model('Place');
        $eventModel = $this->model('PlaceEvent');

        $place  = $placeModel->getById($placeId);
        $events = $eventModel->getByPlace($placeId);

        $this->view('places/events', [
            'title'        => 'จัดการโปรโมชั่น — ' . htmlspecialchars($place->name),
            'current_page' => 'my_businesses',
            'place'        => $place,
            'events'       => $events,
        ]);
    }

    public function save(): void {
        header('Content-Type: application/json');
        $this->requireLogin();

        $id       = (int)($_POST['id']          ?? 0);
        $placeId  = (int)($_POST['place_id']    ?? 0);
        $title    = trim($_POST['title']        ?? '');
        $desc     = trim($_POST['description']  ?? '');
        $start    = trim($_POST['start_date']   ?? '');
        $end      = trim($_POST['end_date']     ?? '');
        $status   = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';

        if (!$placeId || !$title || !$start) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบ']);
            return;
        }

        if (!$this->ownsPlace($placeId)) {
            echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์']);
            return;
        }

        $eventModel = $this->model('PlaceEvent');
        $data = [
            'place_id'    => $placeId,
            'title'       => htmlspecialchars($title, ENT_QUOTES),
            'description' => $desc ? htmlspecialchars($desc, ENT_QUOTES) : null,
            'start_date'  => $start,
            'end_date'    => $end ?: null,
            'status'      => $status,
        ];

        if ($id > 0) {
            $event = $eventModel->getById($id);
            if (!$event || (int)$event->place_id !== $placeId) {
                echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูล']);
                return;
            }
            $data['id'] = $id;
            $ok = $eventModel->update($data);
        } else {
            $ok = $eventModel->create($data);
        }

        echo json_encode(['success' => $ok, 'message' => $ok ? 'บันทึกสำเร็จ' : 'เกิดข้อผิดพลาด']);
    }

    public function delete(): void {
        header('Content-Type: application/json');
        $this->requireLogin();

        $id      = (int)($_POST['id']       ?? 0);
        $placeId = (int)($_POST['place_id'] ?? 0);

        if (!$id || !$placeId) {
            echo json_encode(['success' => false, 'message' => 'Invalid']);
            return;
        }

        if (!$this->ownsPlace($placeId)) {
            echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์']);
            return;
        }

        $eventModel = $this->model('PlaceEvent');
        $event      = $eventModel->getById($id);
        if (!$event || (int)$event->place_id !== $placeId) {
            echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูล']);
            return;
        }

        $ok = $eventModel->delete($id);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'ลบสำเร็จ' : 'เกิดข้อผิดพลาด']);
    }

    public function adminList(): void {
        $this->requireAdmin();
        $eventModel = $this->model('PlaceEvent');

        $this->view('admin/events', [
            'title'        => 'โปรโมชั่น / กิจกรรมทั้งหมด - Admin',
            'current_page' => 'events',
            'events'       => $eventModel->getAllWithPlace(),
        ]);
    }
}
