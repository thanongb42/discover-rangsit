<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
$summary      = $data['summary']      ?? (object)[];
$growth       = $data['growth']       ?? (object)[];
$completeness = $data['completeness'] ?? (object)[];
$topKeywords  = $data['top_keywords'] ?? [];
$topPlaces    = $data['top_places']   ?? [];
$catStats     = $data['cat_stats']    ?? [];
$newByMonth   = $data['new_by_month'] ?? [];

$approved   = (int)(isset($summary->approved)   ? $summary->approved   : 0);
$pending    = (int)(isset($summary->pending)    ? $summary->pending    : 0);
$thisMonth  = (int)(isset($growth->this_month)  ? $growth->this_month  : 0);
$lastMonth  = (int)(isset($growth->last_month)  ? $growth->last_month  : 0);
$growthPct  = $lastMonth > 0 ? round(($thisMonth - $lastMonth) / $lastMonth * 100) : ($thisMonth > 0 ? 100 : 0);

$total      = (int)(isset($completeness->total)      ? $completeness->total      : 0);
$hasPhone   = (int)(isset($completeness->has_phone)  ? $completeness->has_phone  : 0);
$hasCover   = (int)(isset($completeness->has_cover)  ? $completeness->has_cover  : 0);
$hasCoords  = (int)(isset($completeness->has_coords) ? $completeness->has_coords : 0);
$hasDesc    = (int)(isset($completeness->has_desc)   ? $completeness->has_desc   : 0);
$hasSocial  = (int)(isset($completeness->has_social) ? $completeness->has_social : 0);
$pctPhone   = $total > 0 ? round($hasPhone  / $total * 100) : 0;
$pctCover   = $total > 0 ? round($hasCover  / $total * 100) : 0;
$pctCoords  = $total > 0 ? round($hasCoords / $total * 100) : 0;
$pctDesc    = $total > 0 ? round($hasDesc   / $total * 100) : 0;
$pctSocial  = $total > 0 ? round($hasSocial / $total * 100) : 0;

$monthLabels = array_map(fn($r) => $r->label, $newByMonth);
$monthCounts = array_map(fn($r) => (int)$r->count, $newByMonth);
$catLabels   = array_map(fn($c) => $c->name, $catStats);
$catCounts   = array_map(fn($c) => (int)$c->approved_count, $catStats);
$catColors   = array_map(fn($c) => $c->color ?: '#94a3b8', $catStats);
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1e3a8a,#0088CC)">
            <i class="fas fa-chart-pie text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Executive Dashboard</h1>
            <p class="text-gray-400 text-xs">เทศบาลนครรังสิต · อัปเดต <?= date('d M Y H:i') ?></p>
        </div>
    </div>
</div>

<!-- KPI Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- New This Month -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
        <div class="p-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center mb-3">
                <i class="fas fa-store text-white text-sm"></i>
            </div>
            <p class="text-3xl font-black text-gray-800 leading-none mb-1"><?= number_format($thisMonth) ?></p>
            <p class="text-xs font-bold text-gray-600">ธุรกิจใหม่เดือนนี้</p>
            <p class="text-[10px] mt-1 font-bold <?= $growthPct >= 0 ? 'text-green-600' : 'text-red-500' ?>">
                <?= $growthPct >= 0 ? '+' : '' ?><?= $growthPct ?>% จากเดือนที่แล้ว
            </p>
        </div>
    </div>

    <!-- Approved Total -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-emerald-600 to-emerald-400"></div>
        <div class="p-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-white text-sm"></i>
            </div>
            <p class="text-3xl font-black text-gray-800 leading-none mb-1"><?= number_format($approved) ?></p>
            <p class="text-xs font-bold text-gray-600">ธุรกิจอนุมัติแล้ว</p>
            <p class="text-[10px] text-gray-400 mt-1"><?= number_format(isset($summary->total_businesses) ? $summary->total_businesses : 0) ?> รายการในระบบ</p>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r <?= $pending > 0 ? 'from-amber-500 to-yellow-400' : 'from-gray-300 to-gray-200' ?>"></div>
        <div class="p-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?= $pending > 0 ? 'from-amber-500 to-yellow-400' : 'from-gray-300 to-gray-200' ?> flex items-center justify-center mb-3">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
            <p class="text-3xl font-black text-gray-800 leading-none mb-1"><?= number_format($pending) ?></p>
            <p class="text-xs font-bold text-gray-600">รอการอนุมัติ</p>
            <?php if ($pending > 0): ?>
            <a href="<?= BASE_URL ?>/admin/pending" class="text-[10px] text-amber-600 font-bold mt-1 inline-block hover:underline">ตรวจสอบเลย &rarr;</a>
            <?php else: ?>
            <p class="text-[10px] text-green-500 font-bold mt-1">ไม่มีรายการรอ</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-violet-600 to-violet-400"></div>
        <div class="p-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-violet-400 flex items-center justify-center mb-3">
                <i class="fas fa-users text-white text-sm"></i>
            </div>
            <p class="text-3xl font-black text-gray-800 leading-none mb-1"><?= number_format(isset($summary->total_users) ? $summary->total_users : 0) ?></p>
            <p class="text-xs font-bold text-gray-600">สมาชิกทั้งหมด</p>
            <p class="text-[10px] text-gray-400 mt-1"><?= number_format(isset($summary->total_reviews) ? $summary->total_reviews : 0) ?> รีวิวในระบบ</p>
        </div>
    </div>
</div>

<!-- Data Completeness -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex items-center gap-2 mb-5">
        <i class="fas fa-tasks text-[#0088CC]"></i>
        <h3 class="font-bold text-gray-800">ความครบถ้วนของข้อมูลธุรกิจ</h3>
        <span class="text-[10px] bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded-full"><?= number_format($total) ?> ร้านที่อนุมัติแล้ว</span>
    </div>
    <?php
    $bars = [
        ['label' => 'มีรูปภาพหน้าปก',     'pct' => $pctCover,  'color' => 'bg-blue-500'],
        ['label' => 'มีคำอธิบายร้าน',       'pct' => $pctDesc,   'color' => 'bg-sky-500'],
        ['label' => 'มีเบอร์โทรศัพท์',       'pct' => $pctPhone,  'color' => 'bg-emerald-500'],
        ['label' => 'มีพิกัด GPS',           'pct' => $pctCoords, 'color' => 'bg-violet-500'],
        ['label' => 'มี Social Media',       'pct' => $pctSocial, 'color' => 'bg-rose-500'],
    ];
    foreach ($bars as $bar): ?>
    <div class="mb-3">
        <div class="flex justify-between items-center mb-1">
            <span class="text-xs font-semibold text-gray-600"><?= $bar['label'] ?></span>
            <span class="text-xs font-black text-gray-800"><?= $bar['pct'] ?>%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div class="<?= $bar['color'] ?> h-2 rounded-full transition-all duration-500" style="width:<?= $bar['pct'] ?>%"></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Search Keywords + Top Places -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Top Search Keywords -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-search text-[#0088CC]"></i>
            <h3 class="font-bold text-gray-800">คำค้นหายอดนิยม</h3>
            <span class="text-[10px] text-gray-400 ml-auto">30 วันล่าสุด</span>
        </div>
        <?php if (empty($topKeywords)): ?>
            <div class="text-center py-8 text-gray-300">
                <i class="fas fa-search text-3xl mb-2 block"></i>
                <p class="text-sm">ยังไม่มีข้อมูลการค้นหา</p>
            </div>
        <?php else: ?>
            <?php
            $maxCount = (int)($topKeywords[0]->count ?? 1);
            foreach ($topKeywords as $i => $kw):
                $pct = $maxCount > 0 ? round((int)$kw->count / $maxCount * 100) : 0;
            ?>
            <div class="flex items-center gap-3 py-2 <?= $i < count($topKeywords)-1 ? 'border-b border-gray-50' : '' ?>">
                <span class="text-[10px] font-black text-gray-300 w-4 text-right"><?= $i+1 ?></span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <span class="text-sm font-bold text-gray-700 truncate"><?= htmlspecialchars($kw->keyword) ?></span>
                        <span class="text-xs font-black text-gray-500 ml-2 flex-shrink-0"><?= number_format((int)$kw->count) ?></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1">
                        <div class="bg-[#0088CC] h-1 rounded-full" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Top Places by Views -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-fire text-orange-500"></i>
            <h3 class="font-bold text-gray-800">ธุรกิจยอดเข้าชมสูงสุด</h3>
            <span class="text-[10px] text-gray-400 ml-auto">ทั้งหมด</span>
        </div>
        <?php if (empty($topPlaces)): ?>
            <div class="text-center py-8 text-gray-300">
                <i class="fas fa-store text-3xl mb-2 block"></i>
                <p class="text-sm">ยังไม่มีข้อมูล</p>
            </div>
        <?php else: ?>
            <?php foreach ($topPlaces as $i => $p): ?>
            <div class="flex items-center gap-3 py-2 <?= $i < count($topPlaces)-1 ? 'border-b border-gray-50' : '' ?>">
                <span class="text-[10px] font-black text-gray-300 w-4 text-right flex-shrink-0"><?= $i+1 ?></span>
                <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                    <img src="<?= BASE_URL ?>/../uploads/covers/<?= htmlspecialchars($p->cover_image ?: 'default.jpg') ?>"
                         class="w-full h-full object-cover" loading="lazy"
                         onerror="this.src='<?= BASE_URL ?>/images/rangsit-logo.png'">
                </div>
                <div class="flex-1 min-w-0">
                    <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($p->slug) ?>" target="_blank"
                       class="text-sm font-bold text-gray-700 hover:text-[#0088CC] truncate block leading-tight"><?= htmlspecialchars($p->name) ?></a>
                    <p class="text-[10px] text-gray-400 truncate"><?= htmlspecialchars($p->category_name ?? '') ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-black text-gray-700"><?= number_format($p->views_count) ?></p>
                    <p class="text-[10px] text-gray-400">views</p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- New Businesses Trend -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-bar text-blue-500"></i> ธุรกิจใหม่ต่อเดือน
        </h3>
        <p class="text-xs text-gray-400 mb-4">6 เดือนล่าสุด</p>
        <canvas id="growthChart" height="180"></canvas>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-th text-violet-500"></i> หมวดหมู่ธุรกิจ
        </h3>
        <?php if (empty($catStats)): ?>
            <div class="text-center py-8 text-gray-300 text-sm">ไม่มีข้อมูล</div>
        <?php else: ?>
            <canvas id="catChart" height="200"></canvas>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const monthLabels = <?= json_encode($monthLabels, JSON_UNESCAPED_UNICODE) ?>;
    const monthCounts = <?= json_encode($monthCounts) ?>;
    const catLabels   = <?= json_encode($catLabels, JSON_UNESCAPED_UNICODE) ?>;
    const catCounts   = <?= json_encode($catCounts) ?>;
    const catColors   = <?= json_encode($catColors) ?>;

    if (document.getElementById('growthChart')) {
        new Chart(document.getElementById('growthChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'ธุรกิจใหม่',
                    data: monthCounts,
                    backgroundColor: 'rgba(0,136,204,0.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    if (document.getElementById('catChart') && catLabels.length) {
        new Chart(document.getElementById('catChart'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{ data: catCounts, backgroundColor: catColors, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } } }
            }
        });
    }
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
