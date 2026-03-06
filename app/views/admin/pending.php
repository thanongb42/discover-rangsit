<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">รอการอนุมัติ</h1>
        <p class="text-gray-500 text-sm">ตรวจสอบธุรกิจที่ยื่นขอแสดงผลบนแผนที่เมืองรังสิต</p>
    </div>
    <span class="bg-red-100 text-red-600 px-4 py-1 rounded-full text-sm font-bold border border-red-200">
        <?= count($data['places']) ?> รายการ
    </span>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg">
        <p class="text-sm text-green-700"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if(empty($data['places'])): ?>
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-check-double"></i>
            </div>
            <h4 class="text-gray-700 font-semibold">ไม่มีรายการค้างอนุมัติ</h4>
            <p class="text-gray-400 text-sm mt-1">ข้อมูลบนแผนที่อัปเดตเป็นปัจจุบันเรียบร้อยแล้ว</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ธุรกิจ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">หมวดหมู่</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">เจ้าของ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">วันที่ส่ง</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($data['places'] as $place): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden mr-4 border border-gray-100">
                                        <img src="<?= BASE_URL ?>/../uploads/covers/<?= $place->cover_image ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800"><?= htmlspecialchars($place->name) ?></p>
                                        <p class="text-xs text-gray-400 truncate w-48"><?= htmlspecialchars($place->address) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-primary-50 text-primary-700 px-2 py-1 rounded text-xs font-semibold border border-primary-100">
                                    <?= htmlspecialchars($place->category_name) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                <?= htmlspecialchars($place->owner_name) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?= date('d/m/Y', strtotime($place->created_at)) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="<?= BASE_URL ?>/admin/approve" method="POST">
                                        <input type="hidden" name="place_id" value="<?= $place->id ?>">
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                            อนุมัติ
                                        </button>
                                    </form>
                                    <form action="<?= BASE_URL ?>/admin/reject" method="POST">
                                        <input type="hidden" name="place_id" value="<?= $place->id ?>">
                                        <button class="bg-white hover:bg-red-50 text-red-500 px-3 py-1.5 rounded-lg text-xs font-bold transition border border-red-100">
                                            ปฏิเสธ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>