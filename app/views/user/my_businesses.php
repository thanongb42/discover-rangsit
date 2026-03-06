<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ธุรกิจของฉัน</h1>
        <p class="text-gray-500 text-sm">จัดการข้อมูลธุรกิจและบริการที่คุณเป็นเจ้าของ</p>
    </div>
    <a href="<?= BASE_URL ?>/dashboard/add-place" class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-primary-500/30 flex items-center">
        <i class="fas fa-plus mr-2"></i> เพิ่มธุรกิจใหม่
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php if(empty($data['businesses'])): ?>
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-building text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">คุณยังไม่มีธุรกิจในระบบ</h3>
            <p class="text-gray-400 mb-6">เริ่มต้นสร้างธุรกิจของคุณเพื่อให้คนรังสิตค้นเจอได้ทันที</p>
            <a href="<?= BASE_URL ?>/dashboard/add-place" class="text-primary-600 font-bold hover:underline">คลิกเพื่อเพิ่มธุรกิจแรกของคุณ</a>
        </div>
    <?php else: ?>
        <?php foreach($data['businesses'] as $place): ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl transition-all duration-500 group">
                <div class="relative h-48 overflow-hidden">
                    <img src="<?= BASE_URL ?>/uploads/covers/<?= $place->cover_image ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 right-4">
                        <?php 
                            $status_class = 'bg-gray-100 text-gray-600';
                            if($place->status == 'approved') $status_class = 'bg-green-500 text-white';
                            if($place->status == 'pending') $status_class = 'bg-yellow-400 text-white';
                            if($place->status == 'rejected') $status_class = 'bg-red-500 text-white';
                        ?>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-lg <?= $status_class ?>">
                            <?= $place->status ?>
                        </span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-800 mb-1 truncate"><?= htmlspecialchars($place->name) ?></h3>
                    <p class="text-[10px] font-bold text-primary-500 mb-4 uppercase tracking-widest"><?= $place->category_name ?></p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-50 p-3 rounded-2xl text-center">
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Views</p>
                            <p class="text-sm font-black text-gray-700"><?= number_format($place->views_count) ?></p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl text-center">
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Rating</p>
                            <p class="text-sm font-black text-yellow-500"><?= $place->rating_avg ?> <i class="fas fa-star text-[10px]"></i></p>
                        </div>
                    </div>

                    <div class="mt-auto flex gap-2">
                        <a href="<?= BASE_URL ?>/admin/places/edit/<?= $place->id ?>" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-center py-2.5 rounded-xl text-xs font-bold transition">
                            <i class="fas fa-edit mr-1"></i> แก้ไขข้อมูล
                        </a>
                        <a href="<?= BASE_URL ?>/place/<?= $place->slug ?>" target="_blank" class="w-10 h-10 bg-primary-500 text-white rounded-xl flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>