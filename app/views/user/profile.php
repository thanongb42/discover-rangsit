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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">ข้อมูลส่วนตัว</h3>
            </div>
            <div class="p-8">
                <form action="<?= BASE_URL ?>/profile/update" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อ-นามสกุล</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['user']->name) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">อีเมล (ไม่สามารถเปลี่ยนได้)</label>
                            <input type="email" value="<?= htmlspecialchars($data['user']->email) ?>" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" disabled>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($data['user']->phone) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition">
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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>