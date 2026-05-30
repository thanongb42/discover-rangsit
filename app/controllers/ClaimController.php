<?php
class ClaimController extends Controller {

    public function submit() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
            return;
        }

        $placeId     = (int)($_POST['place_id']     ?? 0);
        $contactName = trim($_POST['contact_name']  ?? '');
        $phone       = trim($_POST['phone']         ?? '');
        $lineId      = trim($_POST['line_id']       ?? '');
        $message     = trim($_POST['message']       ?? '');
        $userId      = (int)$_SESSION['user_id'];

        if (!$placeId || !$contactName || !$phone) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            return;
        }

        $placeModel = $this->model('Place');
        $place      = $placeModel->getById($placeId);

        if (!$place) {
            echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลสถานที่']);
            return;
        }

        if (!empty($place->owner_user_id)) {
            echo json_encode(['success' => false, 'message' => 'สถานที่นี้มีเจ้าของแล้ว']);
            return;
        }

        $claimModel  = $this->model('Claim');
        $existing    = $claimModel->getByPlaceAndUser($placeId, $userId);

        if ($existing) {
            $msg = $existing->status === 'pending'
                ? 'คุณได้ยื่นคำขอสำหรับร้านนี้แล้ว กำลังรอการตรวจสอบ'
                : 'คุณเคยยื่นคำขอสำหรับร้านนี้แล้ว';
            echo json_encode(['success' => false, 'message' => $msg]);
            return;
        }

        $result = $claimModel->create([
            'place_id'     => $placeId,
            'user_id'      => $userId,
            'contact_name' => htmlspecialchars($contactName, ENT_QUOTES),
            'phone'        => htmlspecialchars($phone, ENT_QUOTES),
            'line_id'      => $lineId ? htmlspecialchars($lineId, ENT_QUOTES) : null,
            'message'      => $message ? htmlspecialchars($message, ENT_QUOTES) : null,
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'ส่งคำขอเรียบร้อยแล้ว เจ้าหน้าที่จะตรวจสอบภายใน 3-5 วันทำการ']);
        } else {
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่']);
        }
    }

    public function adminList() {
        if (!isset($_SESSION['user_id']) ||
            !in_array($_SESSION['user_role'], ['admin', 'operator'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $claimModel = $this->model('Claim');
        $claims     = $claimModel->getAll();

        $this->view('admin/claims', [
            'title'        => 'คำขอเป็นเจ้าของร้าน - Admin Dashboard',
            'claims'       => $claims,
            'current_page' => 'claims',
        ]);
    }

    public function approve() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $claimId = (int)($_POST['claim_id'] ?? 0);
        if (!$claimId) {
            echo json_encode(['success' => false, 'message' => 'Invalid claim']);
            return;
        }

        $claimModel = $this->model('Claim');
        $claim      = $claimModel->getById($claimId);

        if (!$claim) {
            echo json_encode(['success' => false, 'message' => 'ไม่พบคำขอ']);
            return;
        }

        $claimModel->approve($claimId, (int)$_SESSION['user_id']);

        $placeModel = $this->model('Place');
        $placeModel->setOwner($claim->place_id, $claim->user_id);

        echo json_encode(['success' => true, 'message' => 'อนุมัติคำขอเรียบร้อยแล้ว']);
    }

    public function reject() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $claimId = (int)($_POST['claim_id'] ?? 0);
        if (!$claimId) {
            echo json_encode(['success' => false, 'message' => 'Invalid claim']);
            return;
        }

        $claimModel = $this->model('Claim');
        $ok         = $claimModel->reject($claimId, (int)$_SESSION['user_id']);

        echo json_encode(['success' => $ok, 'message' => $ok ? 'ปฏิเสธคำขอแล้ว' : 'เกิดข้อผิดพลาด']);
    }
}
