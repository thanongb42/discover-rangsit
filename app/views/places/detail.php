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
                        <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($place->cover_image ?: 'default.jpg') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($place->name) ?>">
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

                <!-- Reviews Section -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Recent Reviews</h3>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <button onclick="toggleReviewForm()" class="bg-navy-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-navy-900 transition">Write a Review</button>
                        <?php endif; ?>
                    </div>

                    <!-- Review Form (Hidden by default) -->
                    <div id="reviewForm" class="hidden mb-10 p-6 bg-slate-50 rounded-3xl border border-slate-100 animate-fade-in">
                        <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider">Your Experience</h4>
                        <form id="ratingForm" class="space-y-4">
                            <input type="hidden" name="place_id" value="<?= $place->id ?>">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Rating</label>
                                <div class="star-rating">
                                    <input type="radio" name="rating" id="star5" value="5"><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Comment</label>
                                <textarea name="comment" rows="3" class="w-full bg-white border border-slate-200 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-navy-800" placeholder="Tell others about this place..."></textarea>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-primary-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-600 transition">Submit Review</button>
                                <button type="button" onclick="toggleReviewForm()" class="text-slate-400 font-bold px-4 py-2.5 text-sm">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <?php if(empty($data['reviews'])): ?>
                        <div id="noReviewsState" class="bg-slate-50 rounded-2xl p-10 text-center border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                <i class="fas fa-comment-alt text-2xl"></i>
                            </div>
                            <p class="text-slate-400 font-medium">Be the first to share your experience!</p>
                            <?php if(!isset($_SESSION['user_id'])): ?>
                                <a href="<?= BASE_URL ?>/login" class="text-navy-800 font-bold text-sm mt-4 inline-block hover:underline">Login to review</a>
                            <?php endif; ?>
                        </div>
                        <div id="reviewsList" class="space-y-6"></div>
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
                        const rating = formData.get('rating');
                        const comment = formData.get('comment');
                        
                        try {
                            const submitBtn = this.querySelector('button[type="submit"]');
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';

                            const response = await fetch('<?= BASE_URL ?>/api/place/review', {
                                method: 'POST',
                                body: formData
                            });
                            
                            const res = await response.json();
                            
                            if(res.success) {
                                Swal.fire({ 
                                    icon: 'success', 
                                    title: 'สำเร็จ', 
                                    text: res.message, 
                                    timer: 2000, 
                                    showConfirmButton: false 
                                });

                                // Dynamic DOM Update
                                const reviewsList = document.getElementById('reviewsList');
                                const noReviewsState = document.getElementById('noReviewsState');
                                if(noReviewsState) noReviewsState.remove();

                                // Prepend new review
                                const newReviewHtml = `
                                    <div class="flex gap-4 p-4 bg-primary-50 rounded-2xl border border-primary-100 animate-fade-in">
                                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-slate-100 border border-slate-200">
                                            <img src="<?= isset($_SESSION['profile_image']) ? BASE_URL . '/../' . $_SESSION['profile_image'] : 'https://ui-avatars.com/api/?name=' . ($_SESSION['user_name'] ?? 'User') ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-1">
                                                <h5 class="font-bold text-slate-800 text-sm"><?= $_SESSION['user_name'] ?? 'You' ?></h5>
                                                <span class="text-[10px] text-primary-600 font-bold">Just now</span>
                                            </div>
                                            <div class="flex text-yellow-400 text-[10px] mb-2">
                                                ${'<i class="fas fa-star"></i>'.repeat(rating)}${'<i class="far fa-star"></i>'.repeat(5-rating)}
                                            </div>
                                            <p class="text-slate-600 text-sm leading-relaxed">${comment.replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                `;
                                reviewsList.insertAdjacentHTML('afterbegin', newReviewHtml);

                                // Reset form and close
                                this.reset();
                                toggleReviewForm();
                                
                                // Restore button
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = 'Submit Review';
                            } else {
                                Swal.fire('ข้อผิดพลาด', res.message, 'error');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = 'Submit Review';
                            }
                        } catch (error) {
                            console.error('Submission Error:', error);
                            Swal.fire('Error', 'เกิดข้อผิดพลาดในการส่งข้อมูล', 'error');
                            const submitBtn = this.querySelector('button[type="submit"]');
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

                    <!-- Social Media Grid -->
                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <span class="block text-[10px] uppercase font-bold text-slate-400 mb-4 px-1">Social Connect</span>
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
                        <p class="text-[10px] font-black text-green-700 uppercase tracking-widest mb-3">Scan to Add LINE</p>
                        <img src="<?= BASE_URL ?>/../uploads/gallery/<?= $place->line_qr ?>" class="w-32 h-32 mx-auto rounded-xl shadow-sm bg-white p-1 border border-green-200" alt="LINE QR Code">
                        <?php if($place->line): ?>
                            <p class="mt-2 text-xs font-bold text-green-800">ID: <?= htmlspecialchars($place->line) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
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