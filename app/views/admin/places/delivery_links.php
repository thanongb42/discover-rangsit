<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
$place    = $data['place'];
$platforms = $data['platforms'];
$linksMap  = $data['links_map'];
$stats     = $data['stats'];
$isAdmin   = $_SESSION['user_role'] === 'admin';
$backUrl   = $isAdmin
    ? BASE_URL . '/admin/places/edit/' . $place->id
    : BASE_URL . '/dashboard/edit-place/' . $place->id;

$statsMap = [];
foreach ($stats as $s) { $statsMap[$s->platform] = $s; }
?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Delivery Links</h1>
        <p class="text-gray-500 text-sm"><?= htmlspecialchars($place->name) ?> — จัดการลิงก์สั่งอาหาร/จัดส่ง</p>
    </div>
    <a href="<?= $backUrl ?>" class="text-gray-500 hover:text-gray-800 font-bold flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i> กลับแก้ไขข้อมูล
    </a>
</div>

<!-- Stats Bar -->
<?php if (!empty($stats)): ?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php foreach ($stats as $s):
        $cfg = $platforms[$s->platform] ?? null;
        if (!$cfg) continue;
    ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-lg flex-shrink-0" style="background:<?= $cfg['color'] ?>">
            <i class="<?= $cfg['icon'] ?>"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase"><?= $cfg['name'] ?></p>
            <p class="text-xl font-black text-gray-800"><?= number_format($s->click_count) ?></p>
            <p class="text-[10px] text-gray-400"><?= number_format($s->clicks_30d) ?> คลิก / 30 วัน</p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Platform Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($platforms as $key => $cfg):
        $existing = $linksMap[$key] ?? null;
    ?>
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" id="card-<?= $key ?>">
        <!-- Card Header -->
        <div class="px-6 py-4 flex items-center gap-4 border-b border-gray-50" style="background: <?= $cfg['color'] ?>18">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-lg flex-shrink-0" style="background:<?= $cfg['color'] ?>">
                <i class="<?= $cfg['icon'] ?>"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800"><?= $cfg['name'] ?></h3>
                <?php if (isset($statsMap[$key])): ?>
                <p class="text-xs text-gray-400"><?= number_format($statsMap[$key]->click_count) ?> คลิกทั้งหมด</p>
                <?php endif; ?>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="active-<?= $key ?>" <?= ($existing && $existing->is_active) ? 'checked' : '' ?> class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                <span class="ml-2 text-xs font-bold text-gray-500">เปิดใช้</span>
            </label>
        </div>

        <div class="p-6 space-y-4">
            <!-- URL Input -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">URL หน้าร้าน</label>
                <input type="url" id="url-<?= $key ?>" value="<?= htmlspecialchars($existing->url ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500 transition"
                       placeholder="<?= htmlspecialchars($cfg['url_hint'], ENT_QUOTES, 'UTF-8') ?>">
                <p class="text-[10px] text-gray-400 mt-1">ตัวอย่าง: <?= htmlspecialchars($cfg['url_hint'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <!-- Label Input -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">ข้อความปุ่ม (ไม่บังคับ)</label>
                <input type="text" id="label-<?= $key ?>" value="<?= htmlspecialchars($existing->display_label ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500 transition"
                       placeholder="เช่น สั่งผ่าน <?= $cfg['name'] ?>">
            </div>
            <!-- Buttons -->
            <div class="flex gap-2 pt-2">
                <button onclick="saveLink('<?= $key ?>', <?= $place->id ?>)"
                        class="flex-1 text-white py-2.5 rounded-xl font-bold text-sm transition shadow-sm"
                        style="background:<?= $cfg['color'] ?>; hover:opacity-90">
                    <i class="fas fa-save mr-1"></i> บันทึก
                </button>
                <?php if ($existing): ?>
                <button onclick="deleteLink('<?= $key ?>', <?= $place->id ?>)"
                        class="w-10 h-10 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-trash text-sm"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
async function saveLink(platform, placeId) {
    const url      = document.getElementById('url-' + platform).value.trim();
    const label    = document.getElementById('label-' + platform).value.trim();
    const isActive = document.getElementById('active-' + platform).checked ? 1 : 0;

    const fd = new FormData();
    fd.append('place_id',  placeId);
    fd.append('platform',  platform);
    fd.append('url',       url);
    fd.append('label',     label);
    fd.append('is_active', isActive);

    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const res = await fetch('<?= BASE_URL ?>/api/delivery/save', { method: 'POST', body: fd });
    const data = await res.json();

    if (data.success) {
        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: data.message, timer: 1800, showConfirmButton: false });
        setTimeout(() => location.reload(), 1800);
    } else {
        Swal.fire('ข้อผิดพลาด', data.message, 'error');
    }
}

async function deleteLink(platform, placeId) {
    const confirm = await Swal.fire({
        title: 'ลบลิงก์นี้?',
        text: 'จะลบออกจากหน้าร้านทันที',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#ef4444',
    });
    if (!confirm.isConfirmed) return;

    const fd = new FormData();
    fd.append('place_id', placeId);
    fd.append('platform', platform);

    const res = await fetch('<?= BASE_URL ?>/api/delivery/delete', { method: 'POST', body: fd });
    const data = await res.json();

    if (data.success) {
        Swal.fire({ icon: 'success', title: 'ลบแล้ว', timer: 1200, showConfirmButton: false });
        setTimeout(() => location.reload(), 1200);
    } else {
        Swal.fire('ข้อผิดพลาด', data.message, 'error');
    }
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
