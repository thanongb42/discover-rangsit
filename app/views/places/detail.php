<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php $place = $data['place']; ?>

<script type="application/ld+json">
    <?= json_encode([
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $place->name,
        "image" => BASE_URL . '/../uploads/covers/' . ($place->cover_image ?: 'default.jpg'),
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $place->address,
            "addressLocality" => "Rangsit",
            "addressRegion" => $place->province,
            "addressCountry" => "TH"
        ],
        "geo" => [
            "@type" => "GeoCoordinates",
            "latitude" => $place->latitude,
            "longitude" => $place->longitude
        ],
        "telephone" => $place->phone,
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => $place->rating_avg,
            "reviewCount" => $place->rating_count > 0 ? $place->rating_count : 1
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<main class="bg-slate-50 min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-slate-200 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm font-medium text-slate-400">
                <a href="<?= BASE_URL ?>" class="hover:text-navy-800 transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-slate-600"><?= htmlspecialchars($place->category_name) ?></span>
                <span class="mx-2">/</span>
                <span class="text-navy-800 font-bold"><?= htmlspecialchars($place->name) ?></span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-white">
                    <div class="relative h-[400px]">
                        <img src="<?= BASE_URL ?>/../uploads/covers/<?= htmlspecialchars($place->cover_image ?: 'default.jpg') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($place->name) ?>">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end">
                            <div class="p-8 text-white">
                                <span class="bg-teal-500 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-3 inline-block"><?= htmlspecialchars($place->category_name) ?></span>
                                <h1 class="text-4xl font-extrabold mb-2"><?= htmlspecialchars($place->name) ?></h1>
                                <div class="flex items-center gap-6 text-sm font-bold text-white/90">
                                    <div class="flex items-center text-yellow-400"><i class="fas fa-star mr-2"></i> <?= number_format($place->rating_avg, 1) ?> (<?= $place->rating_count ?> reviews)</div>
                                    <div><i class="fas fa-eye mr-2"></i> <?= number_format($place->views_count) ?> views</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-4">About this place</h3>
                        <p class="text-slate-600 leading-relaxed text-lg"><?= nl2br(htmlspecialchars($place->description)) ?></p>
                    </div>
                </div>

                <!-- Reviews Placeholder -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Recent Reviews</h3>
                        <button class="bg-navy-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-navy-900 transition">Write a Review</button>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-10 text-center border border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                            <i class="fas fa-comment-alt text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-medium">Be the first to share your experience!</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-navy-800"></i> Contact Details
                    </h4>
                    <ul class="space-y-5">
                        <li class="flex items-start group">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 text-slate-400 group-hover:bg-navy-50 group-hover:text-navy-800 transition">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <div class="flex-1 pt-1">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5">Address</span>
                                <span class="text-sm font-semibold text-slate-700 leading-tight"><?= htmlspecialchars($place->address) ?></span>
                            </div>
                        </li>
                        <?php if($place->phone): ?>
                        <li class="flex items-start group">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 text-slate-400 group-hover:bg-navy-50 group-hover:text-navy-800 transition">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <div class="flex-1 pt-1">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5">Phone</span>
                                <a href="tel:<?= htmlspecialchars($place->phone) ?>" class="text-sm font-bold text-navy-800 hover:underline"><?= htmlspecialchars($place->phone) ?></a>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if($place->website): ?>
                        <li class="flex items-start group">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 text-slate-400 group-hover:bg-navy-50 group-hover:text-navy-800 transition">
                                <i class="fas fa-globe text-sm"></i>
                            </div>
                            <div class="flex-1 pt-1">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5">Website</span>
                                <a href="<?= htmlspecialchars($place->website) ?>" target="_blank" class="text-sm font-bold text-navy-800 hover:underline break-all">Visit Official Site</a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Navigation Button -->
                <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $place->latitude ?>,<?= $place->longitude ?>" target="_blank" class="flex items-center justify-center gap-3 w-full bg-emerald-500 text-white py-5 rounded-[2rem] font-black text-lg hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-directions text-2xl"></i>
                    GET DIRECTIONS
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>