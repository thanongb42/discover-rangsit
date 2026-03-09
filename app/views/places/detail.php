<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php $place = $data['place']; ?>

<script type="application/ld+json">
    <?= json_encode([
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $place->name,
        "description" => mb_substr(strip_tags($place->description ?? ''), 0, 200),
        "url" => BASE_URL . '/place/' . $place->slug,
        "image" => BASE_URL . '/uploads/covers/' . ($place->cover_image ?: 'default.jpg'),
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $place->address,
            "addressLocality" => "Rangsit",
            "addressRegion" => $place->province ?? "Pathum Thani",
            "addressCountry" => "TH"
        ],
        "geo" => $place->latitude ? [
            "@type" => "GeoCoordinates",
            "latitude" => (float)$place->latitude,
            "longitude" => (float)$place->longitude
        ] : null,
        "telephone" => $place->phone ?: null,
        "aggregateRating" => $place->rating_count > 0 ? [
            "@type" => "AggregateRating",
            "ratingValue" => number_format((float)$place->rating_avg, 1),
            "reviewCount" => (int)$place->rating_count,
            "bestRating" => "5",
            "worstRating" => "1"
        ] : null
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<script type="application/ld+json">
    <?= json_encode([
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            ["@type" => "ListItem", "position" => 1, "name" => "หน้าหลัก", "item" => BASE_URL],
            ["@type" => "ListItem", "position" => 2, "name" => $place->category_name ?? "สถานที่", "item" => BASE_URL . '/?category=' . ($place->category_id ?? '')],
            ["@type" => "ListItem", "position" => 3, "name" => $place->name, "item" => BASE_URL . '/place/' . $place->slug],
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<main class="bg-slate-50 min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-slate-200 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm font-medium text-slate-400">
                <a href="<?= BASE_URL ?>" class="hover:text-navy-800 transition"><?= t('nav.home') ?></a>
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
                        <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($place->cover_image ?: 'default.jpg') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($place->name) ?>">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end">
                            <div class="p-8 text-white">
                                <span class="bg-teal-500 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-3 inline-block"><?= htmlspecialchars($place->category_name) ?></span>
                                <h1 class="text-4xl font-extrabold mb-2"><?= htmlspecialchars($place->name) ?></h1>
                                <div class="flex items-center gap-6 text-sm font-bold text-white/90">
                                    <div class="flex items-center text-yellow-400"><i class="fas fa-star mr-2"></i> <?= number_format($place->rating_avg, 1) ?> (<?= $place->rating_count ?> <?= t('place.reviews_unit') ?>)</div>
                                    <div><i class="fas fa-eye mr-2"></i> <?= number_format($place->views_count) ?> <?= t('place.views') ?></div>
                                    <div class="flex items-center text-blue-300"><i class="fas fa-thumbs-up mr-2"></i> <span id="likeCountOverlay"><?= number_format($data['like_count']) ?></span> <?= t('place.likes') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <!-- Like Reaction Bar -->
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <!-- Like Button -->
                                <button id="likeBtn" onclick="toggleLike()"
                                    class="like-btn flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 border-2 select-none
                                    <?= $data['has_liked'] ? 'bg-[#0088CC] border-[#0088CC] text-white shadow-md shadow-blue-300/30' : 'bg-white border-slate-200 text-slate-600 hover:border-[#0088CC] hover:text-[#0088CC]' ?>"
                                    data-place-id="<?= $place->id ?>"
                                    data-liked="<?= $data['has_liked'] ? '1' : '0' ?>">
                                    <i id="likeBtnIcon" class="<?= $data['has_liked'] ? 'fas' : 'far' ?> fa-thumbs-up text-base transition-transform duration-200"></i>
                                    <span id="likeBtnText"><?= $data['has_liked'] ? t('place.liked') : t('place.like') ?></span>
                                </button>
                            </div>

                            <!-- Like Count (clickable) -->
                            <button onclick="openLikersModal()" id="likeCountBtn"
                                class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#0088CC] transition group <?= $data['like_count'] == 0 ? 'hidden' : '' ?>">
                                <div class="flex -space-x-1.5" id="likerAvatarRow"></div>
                                <span class="group-hover:underline"><span id="likeCountDisplay"><?= number_format($data['like_count']) ?></span> <?= t('place.likes_count') ?></span>
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>

                        <!-- Posted By -->
                        <?php if (!empty($place->owner_name)): ?>
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                            <?php
                                $avatarSrc = !empty($place->owner_avatar)
                                    ? BASE_URL . '/../' . $place->owner_avatar
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($place->owner_name) . '&background=1e3a8a&color=fff';
                            ?>
                            <img src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($place->owner_name) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200">
                            <div>
                                <p class="text-xs text-slate-400"><?= t('place.posted_by') ?></p>
                                <p class="text-sm font-bold text-slate-700"><?= htmlspecialchars($place->owner_name) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <h3 class="text-xl font-bold text-slate-800 mb-4"><?= t('place.about') ?></h3>
                        <p class="text-slate-600 leading-relaxed text-lg"><?= nl2br(htmlspecialchars($place->description)) ?></p>
                    </div>
                </div>

                <!-- Photo Gallery Section -->
                <?php if (!empty($data['gallery'])): ?>
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 pt-7 pb-4 flex items-center justify-between border-b border-slate-50">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-images text-navy-800"></i>
                            <?= isLang('en') ? 'Photo Gallery' : 'คลังภาพ' ?>
                            <span class="text-sm font-normal text-slate-400">(<?= count($data['gallery']) ?>)</span>
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <?php foreach($data['gallery'] as $idx => $img): ?>
                                <?php
                                    // Skip line_qr images if they match the place's line_qr filename
                                    if ($place->line_qr && $img->image_path === $place->line_qr) continue;
                                ?>
                                <div class="aspect-square rounded-2xl overflow-hidden cursor-pointer group relative"
                                     onclick="openGalleryLightbox(<?= $idx ?>, this)">
                                    <img src="<?= BASE_URL ?>/uploads/gallery/<?= htmlspecialchars($img->image_path) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         alt="<?= htmlspecialchars($place->name) ?> photo <?= $idx + 1 ?>"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition duration-300 flex items-center justify-center">
                                        <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition text-xl drop-shadow-lg"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-800"><?= t('place.reviews') ?></h3>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <button onclick="toggleReviewForm()" class="bg-navy-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-navy-900 transition"><?= t('place.write_review') ?></button>
                        <?php endif; ?>
                    </div>

                    <!-- Review Form -->
                    <div id="reviewForm" class="hidden mb-10 p-6 bg-slate-50 rounded-3xl border border-slate-100 animate-fade-in">
                        <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider"><?= t('place.your_experience') ?></h4>
                        <form id="ratingForm" class="space-y-4">
                            <input type="hidden" name="place_id" value="<?= $place->id ?>">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2"><?= t('place.review.rating') ?></label>
                                <div class="star-rating-v2 flex gap-2">
                                    <input type="radio" name="rating" id="star1" value="1" class="hidden peer/s1" required><label for="star1" class="cursor-pointer text-2xl text-slate-300 peer-checked/s1:text-yellow-400 hover:text-yellow-300"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star2" value="2" class="hidden peer/s2"><label for="star2" class="cursor-pointer text-2xl text-slate-300 peer-checked/s2:text-yellow-400 hover:text-yellow-300"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star3" value="3" class="hidden peer/s3"><label for="star3" class="cursor-pointer text-2xl text-slate-300 peer-checked/s3:text-yellow-400 hover:text-yellow-300"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star4" value="4" class="hidden peer/s4"><label for="star4" class="cursor-pointer text-2xl text-slate-300 peer-checked/s4:text-yellow-400 hover:text-yellow-300"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star5" value="5" class="hidden peer/s5"><label for="star5" class="cursor-pointer text-2xl text-slate-300 peer-checked/s5:text-yellow-400 hover:text-yellow-300"><i class="fas fa-star"></i></label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2"><?= t('place.review.comment') ?></label>
                                <textarea name="comment" rows="3" class="w-full bg-white border border-slate-200 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-navy-800" placeholder="<?= t('place.review.placeholder') ?>"></textarea>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" id="btnSubmitReview" class="bg-primary-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-600 transition"><?= t('place.review.submit') ?></button>
                                <button type="button" onclick="toggleReviewForm()" class="text-slate-400 font-bold px-4 py-2.5 text-sm"><?= t('place.review.cancel') ?></button>
                            </div>
                        </form>
                    </div>

                    <style>
                        .star-rating-v2:has(.peer\/s2:checked) label[for="star1"],
                        .star-rating-v2:has(.peer\/s3:checked) label[for="star1"], .star-rating-v2:has(.peer\/s3:checked) label[for="star2"],
                        .star-rating-v2:has(.peer\/s4:checked) label[for="star1"], .star-rating-v2:has(.peer\/s4:checked) label[for="star2"], .star-rating-v2:has(.peer\/s4:checked) label[for="star3"],
                        .star-rating-v2:has(.peer\/s5:checked) label[for="star1"], .star-rating-v2:has(.peer\/s5:checked) label[for="star2"], .star-rating-v2:has(.peer\/s5:checked) label[for="star3"], .star-rating-v2:has(.peer\/s5:checked) label[for="star4"] {
                            color: #fbbf24;
                        }
                    </style>

                    <?php if(empty($data['reviews'])): ?>
                        <div id="noReviewsState" class="bg-slate-50 rounded-2xl p-10 text-center border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                <i class="fas fa-comment-alt text-2xl"></i>
                            </div>
                            <p class="text-slate-400 font-medium"><?= t('place.review.first') ?></p>
                            <?php if(!isset($_SESSION['user_id'])): ?>
                                <a href="<?= BASE_URL ?>/login" class="text-navy-800 font-bold text-sm mt-4 inline-block hover:underline"><?= t('place.review.login') ?></a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div id="reviewsList" class="space-y-6">
                            <?php foreach($data['reviews'] as $review): ?>
                                <div class="flex gap-4 p-4 hover:bg-slate-50 rounded-2xl transition duration-200">
                                    <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-slate-100 border border-slate-200">
                                        <img src="<?= $review->profile_image ? BASE_URL . '/../' . $review->profile_image : 'https://ui-avatars.com/api/?name=' . $review->first_name ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <h5 class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($review->first_name . ' ' . $review->last_name) ?></h5>
                                            <span class="text-[10px] text-slate-400 font-bold"><?= date('d M Y', strtotime($review->created_at)) ?></span>
                                        </div>
                                        <div class="flex text-yellow-400 text-[10px] mb-2">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?= $i <= $review->rating ? 'fas' : 'far' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <script>
                    function toggleReviewForm() {
                        const form = document.getElementById('reviewForm');
                        form.classList.toggle('hidden');
                    }

                    document.getElementById('ratingForm')?.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const submitBtn = document.getElementById('btnSubmitReview');
                        
                        try {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = `<i class="fas fa-circle-notch fa-spin mr-2"></i> ${DETAIL_LANG.saving}`;

                            const response = await fetch('<?= BASE_URL ?>/api/place/review', {
                                method: 'POST',
                                body: formData
                            });
                            
                            const res = await response.json();
                            
                            if(res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '<?= addslashes(isLang('en') ? 'Success!' : 'สำเร็จ!') ?>',
                                    text: res.message,
                                    confirmButtonText: '<?= addslashes(isLang('en') ? 'OK' : 'ตกลง') ?>',
                                    confirmButtonColor: '#1e3a8a'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('<?= addslashes(isLang('en') ? 'Error' : 'ข้อผิดพลาด') ?>', res.message, 'error');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = DETAIL_LANG.submitReview;
                            }
                        } catch (error) {
                            console.error('Review Error:', error);
                            Swal.fire('<?= addslashes(isLang('en') ? 'Error' : 'ข้อผิดพลาด') ?>', '<?= addslashes(isLang('en') ? 'Could not save. Please try again.' : 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง') ?>', 'error');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Submit Review';
                        }
                    });
                </script>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-navy-800"></i> <?= t('place.contact') ?>
                    </h4>
                    <ul class="space-y-5">
                        <li class="flex items-start group">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 text-slate-400 group-hover:bg-navy-50 group-hover:text-navy-800 transition">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <div class="flex-1 pt-1">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5"><?= t('place.address') ?></span>
                                <span class="text-sm font-semibold text-slate-700 leading-tight"><?= htmlspecialchars($place->address) ?></span>
                            </div>
                        </li>
                        <?php if($place->phone): ?>
                        <li class="flex items-start group">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 text-slate-400 group-hover:bg-navy-50 group-hover:text-navy-800 transition">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <div class="flex-1 pt-1">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5"><?= t('place.phone') ?></span>
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
                                <span class="block text-[10px] uppercase font-bold text-slate-400 mb-0.5"><?= t('place.website') ?></span>
                                <a href="<?= htmlspecialchars($place->website) ?>" target="_blank" class="text-sm font-bold text-navy-800 hover:underline break-all"><?= t('place.website.visit') ?></a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Social Media Grid -->
                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <span class="block text-[10px] uppercase font-bold text-slate-400 mb-4 px-1"><?= t('place.social') ?></span>
                        <div class="grid grid-cols-4 gap-3">
                            <?php if($place->facebook): ?>
                                <a href="<?= htmlspecialchars($place->facebook) ?>" target="_blank" class="w-full aspect-square bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100" title="Facebook">
                                    <i class="fab fa-facebook-f text-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($place->instagram): ?>
                                <a href="<?= htmlspecialchars($place->instagram) ?>" target="_blank" class="w-full aspect-square bg-pink-50 rounded-2xl flex items-center justify-center text-pink-600 hover:bg-pink-600 hover:text-white transition shadow-sm border border-pink-100" title="Instagram">
                                    <i class="fab fa-instagram text-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($place->tiktok): ?>
                                <a href="<?= htmlspecialchars($place->tiktok) ?>" target="_blank" class="w-full aspect-square bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 hover:bg-black hover:text-white transition shadow-sm border border-slate-200" title="TikTok">
                                    <i class="fab fa-tiktok text-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($place->line): ?>
                                <a href="https://line.me/ti/p/~<?= htmlspecialchars($place->line) ?>" target="_blank" class="w-full aspect-square bg-green-50 rounded-2xl flex items-center justify-center text-green-600 hover:bg-green-600 hover:text-white transition shadow-sm border border-green-100" title="LINE ID: <?= htmlspecialchars($place->line) ?>">
                                    <i class="fab fa-line text-xl"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($place->youtube): ?>
                                <a href="<?= htmlspecialchars($place->youtube) ?>" target="_blank" class="w-full aspect-square bg-red-50 rounded-2xl flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100" title="YouTube">
                                    <i class="fab fa-youtube text-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($place->x): ?>
                                <a href="<?= htmlspecialchars($place->x) ?>" target="_blank" class="w-full aspect-square bg-slate-900 rounded-2xl flex items-center justify-center text-white hover:bg-black transition shadow-sm" title="X (Twitter)">
                                    <i class="fa-brands fa-x-twitter text-lg"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($place->line_qr): ?>
                    <!-- LINE QR Section -->
                    <div class="mt-6 p-4 bg-green-50 rounded-[2rem] border border-green-100 text-center">
                        <p class="text-[10px] font-black text-green-700 uppercase tracking-widest mb-3"><?= t('place.line.scan') ?></p>
                        <img src="<?= BASE_URL ?>/uploads/gallery/<?= $place->line_qr ?>" class="w-32 h-32 mx-auto rounded-xl shadow-sm bg-white p-1 border border-green-200" alt="LINE QR Code">
                        <?php if($place->line): ?>
                            <p class="mt-2 text-xs font-bold text-green-800">ID: <?= htmlspecialchars($place->line) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Navigation Button -->
                <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $place->latitude ?>,<?= $place->longitude ?>" target="_blank" class="flex items-center justify-center gap-3 w-full bg-emerald-500 text-white py-5 rounded-[2rem] font-black text-lg hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-directions text-2xl"></i>
                    <?= t('place.navigate') ?>
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Gallery Lightbox -->
<?php if (!empty($data['gallery'])): ?>
<div id="galleryLightbox" class="hidden fixed inset-0 bg-black/90 z-[3000] flex items-center justify-center p-4"
     onclick="if(event.target===this||event.target.id==='galleryLightbox')closeLightbox()">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 transition z-10">
        <i class="fas fa-times text-xl"></i>
    </button>
    <button onclick="prevPhoto()" class="absolute left-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white w-11 h-11 flex items-center justify-center rounded-full hover:bg-white/10 transition z-10">
        <i class="fas fa-chevron-left text-xl"></i>
    </button>
    <button onclick="nextPhoto()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white w-11 h-11 flex items-center justify-center rounded-full hover:bg-white/10 transition z-10">
        <i class="fas fa-chevron-right text-xl"></i>
    </button>
    <img id="lightboxImg" src="" alt="" class="max-w-full max-h-[88vh] rounded-2xl shadow-2xl object-contain select-none">
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/50 text-sm font-bold" id="lightboxCounter"></div>
</div>
<script>
const GALLERY_IMGS = <?= json_encode(array_values(array_filter(
    array_map(fn($img) => $img->image_path, $data['gallery']),
    fn($path) => $path !== ($place->line_qr ?? '')
)), JSON_UNESCAPED_UNICODE) ?>;
let lightboxIdx = 0;

function openGalleryLightbox(idx) {
    // idx from PHP may include filtered items; find by clicked img src
    const clickedEl = event.currentTarget || event.target.closest('[onclick]');
    const imgSrc = clickedEl ? clickedEl.querySelector('img')?.src : null;
    if (imgSrc) {
        const filename = imgSrc.split('/').pop();
        const found = GALLERY_IMGS.findIndex(p => p === filename);
        if (found !== -1) idx = found;
    }
    lightboxIdx = Math.min(idx, GALLERY_IMGS.length - 1);
    showLightboxImg();
    document.getElementById('galleryLightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('galleryLightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
function showLightboxImg() {
    const lb = document.getElementById('lightboxImg');
    lb.style.opacity = '0';
    lb.src = '<?= BASE_URL ?>/uploads/gallery/' + GALLERY_IMGS[lightboxIdx];
    lb.onload = () => { lb.style.transition = 'opacity .2s'; lb.style.opacity = '1'; };
    document.getElementById('lightboxCounter').textContent = (lightboxIdx + 1) + ' / ' + GALLERY_IMGS.length;
}
function prevPhoto() { lightboxIdx = (lightboxIdx - 1 + GALLERY_IMGS.length) % GALLERY_IMGS.length; showLightboxImg(); }
function nextPhoto() { lightboxIdx = (lightboxIdx + 1) % GALLERY_IMGS.length; showLightboxImg(); }
document.addEventListener('keydown', e => {
    if (document.getElementById('galleryLightbox').classList.contains('hidden')) return;
    if (e.key === 'ArrowLeft') prevPhoto();
    else if (e.key === 'ArrowRight') nextPhoto();
    else if (e.key === 'Escape') closeLightbox();
});
</script>
<?php endif; ?>

<!-- Likers Modal -->
<div id="likersModal" class="hidden fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-4 backdrop-blur-sm" onclick="if(event.target===this)closeLikersModal()">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm overflow-hidden animate-fade-in">
        <div class="flex items-center justify-between p-5 border-b border-slate-50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-thumbs-up text-[#0088CC]"></i>
                <?= t('place.likers.title') ?> (<span id="likersCount">0</span>)
            </h3>
            <button onclick="closeLikersModal()" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <div id="likersList" class="overflow-y-auto max-h-[60vh] p-4 space-y-3">
        </div>
    </div>
</div>

<style>
    .like-btn:active { transform: scale(0.93); }
    @keyframes likePopIn {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.35) rotate(-12deg); }
        70%  { transform: scale(0.9) rotate(6deg); }
        100% { transform: scale(1) rotate(0deg); }
    }
    .like-pop { animation: likePopIn 0.4s cubic-bezier(.36,.07,.19,.97) both; }
</style>

<script>
const BASE_URL_DETAIL = '<?= BASE_URL ?>';
const PLACE_ID        = <?= (int)$place->id ?>;
let likeCount         = <?= (int)$data['like_count'] ?>;
let hasLiked          = <?= $data['has_liked'] ? 'true' : 'false' ?>;
const isLoggedIn      = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
const DETAIL_LANG = {
    like:         '<?= addslashes(t('place.like')) ?>',
    liked:        '<?= addslashes(t('place.liked')) ?>',
    likesCount:   '<?= addslashes(t('place.likes_count')) ?>',
    loginTitle:   '<?= addslashes(t('place.like.login_title')) ?>',
    loginText:    '<?= addslashes(t('place.like.login_text')) ?>',
    loginBtn:     '<?= addslashes(t('place.like.login_btn')) ?>',
    cancel:       '<?= addslashes(t('place.like.cancel')) ?>',
    likedAt:      '<?= addslashes(t('place.like.liked_at')) ?>',
    loading:      '<?= addslashes(t('map.loading')) ?>',
    likersEmpty:  '<?= addslashes(t('place.likers.empty')) ?>',
    likersError:  '<?= addslashes(t('place.likers.error')) ?>',
    saving:       '<?= addslashes(t('place.saving')) ?>',
    submitReview: '<?= addslashes(t('place.review.submit')) ?>',
    dateLocale:   '<?= currentLang() === 'th' ? 'th-TH' : 'en-GB' ?>',
};

async function toggleLike() {
    if (!isLoggedIn) {
        Swal.fire({
            icon: 'info',
            title: DETAIL_LANG.loginTitle,
            text: DETAIL_LANG.loginText,
            confirmButtonText: DETAIL_LANG.loginBtn,
            confirmButtonColor: '#0088CC',
            showCancelButton: true,
            cancelButtonText: DETAIL_LANG.cancel
        }).then(r => { if (r.isConfirmed) location.href = BASE_URL_DETAIL + '/login'; });
        return;
    }

    const btn  = document.getElementById('likeBtn');
    const icon = document.getElementById('likeBtnIcon');
    btn.disabled = true;

    // Optimistic UI
    hasLiked = !hasLiked;
    likeCount += hasLiked ? 1 : -1;
    updateLikeBtnUI(hasLiked);
    updateLikeCounts(likeCount);

    // Pop animation
    icon.classList.add('like-pop');
    icon.addEventListener('animationend', () => icon.classList.remove('like-pop'), { once: true });

    try {
        const res  = await fetch(BASE_URL_DETAIL + '/api/place/like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ place_id: PLACE_ID })
        });
        const data = await res.json();
        if (data.success) {
            hasLiked  = data.liked;
            likeCount = data.count;
            updateLikeBtnUI(data.liked);
            updateLikeCounts(data.count);
        }
    } catch(e) {
        // revert optimistic
        hasLiked = !hasLiked;
        likeCount += hasLiked ? 1 : -1;
        updateLikeBtnUI(hasLiked);
        updateLikeCounts(likeCount);
    } finally {
        btn.disabled = false;
    }
}

function updateLikeBtnUI(liked) {
    const btn  = document.getElementById('likeBtn');
    const icon = document.getElementById('likeBtnIcon');
    const text = document.getElementById('likeBtnText');
    if (liked) {
        btn.className  = btn.className.replace(/bg-white border-slate-200 text-slate-600 hover:border-\[#0088CC\] hover:text-\[#0088CC\]/g, '');
        btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-600', 'hover:border-[#0088CC]', 'hover:text-[#0088CC]');
        btn.classList.add('bg-[#0088CC]', 'border-[#0088CC]', 'text-white', 'shadow-md', 'shadow-blue-300/30');
        icon.classList.replace('far', 'fas');
        text.textContent = DETAIL_LANG.liked;
    } else {
        btn.classList.remove('bg-[#0088CC]', 'border-[#0088CC]', 'text-white', 'shadow-md', 'shadow-blue-300/30');
        btn.classList.add('bg-white', 'border-slate-200', 'text-slate-600', 'hover:border-[#0088CC]', 'hover:text-[#0088CC]');
        icon.classList.replace('fas', 'far');
        text.textContent = DETAIL_LANG.like;
    }
}

function updateLikeCounts(count) {
    const formatted = count.toLocaleString('th-TH');
    document.getElementById('likeCountDisplay').textContent = formatted;
    document.getElementById('likeCountOverlay').textContent = formatted;
    const countBtn = document.getElementById('likeCountBtn');
    countBtn.classList.toggle('hidden', count === 0);
}

async function openLikersModal() {
    document.getElementById('likersModal').classList.remove('hidden');
    document.getElementById('likersList').innerHTML =
        `<div class="flex items-center justify-center py-8 text-slate-400"><i class="fas fa-spinner fa-spin mr-2"></i> ${DETAIL_LANG.loading}</div>`;

    try {
        const res    = await fetch(BASE_URL_DETAIL + '/api/place/likers?place_id=' + PLACE_ID);
        const likers = await res.json();
        document.getElementById('likersCount').textContent = likers.length;
        renderLikersList(likers);
        renderLikerAvatars(likers);
    } catch(e) {
        document.getElementById('likersList').innerHTML =
            `<p class="text-center text-slate-400 py-6">${DETAIL_LANG.likersError}</p>`;
    }
}

function renderLikersList(likers) {
    const container = document.getElementById('likersList');
    if (likers.length === 0) {
        container.innerHTML = `<div class="text-center text-slate-400 py-8"><i class="far fa-thumbs-up text-4xl mb-3 block"></i><p>${DETAIL_LANG.likersEmpty}</p></div>`;
        return;
    }
    container.innerHTML = likers.map(u => {
        const name    = (u.first_name || '') + ' ' + (u.last_name || '');
        const initial = (u.first_name || u.username || 'U')[0].toUpperCase();
        const avatar  = u.profile_image
            ? (u.profile_image.startsWith('http') ? u.profile_image : BASE_URL_DETAIL + '/../' + u.profile_image)
            : null;
        const date    = new Date(u.liked_at).toLocaleDateString(DETAIL_LANG.dateLocale, { day: 'numeric', month: 'short', year: 'numeric' });

        return `<div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-[#0088CC] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                ${avatar ? `<img src="${avatar}" class="w-full h-full object-cover">` : initial}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-slate-800 text-sm truncate">${name.trim() || u.username}</p>
                <p class="text-[10px] text-slate-400">${DETAIL_LANG.likedAt} ${date}</p>
            </div>
            <i class="fas fa-thumbs-up text-[#0088CC] text-sm"></i>
        </div>`;
    }).join('');
}

function renderLikerAvatars(likers) {
    const row = document.getElementById('likerAvatarRow');
    row.innerHTML = likers.slice(0, 4).map(u => {
        const avatar  = u.profile_image
            ? (u.profile_image.startsWith('http') ? u.profile_image : BASE_URL_DETAIL + '/../' + u.profile_image)
            : null;
        const initial = (u.first_name || u.username || 'U')[0].toUpperCase();
        return `<div class="w-6 h-6 rounded-full border-2 border-white overflow-hidden bg-[#0088CC] flex items-center justify-center text-white text-[8px] font-bold">
            ${avatar ? `<img src="${avatar}" class="w-full h-full object-cover">` : initial}
        </div>`;
    }).join('');
}

function closeLikersModal() {
    document.getElementById('likersModal').classList.add('hidden');
}

// Pre-load avatars if count > 0
document.addEventListener('DOMContentLoaded', () => {
    if (likeCount > 0) {
        fetch(BASE_URL_DETAIL + '/api/place/likers?place_id=' + PLACE_ID)
            .then(r => r.json())
            .then(likers => renderLikerAvatars(likers))
            .catch(() => {});
    }
});
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>