<?php
$places = $data['places'] ?? [];
require_once APP_ROOT . '/app/views/layouts/header.php';
?>

<section class="bg-gradient-to-br from-sky-50 via-white to-emerald-50 border-b border-slate-100">
    <div class="container mx-auto px-4 py-14">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white border border-sky-100 text-[#0088CC] px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-5 shadow-sm">
                <i class="fas fa-camera-retro"></i>
                Tourism
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-4">เที่ยวรังสิต</h1>
            <p class="text-slate-600 text-lg leading-relaxed">
                รวมสถานที่ท่องเที่ยว จุดเช็คอิน แหล่งเรียนรู้ และพื้นที่พักผ่อนในนครรังสิต
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="<?= BASE_URL ?>/city-map" class="inline-flex items-center gap-2 bg-[#0088CC] text-white font-bold px-5 py-3 rounded-xl hover:bg-[#006BA8] transition">
                    <i class="fas fa-map-marked-alt"></i> ดูบนแผนที่
                </a>
                <a href="<?= BASE_URL ?>/3dmap" class="inline-flex items-center gap-2 bg-white text-slate-700 font-bold px-5 py-3 rounded-xl border border-slate-200 hover:border-sky-200 transition">
                    <i class="fas fa-cube"></i> 3D Map
                </a>
            </div>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 py-12">
    <div class="flex items-end justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">สถานที่ท่องเที่ยว</h2>
            <p class="text-sm text-slate-400 mt-1">พบ <?= count($places) ?> สถานที่</p>
        </div>
    </div>

    <?php if (empty($places)): ?>
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <i class="fas fa-map-signs text-slate-200 text-5xl mb-4"></i>
            <h3 class="text-xl font-black text-slate-700 mb-2">ยังไม่มีข้อมูล Tourism</h3>
            <p class="text-slate-400">เพิ่มสถานที่โดยกำหนด `place_type = tourism` เพื่อให้แสดงในหน้านี้</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($places as $place):
                $cover = $place->cover_image && $place->cover_image !== 'default.jpg'
                    ? BASE_URL . '/uploads/covers/' . rawurlencode($place->cover_image)
                    : BASE_URL . '/images/rangsit-logo.png';
            ?>
            <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($place->slug) ?>" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">
                <div class="h-44 bg-slate-100 overflow-hidden">
                    <img src="<?= htmlspecialchars($cover) ?>" onerror="this.src='<?= BASE_URL ?>/images/rangsit-logo.png'" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="<?= htmlspecialchars($place->name) ?>">
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-[#0088CC] mb-2">
                        <i class="<?= htmlspecialchars($place->category_icon ? 'fas ' . $place->category_icon : 'fas fa-map-marker-alt') ?>"></i>
                        <?= htmlspecialchars($place->category_name ?? 'Tourism') ?>
                    </div>
                    <h3 class="font-black text-slate-800 leading-tight line-clamp-2 mb-3"><?= htmlspecialchars($place->name) ?></h3>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4"><?= htmlspecialchars($place->description ?? '') ?></p>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-yellow-500 font-black"><i class="fas fa-star"></i> <?= number_format((float)$place->rating_avg, 1) ?></span>
                        <span class="text-slate-400 font-bold"><i class="fas fa-eye"></i> <?= number_format((int)$place->views_count) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
