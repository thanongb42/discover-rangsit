<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลสถานที่</h1>
        <p class="text-gray-500 text-sm">ปรับปรุงรายละเอียด ตำแหน่งพิกัด และจัดการคลังภาพ</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/places" class="text-gray-500 hover:text-gray-800 font-bold flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i> กลับหน้ารายการ
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Basic Info -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">รายละเอียดธุรกิจ</h3>
            </div>
            <div class="p-8">
                <form id="placeEditForm" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $data['place']->id ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อสถานที่</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['place']->name) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">หมวดหมู่</label>
                            <div class="flex gap-2">
                                <select name="category_id" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                                    <?php foreach($data['categories'] as $cat): ?>
                                        <option value="<?= $cat->id ?>" <?= $cat->id == $data['place']->category_id ? 'selected' : '' ?>><?= htmlspecialchars($cat->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <a href="<?= BASE_URL ?>/admin/categories" target="_blank" class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-primary-600 hover:border-primary-200 transition shadow-sm" title="จัดการหมวดหมู่">
                                    <i class="fas fa-list-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">คำอธิบาย</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required><?= htmlspecialchars($data['place']->description) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">สถานะการแสดงผล</label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="approved" <?= $data['place']->status == 'approved' ? 'checked' : '' ?> class="mr-2 text-green-500 focus:ring-green-500">
                                <span class="text-sm font-medium text-green-600">แสดงผล (Approved)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="pending" <?= $data['place']->status == 'pending' ? 'checked' : '' ?> class="mr-2 text-yellow-500 focus:ring-yellow-500">
                                <span class="text-sm font-medium text-yellow-600">รออนุมัติ (Pending)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="rejected" <?= $data['place']->status == 'rejected' ? 'checked' : '' ?> class="mr-2 text-red-500 focus:ring-red-500">
                                <span class="text-sm font-medium text-red-600">ระงับ (Rejected)</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i> ตำแหน่งและที่อยู่
                        </h4>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-400 mb-1">ที่อยู่</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($data['place']->address) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">ละติจูด</label>
                                <input type="number" step="any" name="latitude" id="latInput" value="<?= $data['place']->latitude ?>" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">ลองจิจูด</label>
                                <input type="number" step="any" name="longitude" id="lngInput" value="<?= $data['place']->longitude ?>" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                        <div id="editMap" class="w-full h-64 rounded-2xl border border-gray-200 overflow-hidden mb-4"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($data['place']->phone) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เว็บไซต์</label>
                            <input type="url" name="website" value="<?= htmlspecialchars($data['place']->website) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-share-nodes text-primary-500 mr-2"></i> ช่องทางการติดต่อโซเชียลมีเดีย
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">LINE ID</label>
                                <input type="text" name="line" value="<?= htmlspecialchars($data['place']->line) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="@lineid">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Facebook Fanpage (URL)</label>
                                <input type="url" name="facebook" value="<?= htmlspecialchars($data['place']->facebook) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://facebook.com/page">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Instagram (URL)</label>
                                <input type="url" name="instagram" value="<?= htmlspecialchars($data['place']->instagram) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://instagram.com/user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">TikTok (URL)</label>
                                <input type="url" name="tiktok" value="<?= htmlspecialchars($data['place']->tiktok) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://tiktok.com/@user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">X (Twitter) (URL)</label>
                                <input type="url" name="x" value="<?= htmlspecialchars($data['place']->x) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://x.com/user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">YouTube (URL)</label>
                                <input type="url" name="youtube" value="<?= htmlspecialchars($data['place']->youtube) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://youtube.com/channel">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-10 py-4 rounded-2xl font-black transition shadow-lg shadow-primary-500/30">
                            บันทึกข้อมูลทั้งหมด
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Images & Gallery -->
    <div class="space-y-8">
        <!-- Cover Image -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800">รูปภาพหน้าปก</h3>
            </div>
            <div class="p-6 text-center">
                <div class="relative group mb-4 h-48 rounded-2xl overflow-hidden border border-gray-100 shadow-inner">
                    <img id="coverPreview" src="<?= BASE_URL ?>/../uploads/covers/<?= $data['place']->cover_image ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('coverInput').click()">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </div>
                </div>
                <input type="file" id="coverInput" class="hidden" accept="image/*" onchange="previewImage(this, 'coverPreview')">
                <div class="flex flex-col gap-2">
                    <button type="button" onclick="uploadCover()" id="btnUpdateCover" class="hidden w-full bg-primary-500 hover:bg-primary-600 text-white py-2 rounded-xl font-bold text-sm transition shadow-md shadow-primary-500/20">
                        <i class="fas fa-upload mr-2"></i> อัปเดตรูปหน้าปก
                    </button>
                    <p class="text-[10px] text-gray-400">คลิกที่รูปเพื่อเลือกไฟล์ใหม่ แนะนำ 1200x800px</p>
                </div>
            </div>
        </div>

        <!-- LINE QR Code -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800">LINE QR Code</h3>
            </div>
            <div class="p-6 text-center">
                <div class="relative group mb-4 aspect-square max-w-[200px] mx-auto rounded-2xl overflow-hidden border border-gray-100 shadow-inner bg-gray-50 flex items-center justify-center">
                    <img id="lineQrPreview" src="<?= $data['place']->line_qr ? BASE_URL . '/../uploads/gallery/' . $data['place']->line_qr : 'https://placehold.co/200x200/f8fafc/cbd5e1?text=No+QR' ?>" class="w-full h-full object-contain">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('lineQrInput').click()">
                        <i class="fas fa-qrcode text-white text-2xl"></i>
                    </div>
                </div>
                <input type="file" name="line_qr" id="lineQrInput" class="hidden" accept="image/*" onchange="previewImage(this, 'lineQrPreview', 'btnUpdateLineQr')">
                <div class="flex flex-col gap-2">
                    <button type="button" onclick="uploadLineQr()" id="btnUpdateLineQr" class="hidden w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-xl font-bold text-sm transition shadow-md shadow-green-500/20">
                        <i class="fas fa-upload mr-2"></i> อัปเดต LINE QR
                    </button>
                    <p class="text-[10px] text-gray-400">อัปโหลดรูปภาพ QR Code สำหรับเพิ่มเพื่อน LINE</p>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">คลังภาพขยาย</h3>
                <button onclick="document.getElementById('galleryInput').click()" class="text-primary-600 hover:text-primary-700 font-bold text-xs flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> เพิ่มรูป
                </button>
            </div>
            <div class="p-6">
                <input type="file" id="galleryInput" class="hidden" multiple accept="image/*" onchange="uploadToGallery(this)">
                <div class="grid grid-cols-3 gap-3" id="galleryGrid">
                    <?php foreach($data['gallery'] as $img): ?>
                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100" id="gallery-item-<?= $img->id ?>">
                            <img src="<?= BASE_URL ?>/../uploads/gallery/<?= $img->image_path ?>" class="w-full h-full object-cover">
                            <button onclick="deleteGallery(<?= $img->id ?>)" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(empty($data['gallery'])): ?>
                    <p id="emptyGalleryText" class="text-center text-xs text-gray-400 py-4 italic">ยังไม่มีรูปภาพเพิ่มเติม</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet & Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        
        document.getElementById('placeEditForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveAll();
        });
    });

    function initMap() {
        const lat = parseFloat(document.getElementById('latInput').value);
        const lng = parseFloat(document.getElementById('lngInput').value);
        
        map = L.map('editMap').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OSM' }).addTo(map);
        
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        
        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            document.getElementById('latInput').value = pos.lat.toFixed(6);
            document.getElementById('lngInput').value = pos.lng.toFixed(6);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latInput').value = e.latlng.lat.toFixed(6);
            document.getElementById('lngInput').value = e.latlng.lng.toFixed(6);
        });
    }

    function previewImage(input, previewId, btnId = 'btnUpdateCover') {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                // Show specific update button
                const btn = document.getElementById(btnId);
                btn.classList.remove('hidden');
                btn.classList.add('animate-bounce');
                setTimeout(() => btn.classList.remove('animate-bounce'), 1000);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function uploadLineQr() {
        const qrInput = document.getElementById('lineQrInput');
        if(!qrInput.files[0]) return;

        const formData = new FormData();
        formData.append('id', <?= $data['place']->id ?>);
        formData.append('line_qr', qrInput.files[0]);
        
        try {
            Swal.fire({ title: 'กำลังอัปโหลด QR...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            
            const response = await fetch('<?= BASE_URL ?>/api/admin/places/lineqr/update', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();

            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'อัปเดต LINE QR เรียบร้อยแล้ว', timer: 1500, showConfirmButton: false });
                document.getElementById('btnUpdateLineQr').classList.add('hidden');
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }

    async function uploadCover() {
        const coverInput = document.getElementById('coverInput');
        if(!coverInput.files[0]) return;

        const formData = new FormData();
        formData.append('id', <?= $data['place']->id ?>);
        formData.append('cover_image', coverInput.files[0]);
        
        try {
            Swal.fire({ 
                title: 'กำลังอัปโหลด...', 
                html: 'กรุณารอสักครู่',
                allowOutsideClick: false, 
                didOpen: () => { Swal.showLoading(); } 
            });
            
            const response = await fetch('<?= BASE_URL ?>/api/admin/places/cover/update', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();

            if (res.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'สำเร็จ', 
                    text: 'อัปเดตรูปหน้าปกเรียบร้อยแล้ว', 
                    timer: 1500, 
                    showConfirmButton: false 
                });
                document.getElementById('btnUpdateCover').classList.add('hidden');
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }

    async function saveAll() {
        const formData = new FormData(document.getElementById('placeEditForm'));
        const coverInput = document.getElementById('coverInput');
        if(coverInput.files[0]) {
            formData.append('cover_image', coverInput.files[0]);
        }

        const response = await fetch('<?= BASE_URL ?>/api/admin/places/update', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();

        if (res.success) {
            Swal.fire({ icon: 'success', title: 'อัปเดตเรียบร้อย', text: res.message, timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire('ข้อผิดพลาด', res.message, 'error');
        }
    }

    async function uploadToGallery(input) {
        if (!input.files.length) return;
        
        for (let file of input.files) {
            const formData = new FormData();
            formData.append('place_id', <?= $data['place']->id ?>);
            formData.append('image', file);

            const response = await fetch('<?= BASE_URL ?>/api/admin/places/gallery/upload', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();

            if (res.success) {
                if(document.getElementById('emptyGalleryText')) document.getElementById('emptyGalleryText').remove();
                
                const grid = document.getElementById('galleryGrid');
                const div = document.createElement('div');
                div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-100 animate-fade-in';
                div.innerHTML = `
                    <img src="<?= BASE_URL ?>/../uploads/gallery/${res.filename}" class="w-full h-full object-cover">
                `;
                grid.prepend(div);
            }
        }
        input.value = '';
    }

    async function deleteGallery(id) {
        if(!confirm('ลบรูปภาพนี้?')) return;
        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch('<?= BASE_URL ?>/api/admin/places/gallery/delete', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        if(res.success) {
            const item = document.getElementById(`gallery-item-${id}`);
            item.classList.add('opacity-0', 'scale-50', 'transition-all', 'duration-300');
            setTimeout(() => item.remove(), 300);
        }
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>