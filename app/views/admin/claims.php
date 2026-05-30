<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php $claims = $data['claims'] ?? []; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">คำขอเป็นเจ้าของร้าน</h1>
        <p class="text-gray-500 text-sm">ตรวจสอบและอนุมัติคำขอ Business Claim จากผู้ประกอบการ</p>
    </div>
    <?php $pendingCount = count(array_filter($claims, fn($c) => $c->status === 'pending')); ?>
    <?php if ($pendingCount > 0): ?>
    <span class="bg-amber-100 text-amber-700 px-4 py-1 rounded-full text-sm font-bold border border-amber-200">
        <?= $pendingCount ?> รอตรวจสอบ
    </span>
    <?php endif; ?>
</div>

<?php if (empty($claims)): ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
    <div class="w-20 h-20 bg-blue-50 text-[#0088CC] rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
        <i class="fas fa-handshake"></i>
    </div>
    <h4 class="text-gray-700 font-semibold">ยังไม่มีคำขอ</h4>
    <p class="text-gray-400 text-sm mt-1">เมื่อผู้ประกอบการส่งคำขอจะแสดงที่นี่</p>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ร้านค้า</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ผู้ยื่น</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ข้อมูลติดต่อ</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">วันที่</th>
                    <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">สถานะ</th>
                    <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="claimsTableBody">
                <?php foreach ($claims as $claim): ?>
                <tr class="hover:bg-gray-50 transition" id="claim-row-<?= $claim->id ?>">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                <img src="<?= BASE_URL ?>/../uploads/covers/<?= htmlspecialchars($claim->cover_image ?: 'default.jpg') ?>"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='<?= BASE_URL ?>/images/rangsit-logo.png'">
                            </div>
                            <div>
                                <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($claim->place_slug) ?>" target="_blank"
                                   class="font-bold text-gray-800 hover:text-[#0088CC] text-sm">
                                    <?= htmlspecialchars($claim->place_name) ?>
                                </a>
                                <?php if (!empty($claim->owner_user_id)): ?>
                                <p class="text-[10px] text-amber-600 font-bold">มีเจ้าของแล้ว</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-700 font-medium">
                        <?= htmlspecialchars($claim->user_name) ?>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-gray-700"><?= htmlspecialchars($claim->contact_name) ?></p>
                        <p class="text-xs text-gray-400"><?= htmlspecialchars($claim->phone) ?></p>
                        <?php if ($claim->line_id): ?>
                        <p class="text-xs text-green-600 font-bold">LINE: <?= htmlspecialchars($claim->line_id) ?></p>
                        <?php endif; ?>
                        <?php if ($claim->message): ?>
                        <p class="text-[10px] text-gray-400 mt-0.5 max-w-[180px] truncate" title="<?= htmlspecialchars($claim->message) ?>">
                            "<?= htmlspecialchars($claim->message) ?>"
                        </p>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-400">
                        <?= date('d/m/Y', strtotime($claim->created_at)) ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <?php if ($claim->status === 'pending'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">รอตรวจสอบ</span>
                        <?php elseif ($claim->status === 'approved'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">อนุมัติแล้ว</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">ปฏิเสธ</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <?php if ($claim->status === 'pending'): ?>
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="processClaim(<?= $claim->id ?>, 'approve')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                <i class="fas fa-check mr-1"></i> อนุมัติ
                            </button>
                            <button onclick="processClaim(<?= $claim->id ?>, 'reject')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                <i class="fas fa-times mr-1"></i> ปฏิเสธ
                            </button>
                        </div>
                        <?php else: ?>
                        <span class="text-xs text-gray-300">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
async function processClaim(claimId, action) {
    const isApprove = action === 'approve';
    const confirm   = await Swal.fire({
        title:             isApprove ? 'อนุมัติคำขอนี้?' : 'ปฏิเสธคำขอนี้?',
        text:              isApprove ? 'ผู้ยื่นจะได้รับสิทธิ์เป็นเจ้าของร้าน' : 'คำขอจะถูกปฏิเสธ',
        icon:              isApprove ? 'question' : 'warning',
        showCancelButton:  true,
        confirmButtonColor: isApprove ? '#22c55e' : '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: isApprove ? 'อนุมัติ' : 'ปฏิเสธ',
        cancelButtonText:  'ยกเลิก',
    });
    if (!confirm.isConfirmed) return;

    const url  = '<?= BASE_URL ?>/api/admin/claims/' + action;
    const body = new FormData();
    body.append('claim_id', claimId);

    try {
        const res  = await fetch(url, { method: 'POST', body });
        const json = await res.json();
        if (json.success) {
            Swal.fire({ icon: 'success', title: json.message, timer: 1500, showConfirmButton: false });
            const row = document.getElementById('claim-row-' + claimId);
            if (row) row.style.opacity = '0.4';
            setTimeout(() => location.reload(), 1600);
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: json.message });
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'กรุณาลองใหม่' });
    }
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
