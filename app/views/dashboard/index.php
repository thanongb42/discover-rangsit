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
                backgroundColor: ['#2795F5', '#1e3a8a', '#0f766e', '#fbbf24', '#f43f5e', '#8b5cf6']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
            cutout: '75%'
        }
    });
</script>

<!-- Category Ranking Report -->
<?php if (!empty($data['catStats'])): ?>
<?php
    $catStats = $data['catStats'];
    $totalPlaces = array_sum(array_column($catStats, 'approved_count'));
?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <h5 class="font-bold text-gray-800 flex items-center">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i> รายงานสรุป: จำนวนสถานที่แยกตาม Category (Ranking)
        </h5>
        <span class="text-xs text-gray-400 font-medium">รวมทั้งหมด <?= number_format($totalPlaces) ?> สถานที่ (approved)</span>
    </div>
    <div class="p-6">
        <div class="space-y-3">
        <?php foreach ($catStats as $i => $cat): ?>
        <?php
            $rank = $i + 1;
            $pct  = $totalPlaces > 0 ? round($cat->approved_count / $totalPlaces * 100, 1) : 0;
            $rankColor = match($rank) {
                1 => 'text-yellow-500',
                2 => 'text-gray-400',
                3 => 'text-amber-600',
                default => 'text-gray-300'
            };
            $badgeBg = match($rank) {
                1 => 'bg-yellow-50 border-yellow-200',
                2 => 'bg-gray-50 border-gray-200',
                3 => 'bg-amber-50 border-amber-200',
                default => 'bg-white border-gray-100'
            };
        ?>
        <div class="flex items-center gap-4 p-3 rounded-xl border <?= $badgeBg ?> hover:bg-gray-50 transition">
            <!-- Rank -->
            <div class="w-8 text-center">
                <?php if ($rank <= 3): ?>
                    <i class="fas fa-trophy <?= $rankColor ?> text-lg"></i>
                <?php else: ?>
                    <span class="text-sm font-bold text-gray-400">#<?= $rank ?></span>
                <?php endif; ?>
            </div>

            <!-- Icon -->
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background-color: <?= htmlspecialchars($cat->color ?? '#9E9E9E') ?>22;">
                <i class="<?= htmlspecialchars($cat->icon ?? 'fas fa-map-marker-alt') ?> text-base"
                   style="color: <?= htmlspecialchars($cat->color ?? '#9E9E9E') ?>;"></i>
            </div>

            <!-- Name + Bar -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-semibold text-gray-700 truncate"><?= htmlspecialchars($cat->name) ?></span>
                    <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                        <span class="text-sm font-black text-gray-800"><?= number_format($cat->approved_count) ?></span>
                        <span class="text-xs text-gray-400 font-medium">(<?= $pct ?>%)</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all"
                         style="width: <?= $pct ?>%; background-color: <?= htmlspecialchars($cat->color ?? '#9E9E9E') ?>;"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

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