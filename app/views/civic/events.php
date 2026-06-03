<?php
$events = $data['events'] ?? [];
require_once APP_ROOT . '/app/views/layouts/header.php';
?>

<section class="bg-slate-900 text-white">
    <div class="container mx-auto px-4 py-14">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/10 text-sky-100 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-5">
                <i class="fas fa-calendar-alt"></i>
                Events Calendar
            </div>
            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4">ปฏิทินกิจกรรมเมืองรังสิต</h1>
            <p class="text-slate-300 text-lg leading-relaxed">รวมกิจกรรม โปรโมชั่น และอีเวนต์จากสถานที่ใน Discover Rangsit</p>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 py-12">
    <?php if (empty($events)): ?>
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <i class="fas fa-calendar-times text-slate-200 text-5xl mb-4"></i>
            <h3 class="text-xl font-black text-slate-700 mb-2">ยังไม่มีกิจกรรมที่กำลังจะมาถึง</h3>
            <p class="text-slate-400">เมื่อร้านค้าหรือสถานที่เพิ่ม event ที่ active จะแสดงในหน้านี้</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($events as $event):
                $start = $event->start_date ? date('d M Y', strtotime($event->start_date)) : '-';
                $end = $event->end_date ? date('d M Y', strtotime($event->end_date)) : null;
                $cover = $event->cover_image && $event->cover_image !== 'default.jpg'
                    ? BASE_URL . '/uploads/covers/' . rawurlencode($event->cover_image)
                    : BASE_URL . '/images/rangsit-logo.png';
            ?>
            <article class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden md:flex hover:shadow-lg transition">
                <div class="md:w-56 h-40 md:h-auto bg-slate-100 shrink-0">
                    <img src="<?= htmlspecialchars($cover) ?>" onerror="this.src='<?= BASE_URL ?>/images/rangsit-logo.png'" class="w-full h-full object-cover" alt="<?= htmlspecialchars($event->title) ?>">
                </div>
                <div class="p-5 flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-2 bg-sky-50 text-[#0088CC] text-xs font-black px-3 py-1 rounded-full">
                            <i class="fas fa-clock"></i>
                            <?= htmlspecialchars($start) ?><?= $end ? ' - ' . htmlspecialchars($end) : '' ?>
                        </span>
                        <?php if (!empty($event->category_name)): ?>
                        <span class="inline-flex items-center bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full">
                            <?= htmlspecialchars($event->category_name) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 mb-2"><?= htmlspecialchars($event->title) ?></h2>
                    <p class="text-sm font-bold text-slate-500 mb-3">
                        <i class="fas fa-map-marker-alt text-[#0088CC] mr-1"></i>
                        <?= htmlspecialchars($event->place_name) ?>
                    </p>
                    <?php if (!empty($event->description)): ?>
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-2 mb-4"><?= htmlspecialchars($event->description) ?></p>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($event->place_slug) ?>" class="inline-flex items-center gap-2 text-[#0088CC] text-sm font-black hover:text-[#006BA8] transition">
                        ดูรายละเอียดสถานที่ <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
