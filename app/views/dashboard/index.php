<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">แดชบอร์ด</h1>
    <p class="text-gray-500 text-sm">ยินดีต้อนรับกลับมา, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>. ดูภาพรวมธุรกิจและกิจกรรมในเมืองรังสิตของคุณ</p>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">ธุรกิจที่เปิดใช้งาน</p>
            <h3 class="text-2xl font-bold text-gray-800">0</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-eye"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">ยอดการเข้าชมรวม</p>
            <h3 class="text-2xl font-bold text-gray-800">0</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-14 h-14 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">คะแนนรีวิวเฉลี่ย</p>
            <h3 class="text-2xl font-bold text-gray-800">0.0</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h5 class="font-bold text-gray-800">กิจกรรมล่าสุด</h5>
                <a href="<?= BASE_URL ?>/dashboard/add-place" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> เพิ่มธุรกิจใหม่
                </a>
            </div>
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h4 class="text-gray-600 font-medium">คุณยังไม่มีข้อมูลธุรกิจ</h4>
                <p class="text-gray-400 text-sm mt-1">เริ่มสร้างธุรกิจของคุณเพื่อให้ชาวรังสิตได้ค้นพบกันวันนี้</p>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>