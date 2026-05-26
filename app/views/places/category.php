<?php
$cat      = $data['category'];
$places   = $data['places'];
$cats     = $data['categories'];
$catName  = htmlspecialchars($cat->name);
$catColor = htmlspecialchars($cat->color ?? '#0088CC');
$iconRaw  = $cat->icon ?? 'fa-tag';
$iconCls  = (strpos($iconRaw, ' ') === false) ? 'fas ' . $iconRaw : $iconRaw;
$count    = count($places);

include BASE_PATH . '/app/views/layouts/header.php';
?>

<!-- JSON-LD: ItemList -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "<?= addslashes($catName) ?>ในรังสิต",
  "description": "รวม<?= addslashes($catName) ?>ในรังสิต <?= $count ?> แห่ง",
  "url": "<?= BASE_URL . '/category/' . htmlspecialchars($cat->slug) ?>",
  "numberOfItems": <?= $count ?>,
  "itemListElement": [
    <?php foreach ($places as $i => $p): ?>
    {
      "@type": "ListItem",
      "position": <?= $i + 1 ?>,
      "url": "<?= BASE_URL . '/place/' . htmlspecialchars($p->slug) ?>",
      "name": "<?= addslashes(htmlspecialchars($p->name)) ?>"
    }<?= ($i < $count - 1) ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<!-- Breadcrumb -->
<nav class="bg-white border-b border-slate-100">
    <div class="container mx-auto px-4 py-3 flex items-center gap-2 text-sm text-slate-400">
        <a href="<?= BASE_URL ?>" class="hover:text-[#0088CC] transition font-medium">Discover Rangsit</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="font-bold text-slate-700"><?= $catName ?></span>
    </div>
</nav>

<!-- Hero -->
<section class="py-12" style="background:linear-gradient(135deg, <?= $catColor ?>18 0%, white 60%);">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                 style="background:<?= $catColor ?>;">
                <i class="<?= htmlspecialchars($iconCls) ?> text-white text-2xl"></i>
            </div>
            <div>
                <div class="text-xs font-black uppercase tracking-widest mb-1" style="color:<?= $catColor ?>">หมวดหมู่</div>
                <h1 class="text-3xl font-black text-slate-800 leading-tight"><?= $catName ?><span class="text-slate-400 font-medium">รังสิต</span></h1>
                <p class="text-slate-500 mt-1 text-sm">พบ <strong class="text-slate-700"><?= $count ?> แห่ง</strong> ในนครรังสิต</p>
            </div>
        </div>

        <!-- SEO paragraph -->
        <div class="mt-6 max-w-2xl text-slate-500 text-sm leading-relaxed">
            รวม<strong class="text-slate-700"><?= $catName ?>รังสิต</strong> ทั้งหมด <?= $count ?> แห่ง
            ค้นหา<?= $catName ?>นครรังสิต ที่ดีที่สุด พร้อมรีวิวจากผู้ใช้จริง แผนที่ และข้อมูลติดต่อครบถ้วน
            โดยเทศบาลนครรังสิต
        </div>
    </div>
</section>

<!-- Places Grid -->
<section class="container mx-auto px-4 py-10">
    <?php if (empty($places)): ?>
        <div class="text-center py-20 text-slate-400">
            <i class="fas fa-store-slash text-5xl mb-4 block opacity-30"></i>
            <p class="font-bold text-lg">ยังไม่มีสถานที่ในหมวดนี้</p>
            <p class="text-sm mt-1">ช่วยกันเพิ่มข้อมูลให้ชุมชนรังสิต</p>
            <a href="<?= BASE_URL ?>/dashboard/add-place"
               class="inline-block mt-6 bg-[#0088CC] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#006BA8] transition">
                เพิ่มสถานที่
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4" id="placesGrid">
            <?php foreach ($places as $p):
                $cover  = htmlspecialchars($p->cover_image ?: 'default.jpg');
                $rating = (float)($p->rating_avg ?? 0);
                $views  = (int)($p->views_count ?? 0);
            ?>
            <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($p->slug) ?>"
               class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 overflow-hidden flex flex-col">
                <div class="relative h-32 overflow-hidden bg-slate-100">
                    <img src="<?= BASE_URL ?>/uploads/covers/<?= $cover ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                         loading="lazy"
                         alt="<?= htmlspecialchars($p->name) ?> รังสิต">
                </div>
                <div class="p-3 flex-1 flex flex-col">
                    <p class="font-bold text-slate-800 text-sm leading-tight line-clamp-2"><?= htmlspecialchars($p->name) ?></p>
                    <?php if ($rating > 0): ?>
                    <div class="mt-auto pt-2 flex items-center gap-1 text-xs">
                        <span class="text-yellow-500 font-bold">★ <?= number_format($rating, 1) ?></span>
                        <span class="text-slate-300">·</span>
                        <span class="text-slate-400"><?= $views ?> views</span>
                    </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- CTA -->
        <div class="mt-12 text-center">
            <p class="text-slate-400 text-sm mb-3">มี<?= $catName ?>ในรังสิตที่ยังไม่ได้อยู่ในรายการ?</p>
            <a href="<?= BASE_URL ?>/dashboard/add-place"
               class="inline-flex items-center gap-2 bg-[#0088CC] hover:bg-[#006BA8] text-white font-bold px-6 py-3 rounded-xl transition text-sm">
                <i class="fas fa-plus"></i> เพิ่มสถานที่ฟรี
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- Other Categories -->
<?php if (!empty($cats)): ?>
<section class="bg-slate-50 border-t border-slate-100 py-10">
    <div class="container mx-auto px-4">
        <h2 class="text-lg font-black text-slate-700 mb-5">หมวดหมู่อื่นในรังสิต</h2>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($cats as $c):
                if ($c->id == $cat->id) continue;
                $cIcon  = $c->icon ?? 'fa-tag';
                $cCls   = (strpos($cIcon, ' ') === false) ? 'fas ' . $cIcon : $cIcon;
                $cColor = htmlspecialchars($c->color ?? '#0088CC');
            ?>
            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($c->slug) ?>"
               class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 hover:shadow-sm px-4 py-2 rounded-xl text-sm font-bold text-slate-700 transition">
                <i class="<?= htmlspecialchars($cCls) ?> text-sm" style="color:<?= $cColor ?>"></i>
                <?= htmlspecialchars($c->name) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>
