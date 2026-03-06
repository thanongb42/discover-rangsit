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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
                                <button onclick="deletePlace(<?= $place->id ?>)" class="text-gray-400 hover:text-red-600 p-2 transition">
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

<script>
    async function deletePlace(id) {
        const result = await Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลธุรกิจและรูปภาพทั้งหมดจะถูกลบอย่างถาวร!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            const response = await fetch('<?= BASE_URL ?>/api/admin/places/delete', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();

            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false });
                document.getElementById(`row-${id}`).classList.add('opacity-0', 'transition-all', 'duration-500');
                setTimeout(() => document.getElementById(`row-${id}`).remove(), 500);
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        }
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>