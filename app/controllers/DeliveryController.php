<?php
require_once APP_ROOT . '/app/helpers/DeliveryPlatforms.php';

class DeliveryController extends Controller
{
    private function _requireOwnerOrAdmin(int $placeId): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $isAdmin = $_SESSION['user_role'] === 'admin';
        if ($isAdmin) return;

        $placeModel = $this->model('Place');
        $place = $placeModel->getById($placeId);
        if (!$place || (int)$place->owner_user_id !== (int)$_SESSION['user_id']) {
            $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
            header('Location: ' . BASE_URL . '/my-businesses');
            exit;
        }
    }

    public function index(int $id): void
    {
        $this->_requireOwnerOrAdmin($id);

        $placeModel    = $this->model('Place');
        $deliveryModel = $this->model('DeliveryLink');

        $place     = $placeModel->getById($id);
        $links     = $deliveryModel->getByPlaceAdmin($id);
        $stats     = $deliveryModel->getClickStats($id);
        $platforms = DeliveryPlatforms::all();

        $linksMap = [];
        foreach ($links as $link) {
            $linksMap[$link->platform] = $link;
        }

        $isAdmin      = $_SESSION['user_role'] === 'admin';
        $current_page = $isAdmin ? 'admin_places' : 'my_businesses';

        $this->view('admin/places/delivery_links', [
            'title'        => 'Delivery Links — ' . htmlspecialchars($place->name),
            'place'        => $place,
            'platforms'    => $platforms,
            'links_map'    => $linksMap,
            'stats'        => $stats,
            'current_page' => $current_page,
        ]);
    }

    public function save(): void
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $placeId  = (int)($_POST['place_id'] ?? 0);
        $platform = preg_replace('/[^a-z]/', '', strtolower($_POST['platform'] ?? ''));
        $url      = trim($_POST['url'] ?? '');
        $label    = trim($_POST['label'] ?? '') ?: null;
        $isActive = (bool)($_POST['is_active'] ?? 0);
        $sort     = (int)($_POST['sort_order'] ?? 0);

        if (!$placeId || !$platform) {
            echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
            return;
        }

        $this->_requireOwnerOrAdmin($placeId);

        $cfg = DeliveryPlatforms::get($platform);
        if (!$cfg) {
            echo json_encode(['success' => false, 'message' => 'แพลตฟอร์มไม่รองรับ']);
            return;
        }

        if (!empty($url) && !DeliveryPlatforms::validateUrl($platform, $url)) {
            echo json_encode(['success' => false, 'message' => 'URL ของ ' . $cfg['name'] . ' ไม่ถูกต้อง ตัวอย่าง: ' . $cfg['url_hint']]);
            return;
        }

        $model = $this->model('DeliveryLink');

        if (empty($url)) {
            $model->delete($placeId, $platform);
            echo json_encode(['success' => true, 'message' => 'ลบ ' . $cfg['name'] . ' แล้ว']);
            return;
        }

        if ($model->upsert($placeId, $platform, $url, $label, $isActive, $sort)) {
            $this->logActivity('DELIVERY_LINK_SAVE', "Saved delivery link: {$platform} for place {$placeId}");
            echo json_encode(['success' => true, 'message' => 'บันทึก ' . $cfg['name'] . ' เรียบร้อย']);
        } else {
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่']);
        }
    }

    public function delete(): void
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $placeId  = (int)($_POST['place_id'] ?? 0);
        $platform = preg_replace('/[^a-z]/', '', strtolower($_POST['platform'] ?? ''));

        if (!$placeId || !$platform) {
            echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
            return;
        }

        $this->_requireOwnerOrAdmin($placeId);

        $model = $this->model('DeliveryLink');
        $model->delete($placeId, $platform);
        $this->logActivity('DELIVERY_LINK_DELETE', "Deleted delivery link: {$platform} for place {$placeId}");
        echo json_encode(['success' => true, 'message' => 'ลบเรียบร้อย']);
    }
}
