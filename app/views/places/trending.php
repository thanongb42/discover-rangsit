<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<section class="bg-navy-900 text-white py-16 px-4">
    <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight"><i class="fas fa-fire text-orange-500 mr-3"></i>Trending Places</h1>
        <p class="text-teal-100 text-lg opacity-80">Discover the most popular spots in Rangsit City right now.</p>
    </div>
</section>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if(empty($data['places'])): ?>
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <i class="fas fa-info-circle text-slate-200 text-5xl mb-4"></i>
                <p class="text-slate-400 font-medium">No trending places found at the moment.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['places'] as $index => $place): ?>
                <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?= BASE_URL ?>/../uploads/covers/<?= htmlspecialchars($place->cover_image ?: 'default.jpg') ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="<?= htmlspecialchars($place->name) ?>">
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-600 text-white text-xs font-black px-3 py-1 rounded-full shadow-lg">#<?= $index + 1 ?> TRENDING</span>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-navy-900 border border-white">
                            <i class="fas fa-tag text-teal-500 mr-1"></i> <?= htmlspecialchars($place->category_name) ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <h5 class="text-xl font-bold text-slate-800 mb-2 truncate"><?= htmlspecialchars($place->name) ?></h5>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="text-yellow-500 font-bold flex items-center">
                                <i class="fas fa-star mr-1"></i> <?= number_format($place->rating_avg, 1) ?>
                            </div>
                            <div class="text-slate-400 text-sm">
                                <i class="fas fa-eye mr-1"></i> <?= number_format($place->views_count) ?> views
                            </div>
                        </div>
                        <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                            <?= htmlspecialchars($place->description) ?>
                        </p>
                        <a href="<?= BASE_URL ?>/place/<?= htmlspecialchars($place->slug) ?>" class="block w-full bg-slate-900 text-white text-center py-3 rounded-2xl font-bold hover:bg-navy-800 transition">
                            View Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>