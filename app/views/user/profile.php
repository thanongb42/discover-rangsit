<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">โปรไฟล์ของฉัน</h1>
    <p class="text-gray-500 text-sm">จัดการข้อมูลส่วนตัวและเปลี่ยนรหัสผ่านเพื่อความปลอดภัยของบัญชี</p>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg">
        <p class="text-sm text-green-700"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 shadow-sm rounded-r-lg">
        <p class="text-sm text-red-700"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Profile Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">รูปโปรไฟล์</h3>
            </div>
            <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl bg-slate-100">
                        <img id="avatarPreview" src="<?= $data['user']->profile_image ? BASE_URL . '/' . $data['user']->profile_image : 'https://ui-avatars.com/api/?name=' . $data['user']->username . '&size=128' ?>" class="w-full h-full object-cover">
                    </div>
                    <button onclick="document.getElementById('avatarInput').click()" class="absolute bottom-0 right-0 w-10 h-10 bg-primary-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary-600 transition border-4 border-white">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="avatarInput" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h4 class="font-bold text-gray-800 text-lg mb-1">เปลี่ยนรูปโปรไฟล์</h4>
                    <p class="text-sm text-gray-400 mb-4">แนะนำรูปภาพสี่เหลี่ยมจัตุรัส ขนาดไม่เกิน 2MB</p>
                    <button id="btnUpdateAvatar" onclick="uploadAvatar()" class="hidden bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-xl font-bold text-sm transition animate-bounce">
                        <i class="fas fa-upload mr-2"></i> บันทึกรูปภาพใหม่
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">ข้อมูลส่วนตัว</h3>
                <span class="badge bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-black uppercase"><?= $data['user']->role ?></span>
            </div>
            <div class="p-8">
                <form action="<?= BASE_URL ?>/profile/update" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อผู้ใช้งาน (Username)</label>
                            <input type="text" value="<?= htmlspecialchars($data['user']->username) ?>" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">อีเมล (Email)</label>
                            <input type="email" value="<?= htmlspecialchars($data['user']->email) ?>" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" disabled>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">คำนำหน้า</label>
                            <select name="prefix_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                                <?php foreach($data['prefixes'] as $p): ?>
                                    <option value="<?= $p->prefix_id ?>" <?= $p->prefix_id == $data['user']->prefix_id ? 'selected' : '' ?>><?= $p->prefix_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อ</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars($data['user']->first_name) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">นามสกุล</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars($data['user']->last_name) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($data['user']->phone) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ตำแหน่ง</label>
                            <input type="text" value="<?= htmlspecialchars($data['user']->position ?: '-') ?>" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" disabled>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-primary-500/30">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Change Password -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">เปลี่ยนรหัสผ่าน</h3>
            </div>
            <div class="p-8">
                <form action="<?= BASE_URL ?>/profile/change-password" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">รหัสผ่านปัจจุบัน</label>
                        <input type="password" name="current_password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 focus:ring-primary-500 focus:border-primary-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">รหัสผ่านใหม่</label>
                        <input type="password" name="new_password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 focus:ring-primary-500 focus:border-primary-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="confirm_password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 focus:ring-primary-500 focus:border-primary-500 transition" required>
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-xl font-bold transition">
                        เปลี่ยนรหัสผ่าน
                    </button>
                </form>
            </div>
        </div>

        <!-- LINE Connection Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">เชื่อมต่อ LINE</h3>
                <i class="fab fa-line text-green-500 text-2xl"></i>
            </div>
            <div class="p-8 text-center">
                <?php if($data['user']->line_user_id): ?>
                    <div class="w-16 h-16 rounded-full overflow-hidden mx-auto mb-4 border-2 border-green-500">
                        <img src="<?= $data['user']->line_picture_url ?>" class="w-full h-full object-cover">
                    </div>
                    <p class="text-sm font-bold text-gray-800"><?= $data['user']->line_display_name ?></p>
                    <p class="text-[10px] text-gray-400 mt-1">เชื่อมต่อเมื่อ: <?= $data['user']->line_linked_at ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 mb-4">เชื่อมต่อบัญชี LINE เพื่อรับการแจ้งเตือน</p>
                    <a href="<?= BASE_URL ?>/login/line" class="inline-block bg-[#00b900] hover:bg-[#009900] text-white px-6 py-2 rounded-xl font-bold text-sm transition">
                        เชื่อมต่อตอนนี้
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>

<script>
    let resizedBlob = null;

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;
                
                img.onload = function() {
                    // --- Start Resizing Logic ---
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    const max_size = 800; // บังคับขนาดสูงสุด 800px

                    if (width > height) {
                        if (width > max_size) {
                            height *= max_size / width;
                            width = max_size;
                        }
                    } else {
                        if (height > max_size) {
                            width *= max_size / height;
                            height = max_size;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // แปลงเป็น Blob (JPEG quality 0.8) เพื่อลดขนาดไฟล์เหลือหลัก KB
                    canvas.toBlob(function(blob) {
                        resizedBlob = blob;
                        document.getElementById('avatarPreview').src = canvas.toDataURL('image/jpeg', 0.8);
                        document.getElementById('btnUpdateAvatar').classList.remove('hidden');
                    }, 'image/jpeg', 0.8);
                };
            }
            reader.readAsDataURL(file);
        }
    }

    async function uploadAvatar() {
        if (!resizedBlob) return;

        const formData = new FormData();
        // ส่งไฟล์ที่ย่อแล้วในชื่อ 'avatar'
        formData.append('avatar', resizedBlob, 'profile_avatar.jpg');

        const btn = document.getElementById('btnUpdateAvatar');
        try {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> กำลังอัปโหลด...';

            const response = await fetch('<?= BASE_URL ?>/api/profile/avatar', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();

            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false });
                btn.classList.add('hidden');
                // Update header avatars
                const headerAvatars = document.querySelectorAll('.header-avatar-img');
                headerAvatars.forEach(img => {
                    if(img.tagName === 'IMG') {
                        img.src = '<?= BASE_URL ?>/' + res.path + '?v=' + Date.now();
                    } else {
                        // If it was a span (initial), replace with img
                        const newImg = document.createElement('img');
                        newImg.src = '<?= BASE_URL ?>/' + res.path;
                        newImg.className = 'w-full h-full object-cover header-avatar-img';
                        img.parentNode.replaceChild(newImg, img);
                    }
                });
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'ไม่สามารถอัปโหลดได้', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-upload mr-2"></i> บันทึกรูปภาพใหม่';
        }
    }
</script>