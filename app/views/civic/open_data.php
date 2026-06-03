<?php
$stats = $data['stats'];
$categoryStats = $data['category_stats'] ?? [];
$upcomingEvents = (int)($data['upcoming_events'] ?? 0);
require_once APP_ROOT . '/app/views/layouts/header.php';
?>

<section class="bg-gradient-to-br from-slate-900 to-sky-900 text-white">
    <div class="container mx-auto px-4 py-14">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/10 text-sky-100 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-5">
                <i class="fas fa-database"></i>
                Open Data
            </div>
            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4">Discover Rangsit Open Data</h1>
            <p class="text-slate-300 text-lg leading-relaxed">
                ชุดข้อมูลเปิดสำหรับประชาชน นักพัฒนา นักวิจัย และหน่วยงานที่ต้องการต่อยอดข้อมูลเมืองรังสิต
            </p>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <?php
        $cards = [
            ['label' => 'Total Places', 'value' => (int)$stats->total_places, 'icon' => 'fa-map-marker-alt'],
            ['label' => 'Mapped Places', 'value' => (int)$stats->mapped_places, 'icon' => 'fa-map'],
            ['label' => 'Tourism', 'value' => (int)$stats->tourism_places, 'icon' => 'fa-camera-retro'],
            ['label' => 'Upcoming Events', 'value' => $upcomingEvents, 'icon' => 'fa-calendar-alt'],
        ];
        foreach ($cards as $card):
        ?>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-sky-50 text-[#0088CC] flex items-center justify-center mb-4">
                <i class="fas <?= htmlspecialchars($card['icon']) ?>"></i>
            </div>
            <div class="text-3xl font-black text-slate-900"><?= number_format($card['value']) ?></div>
            <div class="text-xs font-black uppercase tracking-widest text-slate-400 mt-1"><?= htmlspecialchars($card['label']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="text-xl font-black text-slate-800">Category Summary</h2>
                <p class="text-sm text-slate-400 mt-1">จำนวนสถานที่แยกตามหมวดหมู่</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-400 uppercase text-[11px] font-black tracking-widest">
                        <tr>
                            <th class="text-left px-5 py-3">Category</th>
                            <th class="text-right px-5 py-3">Places</th>
                            <th class="text-right px-5 py-3">Views</th>
                            <th class="text-right px-5 py-3">Avg Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($categoryStats as $row): ?>
                        <tr>
                            <td class="px-5 py-3 font-bold text-slate-700"><?= htmlspecialchars($row->category_name) ?></td>
                            <td class="px-5 py-3 text-right"><?= number_format((int)$row->total_places) ?></td>
                            <td class="px-5 py-3 text-right"><?= number_format((int)$row->total_views) ?></td>
                            <td class="px-5 py-3 text-right"><?= $row->average_rating ? number_format((float)$row->average_rating, 2) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-black mb-3">Dataset Notes</h2>
            <p class="text-slate-300 text-sm leading-relaxed mb-5">
                ข้อมูลนี้เปิดเผยเฉพาะข้อมูลสาธารณะของสถานที่ที่ได้รับอนุมัติแล้ว ไม่รวมข้อมูลส่วนบุคคลหรือข้อมูลสำหรับผู้ดูแลระบบ
            </p>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span class="text-slate-400">Business</span><strong><?= number_format((int)$stats->business_places) ?></strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">Refill City</span><strong><?= number_format((int)$stats->refill_places) ?></strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">With Images</span><strong><?= number_format((int)$stats->places_with_images) ?></strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">Total Views</span><strong><?= number_format((int)$stats->total_views) ?></strong></div>
            </div>
        </aside>
    </div>
</section>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
