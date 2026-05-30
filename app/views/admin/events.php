<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
$events = $data['events'] ?? [];
$today  = date('Y-m-d');
$active = array_filter($events, fn($e) => $e->status === 'active' && (!$e->end_date || $e->end_date >= $today));
?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">โปรโมชั่น / กิจกรรม</h1>
        <p class="text-gray-500 text-sm">โปรโมชั่นและกิจกรรมจากร้านค้าทั้งหมด</p>
    </div>
    <span class="bg-orange-100 text-orange-700 px-4 py-1 rounded-full text-sm font-bold border border-orange-200">
        <?= count($active) ?> กำลังใช้งาน
    </span>
</div>

<?php if (empty($events)): ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
    <i class="fas fa-tag text-4xl text-gray-200 mb-3 block"></i>
    <p class="text-gray-400">ยังไม่มีโปรโมชั่น</p>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ร้านค้า</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">โปรโมชั่น</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ระยะเวลา</th>
                    <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">สถานะ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($events as $ev):
                    $expired = $ev->end_date && $ev->end_date < $today;
                    $isLive  = $ev->status === 'active' && !$expired;
                ?>
                <tr class="hover:bg-gray-50 transition <?= $expired ? 'opacity-50' : '' ?>">
                    <td class="px-5 py-3">
                        <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($ev->place_slug) ?>" target="_blank"
                           class="font-bold text-gray-700 hover:text-[#0088CC] text-sm">
                            <?= htmlspecialchars($ev->place_name) ?>
                        </a>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($ev->title) ?></p>
                        <?php if ($ev->description): ?>
                        <p class="text-xs text-gray-400 truncate max-w-xs"><?= htmlspecialchars($ev->description) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        <?= date('d/m/Y', strtotime($ev->start_date)) ?>
                        <?= $ev->end_date ? ' — ' . date('d/m/Y', strtotime($ev->end_date)) : ' (ไม่มีวันสิ้นสุด)' ?>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <?php if ($expired): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-400">หมดอายุ</span>
                        <?php elseif ($isLive): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">กำลังใช้งาน</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-400">ปิดใช้งาน</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
