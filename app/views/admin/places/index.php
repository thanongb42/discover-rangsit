<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">จัดการสถานที่และธุรกิจ</h1>
        <p class="text-gray-500 text-sm">ดูแลข้อมูลธุรกิจทั้งหมดในระบบ ปรับแก้ตำแหน่ง และจัดการรูปภาพ</p>
    </div>
    <a href="<?= BASE_URL ?>/dashboard/add-place" class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-primary-500/30 flex items-center">
        <i class="fas fa-plus mr-2"></i> เพิ่มสถานที่ใหม่
    </a>
</div>

<!-- Tabs -->
<div class="flex gap-2 mb-6">
    <button onclick="showTab('active')" id="tab-active"
        class="tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-transparent bg-primary-500 text-white shadow">
        <i class="fas fa-list mr-1.5"></i> ทั้งหมด
    </button>
    <button onclick="showTab('trash')" id="tab-trash"
        class="tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-gray-200 text-gray-500 hover:bg-gray-50">
        <i class="fas fa-trash-alt mr-1.5"></i> ถังขยะ
        <span id="trashCount" class="ml-1 bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full hidden"></span>
    </button>
</div>

<!-- Active Places Tab -->
<div id="panel-active" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">สถานที่</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">หมวดหมู่</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">เจ้าของ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">สถานะ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center w-32">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($data['places'] as $place): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors" id="row-<?= $place->id ?>">
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
                            <span class="text-xs font-bold text-gray-600"><?= htmlspecialchars($place->category_name) ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            <?= htmlspecialchars($place->owner_name ?: 'System Admin') ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                                $status_class = 'bg-gray-100 text-gray-600';
                                if($place->status == 'approved') $status_class = 'bg-green-100 text-green-600 border-green-200';
                                if($place->status == 'pending') $status_class = 'bg-yellow-100 text-yellow-600 border-yellow-200';
                                if($place->status == 'rejected') $status_class = 'bg-red-100 text-red-600 border-red-200';
                            ?>
                            <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase border <?= $status_class ?>">
                                <?= $place->status ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <a href="<?= BASE_URL ?>/admin/places/edit/<?= $place->id ?>" class="text-gray-400 hover:text-primary-600 p-2 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="trashPlace(<?= $place->id ?>, '<?= addslashes(htmlspecialchars($place->name)) ?>')" class="text-gray-400 hover:text-orange-500 p-2 transition" title="ย้ายไปถังขยะ">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Trash Tab -->
<div id="panel-trash" class="hidden">
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-4 flex items-center gap-3">
        <i class="fas fa-info-circle text-orange-500"></i>
        <p class="text-sm text-orange-700 font-medium">รายการในถังขยะจะถูกซ่อนจากแผนที่ สามารถกู้คืนหรือลบถาวรได้</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">สถานที่</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">หมวดหมู่</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">เจ้าของ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center w-40">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="trashTableBody" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            <i class="fas fa-spinner fa-spin text-xl mb-2 block"></i> กำลังโหลด...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';

    function showTab(tab) {
        document.getElementById('panel-active').classList.toggle('hidden', tab !== 'active');
        document.getElementById('panel-trash').classList.toggle('hidden', tab !== 'trash');
        document.getElementById('tab-active').className = tab === 'active'
            ? 'tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-transparent bg-primary-500 text-white shadow'
            : 'tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-gray-200 text-gray-500 hover:bg-gray-50';
        document.getElementById('tab-trash').className = tab === 'trash'
            ? 'tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-transparent bg-orange-500 text-white shadow'
            : 'tab-btn px-5 py-2.5 rounded-xl text-sm font-bold transition border border-gray-200 text-gray-500 hover:bg-gray-50';
        if (tab === 'trash') loadTrash();
    }

    async function loadTrash() {
        const tbody = document.getElementById('trashTableBody');
        const res = await fetch(`${BASE_URL}/api/admin/places/trashed`);
        const data = await res.json();

        const badge = document.getElementById('trashCount');
        if (data.places && data.places.length > 0) {
            badge.textContent = data.places.length;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        if (!data.places || data.places.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">
                <i class="fas fa-trash text-3xl mb-3 block opacity-30"></i> ถังขยะว่างเปล่า
            </td></tr>`;
            return;
        }

        tbody.innerHTML = data.places.map(p => `
            <tr class="hover:bg-gray-50/80 transition-colors" id="trash-row-${p.id}">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden mr-4 border border-gray-100">
                            <img src="${BASE_URL}/../uploads/covers/${p.cover_image || 'default.jpg'}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">${p.name}</p>
                            <p class="text-xs text-gray-400 truncate w-48">${p.address || ''}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-600">${p.category_name || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${p.owner_name || 'System Admin'}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="restorePlace(${p.id})" class="text-xs font-bold text-emerald-600 hover:bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <i class="fas fa-undo text-[10px]"></i> กู้คืน
                        </button>
                        <button onclick="permanentDelete(${p.id}, '${p.name.replace(/'/g, "\\'")}')" class="text-xs font-bold text-red-600 hover:bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <i class="fas fa-times text-[10px]"></i> ลบถาวร
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    async function trashPlace(id, name) {
        const result = await Swal.fire({
            title: 'ย้ายไปถังขยะ?',
            text: `"${name}" จะถูกซ่อนจากแผนที่ สามารถกู้คืนได้ภายหลัง`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            confirmButtonText: 'ย้ายไปถังขยะ',
            cancelButtonText: 'ยกเลิก'
        });
        if (!result.isConfirmed) return;

        const fd = new FormData();
        fd.append('id', id);
        const res = await fetch(`${BASE_URL}/api/admin/places/trash`, { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'ย้ายแล้ว', text: data.message, timer: 1500, showConfirmButton: false });
            const row = document.getElementById(`row-${id}`);
            row.classList.add('opacity-0', 'transition-all', 'duration-500');
            setTimeout(() => row.remove(), 500);
        } else {
            Swal.fire('ข้อผิดพลาด', data.message, 'error');
        }
    }

    async function restorePlace(id) {
        const fd = new FormData();
        fd.append('id', id);
        const res = await fetch(`${BASE_URL}/api/admin/places/restore`, { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'กู้คืนแล้ว', text: data.message, timer: 1500, showConfirmButton: false });
            const row = document.getElementById(`trash-row-${id}`);
            row.classList.add('opacity-0', 'transition-all', 'duration-300');
            setTimeout(() => { row.remove(); updateTrashBadge(); }, 300);
        } else {
            Swal.fire('ข้อผิดพลาด', data.message, 'error');
        }
    }

    async function permanentDelete(id, name) {
        const result = await Swal.fire({
            title: 'ลบถาวร?',
            html: `<strong>"${name}"</strong> จะถูกลบออกจากระบบอย่างถาวร ไม่สามารถกู้คืนได้!`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ลบถาวร',
            cancelButtonText: 'ยกเลิก'
        });
        if (!result.isConfirmed) return;

        const fd = new FormData();
        fd.append('id', id);
        const res = await fetch(`${BASE_URL}/api/admin/places/delete`, { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'ลบแล้ว', text: data.message, timer: 1500, showConfirmButton: false });
            const row = document.getElementById(`trash-row-${id}`);
            row.classList.add('opacity-0', 'transition-all', 'duration-300');
            setTimeout(() => { row.remove(); updateTrashBadge(); }, 300);
        } else {
            Swal.fire('ข้อผิดพลาด', data.message, 'error');
        }
    }

    function updateTrashBadge() {
        const remaining = document.querySelectorAll('#trashTableBody tr[id^="trash-row-"]').length;
        const badge = document.getElementById('trashCount');
        if (remaining > 0) {
            badge.textContent = remaining;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
            document.getElementById('trashTableBody').innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">
                <i class="fas fa-trash text-3xl mb-3 block opacity-30"></i> ถังขยะว่างเปล่า
            </td></tr>`;
        }
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>