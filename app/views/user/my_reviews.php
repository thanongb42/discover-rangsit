<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">รีวิวของฉัน</h1>
    <p class="text-gray-500 text-sm">ประวัติการให้คะแนนและแสดงความคิดเห็นของคุณต่อสถานที่ต่างๆ</p>
</div>

<div class="max-w-4xl">
    <?php if(empty($data['reviews'])): ?>
        <div class="py-20 text-center bg-white rounded-[3rem] border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-star text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">คุณยังไม่เคยเขียนรีวิว</h3>
            <p class="text-gray-400 mb-6">ออกไปค้นหาและแบ่งปันประสบการณ์ของคุณในเมืองรังสิตกันเถอะ</p>
            <a href="<?= BASE_URL ?>/" class="bg-navy-800 text-white px-8 py-3 rounded-2xl font-bold shadow-lg transition hover:bg-navy-900">สำรวจเมืองรังสิต</a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach($data['reviews'] as $review): ?>
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 hover:shadow-md transition duration-300">
                    <div class="w-full md:w-32 h-32 rounded-2xl overflow-hidden bg-slate-50 shrink-0">
                        <img src="<?= BASE_URL ?>/uploads/covers/<?= $review->cover_image ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($review->place_name) ?></h3>
                                <div class="flex text-yellow-400 text-xs mt-1">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="<?= $i <= $review->rating ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                    <span class="text-gray-400 ml-2 font-bold uppercase text-[9px] mt-0.5"><?= date('d M Y', strtotime($review->created_at)) ?></span>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/place/<?= $review->place_slug ?>" target="_blank" class="text-primary-600 hover:text-primary-700 text-xs font-bold flex items-center">
                                ดูหน้าร้าน <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 italic text-gray-600 text-sm">
                            "<?= nl2br(htmlspecialchars($review->comment ?: 'ไม่ได้ระบุข้อความ')) ?>"
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>