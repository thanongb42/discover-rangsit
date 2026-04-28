<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
require_once APP_ROOT . '/app/helpers/DeliveryPlatforms.php';
$place    = $data['place'];
$days     = $data['days'];
$summary  = $data['summary'];
$bench    = $data['benchmark'];
$reviews  = $data['reviews'];

// Build date labels for the selected period
$dateLabels = [];
for ($i = $days - 1; $i >= 0; $i--) {
    $dateLabels[] = date('d/m', strtotime("-{$i} days"));
}
$dateKeys = [];
for ($i = $days - 1; $i >= 0; $i--) {
    $dateKeys[] = date('Y-m-d', strtotime("-{$i} days"));
}

// Map views by date
$viewsMap = [];
foreach ($data['views_by_day'] as $r) { $viewsMap[$r->date] = (int)$r->views; }
$viewsData = array_map(fn($d) => $viewsMap[$d] ?? 0, $dateKeys);

// Map likes by date
$likesMap = [];
foreach ($data['likes_by_day'] as $r) { $likesMap[$r->date] = (int)$r->likes; }
$likesData = array_map(fn($d) => $likesMap[$d] ?? 0, $dateKeys);

// Rating distribution
$ratingMap = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
foreach ($data['rating_dist'] as $r) { $ratingMap[(int)$r->rating] = (int)$r->count; }
$totalRatings = array_sum($ratingMap);

// Delivery clicks by platform
$deliveryPlatforms = DeliveryPlatforms::all();
$deliveryTotals = [];
foreach ($data['delivery_stats'] as $s) {
    $deliveryTotals[$s->platform] = (int)$s->click_count;
}

// Delivery by day per platform
$deliveryDayMap = [];
foreach ($data['delivery_by_day'] as $r) {
    $deliveryDayMap[$r->platform][$r->date] = (int)$r->clicks;
}
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <a href="<?= BASE_URL ?>/my-businesses" class="text-gray-400 hover:text-gray-600 text-sm font-bold flex items-center gap-1 mb-1">
            <i class="fas fa-arrow-left text-xs"></i> ธุรกิจของฉัน
        </a>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <span class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chart-line text-primary-600"></i>
            </span>
            <?= htmlspecialchars($place->name) ?>
        </h1>
    </div>
    <!-- Period Selector -->
    <div class="flex items-center gap-2 bg-gray-100 rounded-xl p-1">
        <?php foreach ([7 => '7 วัน', 30 => '30 วัน', 90 => '90 วัน'] as $d => $label): ?>
        <a href="?days=<?= $d ?>" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= $days == $d ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php
    $kpis = [
        ['label' => 'การเข้าชม', 'sub' => 'ใน ' . $days . ' วัน', 'value' => number_format($summary->views_period ?? 0), 'total' => 'รวม ' . number_format($summary->views_count ?? 0), 'icon' => 'fa-eye', 'color' => 'blue', 'bench' => $bench ? number_format($bench->avg_views ?? 0) : null, 'bench_label' => 'เฉลี่ยหมวด'],
        ['label' => 'คะแนนรีวิว', 'sub' => 'จาก ' . number_format($summary->rating_count ?? 0) . ' รีวิว', 'value' => number_format($summary->rating_avg ?? 0, 1) . ' ★', 'total' => null, 'icon' => 'fa-star', 'color' => 'yellow', 'bench' => $bench ? number_format($bench->avg_rating ?? 0, 1) . ' ★' : null, 'bench_label' => 'เฉลี่ยหมวด'],
        ['label' => 'ถูกใจ', 'sub' => 'ทั้งหมด', 'value' => number_format($summary->like_count ?? 0), 'total' => null, 'icon' => 'fa-heart', 'color' => 'red', 'bench' => null, 'bench_label' => null],
        ['label' => 'รีวิวใหม่', 'sub' => 'ใน ' . $days . ' วัน', 'value' => number_format($summary->reviews_period ?? 0), 'total' => 'รวม ' . number_format($summary->rating_count ?? 0), 'icon' => 'fa-comment', 'color' => 'purple', 'bench' => null, 'bench_label' => null],
    ];
    $colorMap = ['blue'=>['bg'=>'bg-blue-50','text'=>'text-blue-600','badge'=>'bg-blue-100 text-blue-600'], 'yellow'=>['bg'=>'bg-yellow-50','text'=>'text-yellow-500','badge'=>'bg-yellow-100 text-yellow-600'], 'red'=>['bg'=>'bg-red-50','text'=>'text-red-500','badge'=>'bg-red-100 text-red-600'], 'purple'=>['bg'=>'bg-purple-50','text'=>'text-purple-600','badge'=>'bg-purple-100 text-purple-600']];
    foreach ($kpis as $kpi):
        $c = $colorMap[$kpi['color']];
    ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 <?= $c['bg'] ?> rounded-xl flex items-center justify-center">
                <i class="fas <?= $kpi['icon'] ?> <?= $c['text'] ?>"></i>
            </div>
            <?php if ($kpi['bench']): ?>
            <span class="text-[10px] font-bold <?= $c['badge'] ?> px-2 py-1 rounded-full">
                <?= $kpi['bench_label'] ?> <?= $kpi['bench'] ?>
            </span>
            <?php endif; ?>
        </div>
        <p class="text-2xl font-black text-gray-800 mb-0.5"><?= $kpi['value'] ?></p>
        <p class="text-xs font-bold text-gray-500"><?= $kpi['label'] ?></p>
        <?php if ($kpi['sub']): ?><p class="text-[10px] text-gray-400"><?= $kpi['sub'] ?></p><?php endif; ?>
        <?php if ($kpi['total']): ?><p class="text-[10px] text-gray-400 mt-1"><?= $kpi['total'] ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Views Chart -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-eye text-blue-500"></i> การเข้าชม <?= $days ?> วัน
        </h3>
        <p class="text-xs text-gray-400 mb-4">จำนวนครั้งที่มีคนเปิดหน้าร้านคุณต่อวัน</p>
        <canvas id="viewsChart" height="200"></canvas>
    </div>

    <!-- Delivery Chart -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-motorcycle text-orange-500"></i> คลิก Delivery แต่ละแพลตฟอร์ม
        </h3>
        <p class="text-xs text-gray-400 mb-4">จำนวนคลิกปุ่มสั่งอาหาร/จัดส่งรวมทั้งหมด</p>
        <?php if (empty($data['delivery_stats'])): ?>
        <div class="flex flex-col items-center justify-center h-40 text-gray-300">
            <i class="fas fa-motorcycle text-5xl mb-3"></i>
            <p class="text-sm font-medium text-gray-400">ยังไม่มีข้อมูล</p>
            <a href="<?= BASE_URL ?>/dashboard/delivery/<?= $place->id ?>" class="mt-2 text-xs text-primary-500 hover:underline font-bold">ตั้งค่า Delivery Links</a>
        </div>
        <?php else: ?>
        <canvas id="deliveryChart" height="200"></canvas>
        <?php endif; ?>
    </div>
</div>

<!-- Rating + Reviews Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Rating Distribution -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-star text-yellow-400"></i> การกระจายคะแนน
        </h3>
        <?php if ($totalRatings === 0): ?>
        <div class="flex flex-col items-center justify-center h-32 text-gray-300">
            <i class="fas fa-star text-4xl mb-2"></i>
            <p class="text-sm text-gray-400">ยังไม่มีรีวิว</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ([5,4,3,2,1] as $star):
                $count = $ratingMap[$star];
                $pct   = $totalRatings > 0 ? round($count / $totalRatings * 100) : 0;
                $colors = [5=>'bg-green-400', 4=>'bg-lime-400', 3=>'bg-yellow-400', 2=>'bg-orange-400', 1=>'bg-red-400'];
            ?>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-600 w-4 text-right"><?= $star ?></span>
                <i class="fas fa-star text-yellow-400 text-xs"></i>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                    <div class="<?= $colors[$star] ?> h-2.5 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
                </div>
                <span class="text-sm font-bold text-gray-700 w-6 text-right"><?= $count ?></span>
                <span class="text-xs text-gray-400 w-8"><?= $pct ?>%</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Benchmark -->
        <?php if ($bench && $bench->avg_rating > 0): ?>
        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-center">
                <p class="text-2xl font-black text-gray-800"><?= number_format($summary->rating_avg ?? 0, 1) ?></p>
                <p class="text-[10px] text-gray-400 font-bold">คะแนนร้านคุณ</p>
            </div>
            <div class="text-gray-300 text-xl">vs</div>
            <div class="text-center">
                <p class="text-2xl font-black <?= ($summary->rating_avg ?? 0) >= $bench->avg_rating ? 'text-green-500' : 'text-red-400' ?>"><?= number_format($bench->avg_rating, 1) ?></p>
                <p class="text-[10px] text-gray-400 font-bold">เฉลี่ยหมวดหมู่</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-comments text-purple-500"></i> รีวิวล่าสุด
        </h3>
        <?php if (empty($reviews)): ?>
        <div class="flex flex-col items-center justify-center h-32 text-gray-300">
            <i class="fas fa-comment-slash text-4xl mb-2"></i>
            <p class="text-sm text-gray-400">ยังไม่มีรีวิว</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($reviews as $r): ?>
            <div class="flex gap-3 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                <?php
                $avatar = $r->profile_image ?? null;
                $imgSrc = $avatar ? ((strpos($avatar,'http')===0) ? $avatar : BASE_URL.'/'.$avatar) : 'https://ui-avatars.com/api/?name='.urlencode($r->first_name).'&size=40&background=e2e8f0&color=64748b';
                ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-gray-100">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-bold text-gray-700"><?= htmlspecialchars($r->first_name . ' ' . $r->last_name) ?></span>
                        <span class="flex">
                            <?php for ($s=1; $s<=5; $s++): ?>
                            <i class="fas fa-star text-[10px] <?= $s <= $r->rating ? 'text-yellow-400' : 'text-gray-200' ?>"></i>
                            <?php endfor; ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2"><?= htmlspecialchars($r->comment) ?></p>
                    <p class="text-[10px] text-gray-300 mt-1"><?= date('d M Y', strtotime($r->created_at)) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delivery Platform Detail -->
<?php if (!empty($data['delivery_stats'])): ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-motorcycle text-orange-500"></i> สรุป Delivery แต่ละแพลตฟอร์ม
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php foreach ($data['delivery_stats'] as $s):
            $cfg = DeliveryPlatforms::get($s->platform);
            if (!$cfg) continue;
        ?>
        <div class="rounded-2xl p-4 text-center" style="background: <?= $cfg['color'] ?>18; border: 1px solid <?= $cfg['color'] ?>30">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white mx-auto mb-2" style="background: <?= $cfg['color'] ?>">
                <i class="<?= $cfg['icon'] ?>"></i>
            </div>
            <p class="text-xs font-bold text-gray-600 mb-1"><?= $cfg['name'] ?></p>
            <p class="text-xl font-black text-gray-800"><?= number_format($s->click_count) ?></p>
            <p class="text-[10px] text-gray-400"><?= number_format($s->clicks_30d) ?> / 30 วัน</p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const LABELS = <?= json_encode($dateLabels) ?>;
const VIEWS  = <?= json_encode($viewsData) ?>;
const LIKES  = <?= json_encode($likesData) ?>;

Chart.defaults.font.family = "'Sarabun', 'Noto Sans Thai', sans-serif";

// Views Chart
new Chart(document.getElementById('viewsChart'), {
    type: 'line',
    data: {
        labels: LABELS,
        datasets: [{
            label: 'การเข้าชม',
            data: VIEWS,
            borderColor: '#0088CC',
            backgroundColor: 'rgba(0,136,204,0.08)',
            borderWidth: 2.5,
            pointRadius: 3,
            pointBackgroundColor: '#0088CC',
            fill: true,
            tension: 0.4,
        }, {
            label: 'ถูกใจ',
            data: LIKES,
            borderColor: '#f43f5e',
            backgroundColor: 'rgba(244,63,94,0.05)',
            borderWidth: 2,
            pointRadius: 2,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 12 } } } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { precision: 0 } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 10 } } }
        }
    }
});

<?php if (!empty($data['delivery_stats'])): ?>
// Delivery Chart
const PLATFORMS = <?= json_encode(array_keys($deliveryPlatforms)) ?>;
const PLAT_NAMES  = <?= json_encode(array_map(fn($p) => $p['name'], $deliveryPlatforms)) ?>;
const PLAT_COLORS = <?= json_encode(array_map(fn($p) => $p['color'], $deliveryPlatforms)) ?>;
const DELIVERY_MAP = <?= json_encode($deliveryDayMap) ?>;
const DATE_KEYS = <?= json_encode($dateKeys) ?>;

const deliveryDatasets = PLATFORMS
    .filter(p => DELIVERY_MAP[p])
    .map((p, i) => ({
        label: PLAT_NAMES[i] || p,
        data: DATE_KEYS.map(d => (DELIVERY_MAP[p] && DELIVERY_MAP[p][d]) ? DELIVERY_MAP[p][d] : 0),
        backgroundColor: PLAT_COLORS[i] || '#999',
        borderRadius: 4,
    }));

if (deliveryDatasets.length > 0) {
    new Chart(document.getElementById('deliveryChart'), {
        type: 'bar',
        data: { labels: LABELS, datasets: deliveryDatasets },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } } },
            scales: {
                y: { beginAtZero: true, stacked: false, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { precision: 0 } },
                x: { stacked: false, grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 10 } } }
            }
        }
    });
}
<?php endif; ?>
</script>

<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
