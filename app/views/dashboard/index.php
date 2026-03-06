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
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">ธุรกิจที่เปิดใช้งาน</p>
        <div class="flex items-end justify-between">
            <h3 class="text-3xl font-black text-gray-800"><?= number_format($data['activePlaces']) ?></h3>
            <i class="fas fa-building text-primary-200 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">ยอดเข้าชมสถานที่</p>
        <div class="flex items-end justify-between">
            <h3 class="text-3xl font-black text-gray-800"><?= number_format($data['totalViews']) ?></h3>
            <i class="fas fa-eye text-blue-200 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">ผู้เข้าชมวันนี้ (Unique)</p>
        <div class="flex items-end justify-between">
            <h3 class="text-3xl font-black text-gray-800"><?= number_format($data['siteSummary']->today_unique_visitors) ?></h3>
            <i class="fas fa-user-check text-emerald-200 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">การเข้าชมเว็บรวม</p>
        <div class="flex items-end justify-between">
            <h3 class="text-3xl font-black text-gray-800"><?= number_format($data['siteSummary']->total_page_views) ?></h3>
            <i class="fas fa-chart-line text-purple-200 text-2xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Website Traffic Graph -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <h4 class="font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-users text-emerald-500 mr-2"></i> สถิติผู้เข้าชมเว็บไซต์ (7 วันล่าสุด)
        </h4>
        <canvas id="visitorChart" height="200"></canvas>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <h4 class="font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-chart-pie text-emerald-500 mr-2"></i> สัดส่วนประเภทธุรกิจ
        </h4>
        <canvas id="catChart" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Visitor Stats Data
    const visitorData = <?= json_encode($data['visitorStats']) ?>;
    const vCtx = document.getElementById('visitorChart').getContext('2d');
    new Chart(vCtx, {
        type: 'bar',
        data: {
            labels: visitorData.map(d => d.date),
            datasets: [
                {
                    label: 'Page Views',
                    data: visitorData.map(d => d.page_views),
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 8
                },
                {
                    label: 'Unique Visitors',
                    data: visitorData.map(d => d.unique_visitors),
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    borderColor: '#2563eb',
                    borderWidth: 2,
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Category Stats Data
    const catData = <?= json_encode($data['catStats']) ?>;
    const catCtx = document.getElementById('catChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: catData.map(d => d.name),
            datasets: [{
                data: catData.map(d => d.count),
                backgroundColor: ['#009933', '#1e3a8a', '#0f766e', '#fbbf24', '#f43f5e', '#8b5cf6']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
            cutout: '75%'
        }
    });
</script>

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