<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>
<?php
require_once APP_ROOT . '/app/helpers/DeliveryPlatforms.php';
$summary       = $data['summary'];
$categoryStats = $data['category_stats'];
$topPlaces     = $data['top_places'];
$mapPlaces     = $data['map_places'];
$newByMonth    = $data['new_by_month'];
$engByMonth    = $data['eng_by_month'];
$deliveryAdopt = $data['delivery_adopt'];
$platforms     = DeliveryPlatforms::all();

$totalApproved    = (int)($summary->approved ?? 0);
$deliveryShops    = (int)($summary->delivery_shops ?? 0);
$deliveryPct      = $totalApproved > 0 ? round($deliveryShops / $totalApproved * 100) : 0;
$reviewedPct      = $totalApproved > 0 ? round((int)($summary->has_reviews ?? 0) / $totalApproved * 100) : 0;

// Chart data
$monthLabels = array_map(fn($r) => $r->label, $newByMonth);
$newCounts   = array_map(fn($r) => (int)$r->count, $newByMonth);
$engCounts   = array_map(fn($r) => (int)$r->views, $engByMonth);

$catLabels = array_map(fn($c) => $c->name, $categoryStats);
$catCounts = array_map(fn($c) => (int)$c->approved_count, $categoryStats);
$catColors = array_map(fn($c) => $c->color ?: '#94a3b8', $categoryStats);
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1e3a8a,#0088CC)">
            <i class="fas fa-city text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">City Economic Dashboard</h1>
            <p class="text-gray-400 text-xs">เทศบาลนครรังสิต · อัปเดต <?= date('d M Y H:i') ?></p>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <?php
    $kpis = [
        ['icon'=>'fa-store',         'label'=>'ธุรกิจทั้งหมด',    'value'=>number_format($summary->total_businesses ?? 0), 'sub'=>number_format($summary->approved ?? 0).' อนุมัติแล้ว', 'grad'=>'from-blue-600 to-blue-400'],
        ['icon'=>'fa-users',         'label'=>'สมาชิก',           'value'=>number_format($summary->total_users ?? 0),       'sub'=>'ผู้ใช้งานทั้งหมด',   'grad'=>'from-violet-600 to-violet-400'],
        ['icon'=>'fa-eye',           'label'=>'การเข้าชม',        'value'=>number_format($summary->total_views ?? 0),       'sub'=>'ทุกธุรกิจรวมกัน',   'grad'=>'from-sky-600 to-sky-400'],
        ['icon'=>'fa-heart',         'label'=>'ถูกใจ',            'value'=>number_format($summary->total_likes ?? 0),       'sub'=>'รวมทุกธุรกิจ',      'grad'=>'from-rose-600 to-rose-400'],
        ['icon'=>'fa-star',          'label'=>'รีวิว',            'value'=>number_format($summary->total_reviews ?? 0),     'sub'=>'คะแนนเฉลี่ย '.number_format($summary->avg_rating ?? 0,1).'★', 'grad'=>'from-amber-500 to-yellow-400'],
        ['icon'=>'fa-motorcycle',    'label'=>'คลิก Delivery',    'value'=>number_format($summary->total_delivery_clicks ?? 0), 'sub'=>$deliveryPct.'% ของร้านเปิดใช้', 'grad'=>'from-green-600 to-emerald-400'],
    ];
    foreach ($kpis as $k): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r <?= $k['grad'] ?>"></div>
        <div class="p-4">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?= $k['grad'] ?> flex items-center justify-center mb-3">
                <i class="fas <?= $k['icon'] ?> text-white text-sm"></i>
            </div>
            <p class="text-2xl font-black text-gray-800 leading-none mb-1"><?= $k['value'] ?></p>
            <p class="text-xs font-bold text-gray-600"><?= $k['label'] ?></p>
            <p class="text-[10px] text-gray-400 mt-0.5"><?= $k['sub'] ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Smart Economy KPI Banner -->
<div class="mb-8 rounded-2xl p-6 border border-blue-100" style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
    <div class="flex items-center gap-2 mb-4">
        <i class="fas fa-trophy text-blue-600"></i>
        <h3 class="font-black text-blue-900 text-sm uppercase tracking-wider">Smart Economy KPIs</h3>
        <span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-bold">สำหรับการประกวด</span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $smartKpis = [
            ['label'=>'อัตราการ Digitize', 'value'=>$deliveryPct.'%', 'desc'=>'ร้านที่เชื่อม Delivery Platform', 'icon'=>'fa-wifi'],
            ['label'=>'Engagement Rate',   'value'=>$reviewedPct.'%', 'desc'=>'ร้านที่มีรีวิวจากลูกค้าจริง',    'icon'=>'fa-comments'],
            ['label'=>'Digital Touchpoints','value'=>number_format(($summary->total_views??0)+($summary->total_likes??0)+($summary->total_reviews??0)+($summary->total_delivery_clicks??0)), 'desc'=>'การปฏิสัมพันธ์ดิจิทัลรวมทั้งหมด', 'icon'=>'fa-hand-pointer'],
            ['label'=>'Platform Coverage',  'value'=>count(array_unique(array_column($deliveryAdopt,'platform'))).' แพลตฟอร์ม', 'desc'=>'Delivery ที่มีร้านรังสิตเข้าร่วม', 'icon'=>'fa-layer-group'],
        ];
        foreach ($smartKpis as $sk): ?>
        <div class="bg-white rounded-xl p-4 border border-blue-100 text-center">
            <i class="fas <?= $sk['icon'] ?> text-blue-500 text-xl mb-2 block"></i>
            <p class="text-2xl font-black text-blue-800"><?= $sk['value'] ?></p>
            <p class="text-xs font-bold text-blue-700 mt-0.5"><?= $sk['label'] ?></p>
            <p class="text-[10px] text-blue-400 mt-0.5"><?= $sk['desc'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- New Businesses by Month -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-bar text-blue-500"></i> ธุรกิจใหม่ต่อเดือน
        </h3>
        <p class="text-xs text-gray-400 mb-4">จำนวนธุรกิจที่ลงทะเบียนใน 6 เดือนล่าสุด</p>
        <canvas id="newBusinessChart" height="220"></canvas>
    </div>

    <!-- Engagement by Month -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-chart-line text-sky-500"></i> Digital Engagement ต่อเดือน
        </h3>
        <p class="text-xs text-gray-400 mb-4">จำนวนการเข้าชมธุรกิจทั้งหมดในระบบต่อเดือน</p>
        <canvas id="engagementChart" height="220"></canvas>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Category Distribution -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-th text-violet-500"></i> หมวดหมู่ธุรกิจ
        </h3>
        <canvas id="categoryChart" height="220"></canvas>
        <div class="mt-4 space-y-1.5 max-h-36 overflow-y-auto">
            <?php foreach ($categoryStats as $cat): ?>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:<?= $cat->color ?: '#94a3b8' ?>"></span>
                    <span class="text-gray-600 truncate max-w-[120px]"><?= htmlspecialchars($cat->name) ?></span>
                </div>
                <span class="font-black text-gray-800 ml-2"><?= $cat->approved_count ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Delivery Adoption -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-motorcycle text-orange-500"></i> Delivery Adoption
        </h3>
        <?php if (empty($deliveryAdopt)): ?>
        <div class="flex flex-col items-center justify-center h-40 text-gray-300">
            <i class="fas fa-motorcycle text-5xl mb-2"></i>
            <p class="text-sm text-gray-400">ยังไม่มีข้อมูล</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($deliveryAdopt as $d):
                $cfg = $platforms[$d->platform] ?? null;
                if (!$cfg) continue;
                $pct = $totalApproved > 0 ? round((int)$d->shop_count / $totalApproved * 100) : 0;
            ?>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-[10px]" style="background:<?= $cfg['color'] ?>">
                            <i class="<?= $cfg['icon'] ?>"></i>
                        </span>
                        <span class="text-xs font-bold text-gray-700"><?= $cfg['name'] ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-gray-800"><?= $d->shop_count ?> ร้าน</span>
                        <span class="text-[10px] text-gray-400 ml-1">(<?= $pct ?>%)</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all" style="width:<?= $pct ?>%; background:<?= $cfg['color'] ?>"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-0.5"><?= number_format($d->total_clicks) ?> คลิกทั้งหมด</p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Business Status -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-clipboard-check text-green-500"></i> สถานะธุรกิจ
        </h3>
        <canvas id="statusChart" height="180"></canvas>
        <div class="mt-4 grid grid-cols-3 gap-2 text-center">
            <div class="bg-green-50 rounded-xl p-2">
                <p class="text-lg font-black text-green-600"><?= number_format($summary->approved ?? 0) ?></p>
                <p class="text-[10px] text-green-500 font-bold">อนุมัติ</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-2">
                <p class="text-lg font-black text-yellow-500"><?= number_format($summary->pending ?? 0) ?></p>
                <p class="text-[10px] text-yellow-500 font-bold">รอ</p>
            </div>
            <div class="bg-red-50 rounded-xl p-2">
                <p class="text-lg font-black text-red-500"><?= number_format($summary->rejected ?? 0) ?></p>
                <p class="text-[10px] text-red-500 font-bold">ไม่อนุมัติ</p>
            </div>
        </div>
    </div>
</div>

<!-- Map -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-emerald-500"></i> แผนที่ความหนาแน่นธุรกิจ
        </h3>
        <span class="text-xs text-gray-400"><?= count($mapPlaces) ?> สถานที่บนแผนที่</span>
    </div>
    <div id="cityMap" style="height:420px;"></div>
</div>

<!-- Top 10 Leaderboard -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-50">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-trophy text-amber-400"></i> Top 10 ธุรกิจยอดนิยม (การเข้าชม)
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 w-10">#</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500">ธุรกิจ</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500">หมวดหมู่</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 text-right">การเข้าชม</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 text-right">คะแนน</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($topPlaces as $i => $p): ?>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-3 text-center">
                        <?php if ($i < 3): ?>
                        <span class="text-lg"><?= ['🥇','🥈','🥉'][$i] ?></span>
                        <?php else: ?>
                        <span class="text-sm font-bold text-gray-400"><?= $i+1 ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?= BASE_URL ?>/../uploads/covers/<?= $p->cover_image ?>" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                            <a href="<?= BASE_URL ?>/place/<?= $p->slug ?>" target="_blank" class="font-bold text-gray-800 hover:text-primary-600 transition text-sm"><?= htmlspecialchars($p->name) ?></a>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-xs text-gray-500"><?= htmlspecialchars($p->category_name ?? '-') ?></td>
                    <td class="px-6 py-3 text-right">
                        <span class="font-black text-gray-800"><?= number_format($p->views_count) ?></span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <?php if ($p->rating_count > 0): ?>
                        <span class="font-bold text-yellow-500"><?= number_format($p->rating_avg, 1) ?> ★</span>
                        <?php else: ?>
                        <span class="text-gray-300 text-xs">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
Chart.defaults.font.family = "'Sarabun', 'Noto Sans Thai', sans-serif";
Chart.defaults.color = '#64748b';

const MONTH_LABELS = <?= json_encode($monthLabels) ?>;
const NEW_COUNTS   = <?= json_encode($newCounts) ?>;
const ENG_COUNTS   = <?= json_encode($engCounts) ?>;
const CAT_LABELS   = <?= json_encode($catLabels) ?>;
const CAT_COUNTS   = <?= json_encode($catCounts) ?>;
const CAT_COLORS   = <?= json_encode($catColors) ?>;

// New Businesses Chart
new Chart(document.getElementById('newBusinessChart'), {
    type: 'bar',
    data: {
        labels: MONTH_LABELS,
        datasets: [{ label: 'ธุรกิจใหม่', data: NEW_COUNTS, backgroundColor: '#0088CC', borderRadius: 8 }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Engagement Chart
new Chart(document.getElementById('engagementChart'), {
    type: 'line',
    data: {
        labels: MONTH_LABELS,
        datasets: [{ label: 'การเข้าชม', data: ENG_COUNTS, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.08)', borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#0ea5e9' }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Category Donut
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: CAT_LABELS,
        datasets: [{ data: CAT_COUNTS, backgroundColor: CAT_COLORS, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// Status Donut
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['อนุมัติ', 'รอการอนุมัติ', 'ไม่อนุมัติ'],
        datasets: [{ data: [<?= (int)($summary->approved??0) ?>, <?= (int)($summary->pending??0) ?>, <?= (int)($summary->rejected??0) ?>], backgroundColor: ['#22c55e','#f59e0b','#ef4444'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } }
    }
});

// Leaflet Map
const MAP_PLACES = <?= json_encode(array_map(fn($p) => [
    'lat'    => (float)$p->latitude,
    'lng'    => (float)$p->longitude,
    'name'   => $p->name,
    'slug'   => $p->slug,
    'views'  => $p->views_count,
    'rating' => $p->rating_avg,
], $mapPlaces)) ?>;

const cityMap = L.map('cityMap').setView([13.9840, 100.6125], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(cityMap);

MAP_PLACES.forEach(p => {
    const radius = Math.max(6, Math.min(20, 6 + p.views / 200));
    const circle = L.circleMarker([p.lat, p.lng], {
        radius,
        fillColor: '#0088CC',
        color: '#fff',
        weight: 1.5,
        fillOpacity: 0.75
    }).addTo(cityMap);
    circle.bindPopup(`<strong>${p.name}</strong><br>👁 ${p.views.toLocaleString()} ครั้ง · ⭐ ${p.rating || '—'}`);
});
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
