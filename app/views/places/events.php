<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
$place  = $data['place'];
$events = $data['events'] ?? [];
$today  = date('Y-m-d');
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="<?= BASE_URL ?>/my-businesses" class="text-xs text-gray-400 hover:text-gray-600 font-bold flex items-center gap-1 mb-1">
            <i class="fas fa-chevron-left text-[10px]"></i> ธุรกิจของฉัน
        </a>
        <h1 class="text-2xl font-bold text-gray-800">โปรโมชั่น / กิจกรรม</h1>
        <p class="text-gray-500 text-sm"><?= htmlspecialchars($place->name) ?></p>
    </div>
    <button onclick="openEventForm()"
            class="bg-[#0088CC] hover:bg-[#005A8E] text-white px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
        <i class="fas fa-plus"></i> เพิ่มโปรโมชั่น
    </button>
</div>

<?php if (empty($events)): ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
    <div class="w-20 h-20 bg-orange-50 text-orange-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
        <i class="fas fa-tag"></i>
    </div>
    <h4 class="text-gray-700 font-semibold">ยังไม่มีโปรโมชั่น</h4>
    <p class="text-gray-400 text-sm mt-1 mb-5">เพิ่มโปรโมชั่นหรือกิจกรรมเพื่อดึงดูดลูกค้า</p>
    <button onclick="openEventForm()" class="bg-[#0088CC] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#005A8E] transition">
        เพิ่มโปรโมชั่นแรก
    </button>
</div>
<?php else: ?>
<div class="space-y-3">
    <?php foreach ($events as $ev):
        $isActive  = $ev->status === 'active';
        $isExpired = $ev->end_date && $ev->end_date < $today;
    ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 <?= $isActive && !$isExpired ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-400' ?>">
            <i class="fas fa-tag"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                <h4 class="font-bold text-gray-800"><?= htmlspecialchars($ev->title) ?></h4>
                <?php if ($isExpired): ?>
                    <span class="text-[10px] bg-gray-100 text-gray-400 font-bold px-2 py-0.5 rounded-full">หมดอายุ</span>
                <?php elseif ($isActive): ?>
                    <span class="text-[10px] bg-green-100 text-green-600 font-bold px-2 py-0.5 rounded-full">กำลังใช้งาน</span>
                <?php else: ?>
                    <span class="text-[10px] bg-gray-100 text-gray-400 font-bold px-2 py-0.5 rounded-full">ปิดใช้งาน</span>
                <?php endif; ?>
            </div>
            <?php if ($ev->description): ?>
            <p class="text-sm text-gray-500 mb-1"><?= htmlspecialchars($ev->description) ?></p>
            <?php endif; ?>
            <p class="text-xs text-gray-400">
                <i class="fas fa-calendar-alt mr-1"></i>
                <?= date('d/m/Y', strtotime($ev->start_date)) ?>
                <?= $ev->end_date ? ' — ' . date('d/m/Y', strtotime($ev->end_date)) : '' ?>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button onclick="openEventForm(<?= htmlspecialchars(json_encode($ev), ENT_QUOTES) ?>)"
                    class="w-9 h-9 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center transition">
                <i class="fas fa-edit text-xs"></i>
            </button>
            <button onclick="deleteEvent(<?= $ev->id ?>, <?= (int)$place->id ?>)"
                    class="w-9 h-9 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl flex items-center justify-center transition">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Event Form Modal -->
<div id="event-modal" class="hidden fixed inset-0 bg-black/60 z-[3500] items-center justify-center p-4" onclick="if(event.target===this)closeEventForm()">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-5">
            <h4 id="event-modal-title" class="text-lg font-black text-slate-800">เพิ่มโปรโมชั่น</h4>
            <button onclick="closeEventForm()" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form id="event-form" class="space-y-3">
            <input type="hidden" name="id" id="event-id" value="0">
            <input type="hidden" name="place_id" value="<?= (int)$place->id ?>">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">ชื่อโปรโมชั่น / กิจกรรม <span class="text-red-400">*</span></label>
                <input type="text" name="title" id="event-title" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0088CC]"
                       placeholder="เช่น ลด 20% ทุกเมนู, งานวันเกิดร้าน">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">รายละเอียด</label>
                <textarea name="description" id="event-desc" rows="2"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0088CC] resize-none"
                          placeholder="รายละเอียดเพิ่มเติม (ไม่บังคับ)"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">วันเริ่ม <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date" id="event-start" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0088CC]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">วันสิ้นสุด</label>
                    <input type="date" name="end_date" id="event-end"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0088CC]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">สถานะ</label>
                <select name="status" id="event-status"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0088CC]">
                    <option value="active">เปิดใช้งาน</option>
                    <option value="inactive">ปิดใช้งาน</option>
                </select>
            </div>
            <button type="submit" id="event-submit-btn"
                    class="w-full bg-[#0088CC] hover:bg-[#005A8E] text-white font-bold py-3 rounded-2xl text-sm transition mt-2">
                บันทึก
            </button>
        </form>
    </div>
</div>

<script>
const BASE = '<?= BASE_URL ?>';

function openEventForm(ev = null) {
    document.getElementById('event-modal-title').textContent = ev ? 'แก้ไขโปรโมชั่น' : 'เพิ่มโปรโมชั่น';
    document.getElementById('event-id').value       = ev ? ev.id       : 0;
    document.getElementById('event-title').value    = ev ? ev.title    : '';
    document.getElementById('event-desc').value     = ev ? (ev.description || '') : '';
    document.getElementById('event-start').value    = ev ? ev.start_date : '';
    document.getElementById('event-end').value      = ev ? (ev.end_date || '') : '';
    document.getElementById('event-status').value   = ev ? ev.status   : 'active';
    const m = document.getElementById('event-modal');
    m.classList.remove('hidden'); m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeEventForm() {
    const m = document.getElementById('event-modal');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.body.style.overflow = '';
}

document.getElementById('event-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn  = document.getElementById('event-submit-btn');
    const data = new FormData(this);
    btn.disabled = true; btn.textContent = 'กำลังบันทึก...';
    try {
        const res  = await fetch(BASE + '/api/events/save', { method: 'POST', body: data });
        const json = await res.json();
        closeEventForm();
        Swal.fire({ icon: json.success ? 'success' : 'error', title: json.message,
                    timer: 1500, showConfirmButton: false });
        if (json.success) setTimeout(() => location.reload(), 1600);
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'กรุณาลองใหม่' });
    } finally {
        btn.disabled = false; btn.textContent = 'บันทึก';
    }
});

async function deleteEvent(id, placeId) {
    const confirm = await Swal.fire({
        title: 'ลบโปรโมชั่นนี้?', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8', confirmButtonText: 'ลบ', cancelButtonText: 'ยกเลิก'
    });
    if (!confirm.isConfirmed) return;
    const body = new FormData();
    body.append('id', id); body.append('place_id', placeId);
    const res  = await fetch(BASE + '/api/events/delete', { method: 'POST', body });
    const json = await res.json();
    Swal.fire({ icon: json.success ? 'success' : 'error', title: json.message,
                timer: 1200, showConfirmButton: false });
    if (json.success) setTimeout(() => location.reload(), 1300);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEventForm(); });
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
