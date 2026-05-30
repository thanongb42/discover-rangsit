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

                    <div class="mt-auto space-y-2">
                        <a href="<?= BASE_URL ?>/dashboard/analytics/<?= $place->id ?>" class="flex items-center justify-center gap-2 w-full bg-primary-500 hover:bg-primary-600 text-white py-2.5 rounded-xl text-xs font-bold transition shadow-sm shadow-primary-500/30">
                            <i class="fas fa-chart-line"></i> ดู Analytics
                        </a>
                        <div class="flex gap-2">
                            <a href="<?= BASE_URL ?>/dashboard/edit-place/<?= $place->id ?>" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-center py-2.5 rounded-xl text-xs font-bold transition">
                                <i class="fas fa-edit mr-1"></i> แก้ไข
                            </a>
                            <button onclick="openBusinessQR('<?= BASE_URL ?>/place/<?= htmlspecialchars($place->slug, ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($place->name), ENT_QUOTES) ?>')"
                                    class="w-10 h-10 bg-gray-100 hover:bg-[#0088CC] hover:text-white text-gray-600 rounded-xl flex items-center justify-center transition"
                                    title="QR Code">
                                <i class="fas fa-qrcode text-xs"></i>
                            </button>
                            <a href="<?= BASE_URL ?>/place/<?= $place->slug ?>" target="_blank" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl flex items-center justify-center transition">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Business QR Modal -->
<div id="business-qr-modal" class="hidden fixed inset-0 bg-black/60 z-[3500] items-center justify-center p-4" onclick="if(event.target===this)closeBusinessQR()">
    <div class="bg-white rounded-3xl shadow-2xl p-8 text-center max-w-xs w-full">
        <div class="flex items-center justify-between mb-2">
            <h4 class="text-base font-black text-slate-800 flex items-center gap-2">
                <i class="fas fa-qrcode text-[#0088CC]"></i> QR Code ร้านค้า
            </h4>
            <button onclick="closeBusinessQR()" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <p id="biz-qr-name" class="text-sm font-bold text-slate-600 mb-1 truncate"></p>
        <p id="biz-qr-url" class="text-[10px] text-slate-400 mb-4 break-all"></p>
        <div id="biz-qr-canvas" class="flex justify-center mb-4 bg-white p-3 rounded-2xl border border-slate-100 mx-auto" style="width:fit-content"></div>
        <p class="text-[10px] text-slate-400 mb-5">ให้ลูกค้า scan เพื่อดูข้อมูลร้านบนมือถือ</p>
        <div class="grid grid-cols-2 gap-2">
            <button onclick="downloadBusinessQR()" class="flex items-center justify-center gap-2 bg-[#0088CC] hover:bg-[#005A8E] text-white font-bold py-2.5 rounded-2xl text-sm transition">
                <i class="fas fa-download"></i> ดาวน์โหลด
            </button>
            <button onclick="copyBusinessURL()" id="biz-copy-btn" class="flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-2xl text-sm transition">
                <i class="fas fa-copy"></i> คัดลอก URL
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
(function () {
    const modal  = document.getElementById('business-qr-modal');
    const canvas = document.getElementById('biz-qr-canvas');
    let currentURL = '';

    window.openBusinessQR = function (url, name) {
        currentURL = url;
        document.getElementById('biz-qr-name').textContent = name;
        document.getElementById('biz-qr-url').textContent  = url;
        canvas.innerHTML = '';
        QRCode.toCanvas(url, { width: 220, margin: 2, color: { dark: '#1e293b', light: '#ffffff' } }, function (err, c) {
            if (!err) canvas.appendChild(c);
        });
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closeBusinessQR = function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    window.downloadBusinessQR = function () {
        const c = canvas.querySelector('canvas');
        if (!c) return;
        const slug = currentURL.split('/').pop();
        const a    = document.createElement('a');
        a.download = 'qr-' + slug + '.png';
        a.href     = c.toDataURL('image/png');
        a.click();
    };

    window.copyBusinessURL = function () {
        const btn = document.getElementById('biz-copy-btn');
        navigator.clipboard.writeText(currentURL).then(function () {
            btn.innerHTML = '<i class="fas fa-check"></i> คัดลอกแล้ว';
            btn.classList.add('bg-green-100', 'text-green-700');
            btn.classList.remove('bg-slate-100', 'text-slate-700');
            setTimeout(function () {
                btn.innerHTML = '<i class="fas fa-copy"></i> คัดลอก URL';
                btn.classList.remove('bg-green-100', 'text-green-700');
                btn.classList.add('bg-slate-100', 'text-slate-700');
            }, 2000);
        });
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeBusinessQR();
    });
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>