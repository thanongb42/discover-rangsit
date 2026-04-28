<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลสถานที่</h1>
        <p class="text-gray-500 text-sm">ปรับปรุงรายละเอียด ตำแหน่งพิกัด และจัดการคลังภาพ</p>
    </div>
    <?php if($data['current_page'] === 'my_businesses'): ?>
    <a href="<?= BASE_URL ?>/my-businesses" class="text-gray-500 hover:text-gray-800 font-bold flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i> กลับธุรกิจของฉัน
    </a>
    <?php else: ?>
    <a href="<?= BASE_URL ?>/admin/places" class="text-gray-500 hover:text-gray-800 font-bold flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i> กลับหน้ารายการ
    </a>
    <?php endif; ?>
</div>

<?php
$deliveryUrl = $data['current_page'] === 'my_businesses'
    ? BASE_URL . '/dashboard/delivery/' . $data['place']->id
    : BASE_URL . '/admin/places/delivery/' . $data['place']->id;
?>

<!-- Delivery Feature Banner -->
<div class="mb-8 rounded-[2rem] overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #06C755 0%, #05a847 50%, #039939 100%);">
    <div class="p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center gap-6">

        <!-- Icon -->
        <div class="flex-shrink-0 w-20 h-20 bg-white/20 rounded-[1.5rem] flex items-center justify-center shadow-inner">
            <i class="fas fa-motorcycle text-white text-4xl"></i>
        </div>

        <!-- Text -->
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-white/25 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">ฟีเจอร์ใหม่</span>
            </div>
            <h2 class="text-white font-black text-xl md:text-2xl mb-1">เชื่อมร้านกับ Delivery แพลตฟอร์ม</h2>
            <p class="text-green-100 text-sm leading-relaxed mb-4">
                ให้ลูกค้าสั่งอาหาร/สินค้าจากร้านคุณผ่าน LINE MAN, GrabFood, foodpanda และอื่นๆ ได้ในคลิกเดียว — พร้อม <strong class="text-white">QR Code</strong> ให้สแกนหน้าร้าน
            </p>

            <!-- Platform Pills -->
            <div class="flex flex-wrap gap-2 mb-5">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fa-brands fa-line"></i> LINE MAN</span>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fas fa-utensils"></i> GrabFood</span>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fas fa-paw"></i> foodpanda</span>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fas fa-shopping-bag"></i> ShopeeFood</span>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fas fa-hat-wizard"></i> Robinhood</span>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5"><i class="fas fa-truck"></i> Lalamove</span>
            </div>

            <!-- Steps -->
            <div class="flex flex-wrap gap-3 mb-6">
                <div class="flex items-center gap-2 bg-white/15 rounded-2xl px-4 py-2.5">
                    <span class="w-6 h-6 bg-white text-green-600 rounded-full flex items-center justify-center font-black text-xs flex-shrink-0">1</span>
                    <span class="text-white text-xs font-semibold">กดปุ่มด้านล่าง</span>
                </div>
                <div class="flex items-center gap-2 text-green-200 flex-shrink-0 self-center">
                    <i class="fas fa-arrow-right text-xs"></i>
                </div>
                <div class="flex items-center gap-2 bg-white/15 rounded-2xl px-4 py-2.5">
                    <span class="w-6 h-6 bg-white text-green-600 rounded-full flex items-center justify-center font-black text-xs flex-shrink-0">2</span>
                    <span class="text-white text-xs font-semibold">วาง URL หน้าร้านของแต่ละแพลตฟอร์ม</span>
                </div>
                <div class="flex items-center gap-2 text-green-200 flex-shrink-0 self-center">
                    <i class="fas fa-arrow-right text-xs"></i>
                </div>
                <div class="flex items-center gap-2 bg-white/15 rounded-2xl px-4 py-2.5">
                    <span class="w-6 h-6 bg-white text-green-600 rounded-full flex items-center justify-center font-black text-xs flex-shrink-0">3</span>
                    <span class="text-white text-xs font-semibold">ปุ่มปรากฏบนหน้าร้านทันที!</span>
                </div>
            </div>

            <a href="<?= $deliveryUrl ?>" class="inline-flex items-center gap-3 bg-white text-green-600 hover:bg-green-50 font-black px-8 py-3.5 rounded-2xl shadow-lg transition-all hover:-translate-y-0.5 hover:shadow-xl text-sm">
                <i class="fas fa-motorcycle text-lg"></i>
                ตั้งค่า Delivery Links ของร้านฉัน
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Decorative Icon -->
        <div class="hidden lg:flex flex-shrink-0 w-32 h-32 items-center justify-center opacity-20">
            <i class="fas fa-qrcode text-white" style="font-size:100px;"></i>
        </div>

    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Basic Info -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Data Entry Guideline Alert -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-2xl shadow-sm">
            <div class="flex gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div class="text-sm text-blue-800 leading-relaxed">
                    <p class="font-bold mb-1">แนวทางความปลอดภัยของข้อมูล:</p>
                    <p>1. ข้อมูลที่นำเข้าควรเป็นข้อมูลสาธารณะเพื่อการประชาสัมพันธ์เมือง</p>
                    <p>2. รูปภาพที่มีข้อมูลส่วนบุคคลจะต้องทำการอำพรางหรือได้รับอนุญาตจากผู้ที่อยู่ในเหตุการณ์ก่อนทุกครั้ง</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">รายละเอียดธุรกิจ</h3>
            </div>
            <div class="p-8">
                <form id="placeEditForm" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $data['place']->id ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อสถานที่</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['place']->name) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">หมวดหมู่</label>
                            <div class="flex gap-2">
                                <select name="category_id" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required>
                                    <?php foreach($data['categories'] as $cat): ?>
                                        <option value="<?= $cat->id ?>" <?= $cat->id == $data['place']->category_id ? 'selected' : '' ?>><?= htmlspecialchars($cat->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if($data['current_page'] !== 'my_businesses'): ?>
                                <a href="<?= BASE_URL ?>/admin/categories" target="_blank" class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-primary-600 hover:border-primary-200 transition shadow-sm" title="จัดการหมวดหมู่">
                                    <i class="fas fa-list-check"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">คำอธิบาย</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-primary-500 focus:border-primary-500 transition" required><?= htmlspecialchars($data['place']->description) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">สถานะการแสดงผล</label>
                        <?php if($data['current_page'] === 'my_businesses'): ?>
                        <?php
                            $status = $data['place']->status;
                            $isApproved = $status === 'approved';
                            $isPending  = $status === 'pending';
                            $isRejected = $status === 'rejected';
                        ?>
                        <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                            <div class="flex items-center gap-0">
                                <!-- Step 1: ส่งข้อมูลแล้ว -->
                                <div class="flex flex-col items-center">
                                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-paper-plane text-white text-sm"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-blue-600 mt-2 text-center whitespace-nowrap">ส่งข้อมูลแล้ว</span>
                                </div>
                                <!-- Line -->
                                <div class="flex-1 h-1 mx-2 rounded-full <?= !$isPending || $isApproved || $isRejected ? 'bg-blue-300' : 'bg-blue-200' ?>"></div>
                                <!-- Step 2: รอตรวจสอบ -->
                                <div class="flex flex-col items-center">
                                    <?php if($isPending): ?>
                                        <div class="w-9 h-9 rounded-full bg-yellow-400 flex items-center justify-center shadow-sm animate-pulse">
                                            <i class="fas fa-hourglass-half text-white text-sm"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-yellow-500 mt-2 text-center whitespace-nowrap">รอการอนุมัติ</span>
                                    <?php else: ?>
                                        <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-blue-600 mt-2 text-center whitespace-nowrap">ตรวจสอบแล้ว</span>
                                    <?php endif; ?>
                                </div>
                                <!-- Line -->
                                <div class="flex-1 h-1 mx-2 rounded-full <?= $isApproved ? 'bg-green-300' : ($isRejected ? 'bg-red-200' : 'bg-gray-200') ?>"></div>
                                <!-- Step 3: ผลการอนุมัติ -->
                                <div class="flex flex-col items-center">
                                    <?php if($isApproved): ?>
                                        <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-check-circle text-white text-sm"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-green-600 mt-2 text-center whitespace-nowrap">อนุมัติแล้ว</span>
                                    <?php elseif($isRejected): ?>
                                        <div class="w-9 h-9 rounded-full bg-red-500 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-times-circle text-white text-sm"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-red-500 mt-2 text-center whitespace-nowrap">ถูกปฏิเสธ</span>
                                    <?php else: ?>
                                        <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-flag text-gray-400 text-sm"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-400 mt-2 text-center whitespace-nowrap">อนุมัติ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Status message -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <?php if($isPending): ?>
                                    <p class="text-xs text-yellow-700 bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-2.5">
                                        <i class="fas fa-clock mr-1.5"></i> ธุรกิจของคุณอยู่ระหว่างการตรวจสอบ รอเจ้าหน้าที่อนุมัติภายใน 1–3 วันทำการ
                                    </p>
                                <?php elseif($isApproved): ?>
                                    <p class="text-xs text-green-700 bg-green-50 border border-green-100 rounded-xl px-4 py-2.5">
                                        <i class="fas fa-map-marker-alt mr-1.5"></i> ธุรกิจของคุณได้รับการอนุมัติและแสดงบนแผนที่เมืองรังสิตแล้ว
                                    </p>
                                <?php elseif($isRejected): ?>
                                    <p class="text-xs text-red-700 bg-red-50 border border-red-100 rounded-xl px-4 py-2.5">
                                        <i class="fas fa-exclamation-circle mr-1.5"></i> ธุรกิจของคุณถูกปฏิเสธ กรุณาแก้ไขข้อมูลและติดต่อเจ้าหน้าที่
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="approved" <?= $data['place']->status == 'approved' ? 'checked' : '' ?> class="mr-2 text-green-500 focus:ring-green-500">
                                <span class="text-sm font-medium text-green-600">แสดงผล (Approved)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="pending" <?= $data['place']->status == 'pending' ? 'checked' : '' ?> class="mr-2 text-yellow-500 focus:ring-yellow-500">
                                <span class="text-sm font-medium text-yellow-600">รออนุมัติ (Pending)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="rejected" <?= $data['place']->status == 'rejected' ? 'checked' : '' ?> class="mr-2 text-red-500 focus:ring-red-500">
                                <span class="text-sm font-medium text-red-600">ระงับ (Rejected)</span>
                            </label>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i> ตำแหน่งและที่อยู่
                        </h4>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-400 mb-1">ที่อยู่</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($data['place']->address) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">ละติจูด</label>
                                <input type="number" step="any" name="latitude" id="latInput" value="<?= $data['place']->latitude ?>" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">ลองจิจูด</label>
                                <input type="number" step="any" name="longitude" id="lngInput" value="<?= $data['place']->longitude ?>" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                        <div id="editMap" class="w-full h-64 rounded-2xl border border-gray-200 overflow-hidden mb-4"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($data['place']->phone) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">เว็บไซต์</label>
                            <input type="url" name="website" value="<?= htmlspecialchars($data['place']->website) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-share-nodes text-primary-500 mr-2"></i> ช่องทางการติดต่อโซเชียลมีเดีย
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">LINE ID</label>
                                <input type="text" name="line" value="<?= htmlspecialchars($data['place']->line) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="@lineid">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Facebook Fanpage (URL)</label>
                                <input type="url" name="facebook" value="<?= htmlspecialchars($data['place']->facebook) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://facebook.com/page">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Instagram (URL)</label>
                                <input type="url" name="instagram" value="<?= htmlspecialchars($data['place']->instagram) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://instagram.com/user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">TikTok (URL)</label>
                                <input type="url" name="tiktok" value="<?= htmlspecialchars($data['place']->tiktok) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://tiktok.com/@user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">X (Twitter) (URL)</label>
                                <input type="url" name="x" value="<?= htmlspecialchars($data['place']->x) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://x.com/user">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">YouTube (URL)</label>
                                <input type="url" name="youtube" value="<?= htmlspecialchars($data['place']->youtube) ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm" placeholder="https://youtube.com/channel">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-10 py-4 rounded-2xl font-black transition shadow-lg shadow-primary-500/30">
                            บันทึกข้อมูลทั้งหมด
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Images & Gallery -->
    <div class="space-y-8">
        <input type="hidden" id="coverBase64">
        <input type="hidden" id="qrBase64">

        <!-- Cover Image -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800">รูปภาพหน้าปก</h3>
            </div>
            <div class="p-6 text-center">
                <div class="relative mb-4 h-48 rounded-2xl overflow-hidden border border-gray-100 shadow-inner cursor-pointer" onclick="document.getElementById('coverInput').click()">
                    <img id="coverPreview" src="<?= BASE_URL ?>/../uploads/covers/<?= $data['place']->cover_image ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur rounded-full p-3">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <input type="file" id="coverInput" accept="image/*" onchange="previewImage(this, 'coverPreview')" style="position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;">
                <div class="flex flex-col gap-2">
                    <button type="button" onclick="document.getElementById('coverInput').click()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-image"></i> เลือกรูปภาพหน้าปก
                    </button>
                    <button type="button" onclick="uploadCover()" id="btnUpdateCover" class="hidden w-full bg-primary-500 hover:bg-primary-600 text-white py-2.5 rounded-xl font-bold text-sm transition shadow-md shadow-primary-500/20">
                        <i class="fas fa-upload mr-2"></i> อัปเดตรูปหน้าปก
                    </button>
                    <p class="text-[10px] text-gray-400">แนะนำ 1200x800px รองรับ JPG, PNG, HEIC</p>
                </div>
            </div>
        </div>

        <!-- LINE QR Code -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800">LINE QR Code</h3>
            </div>
            <div class="p-6 text-center">
                <div class="relative mb-4 aspect-square max-w-[200px] mx-auto rounded-2xl overflow-hidden border border-gray-100 shadow-inner bg-gray-50 flex items-center justify-center cursor-pointer" onclick="document.getElementById('lineQrInput').click()">
                    <img id="lineQrPreview" src="<?= $data['place']->line_qr ? BASE_URL . '/../uploads/gallery/' . $data['place']->line_qr : 'https://placehold.co/200x200/f8fafc/cbd5e1?text=No+QR' ?>" class="w-full h-full object-contain">
                    <div class="absolute inset-0 bg-black/20 flex items-end justify-center pb-3">
                        <div class="bg-white/80 backdrop-blur rounded-full px-3 py-1 text-xs font-bold text-gray-700">
                            <i class="fas fa-qrcode mr-1"></i> เปลี่ยน QR
                        </div>
                    </div>
                </div>
                <input type="file" name="line_qr" id="lineQrInput" accept="image/*" onchange="previewImage(this, 'lineQrPreview', 'btnUpdateLineQr')" style="position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;">
                <div class="flex flex-col gap-2">
                    <button type="button" onclick="document.getElementById('lineQrInput').click()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-qrcode"></i> เลือกรูป QR Code
                    </button>
                    <button type="button" onclick="uploadLineQr()" id="btnUpdateLineQr" class="hidden w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-bold text-sm transition shadow-md shadow-green-500/20">
                        <i class="fas fa-upload mr-2"></i> อัปเดต LINE QR
                    </button>
                    <p class="text-[10px] text-gray-400">อัปโหลดรูปภาพ QR Code สำหรับเพิ่มเพื่อน LINE</p>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">คลังภาพขยาย</h3>
                <button onclick="document.getElementById('galleryInput').click()" class="text-primary-600 hover:text-primary-700 font-bold text-xs flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> เพิ่มรูป
                </button>
            </div>
            <div class="p-6">
                <input type="file" id="galleryInput" multiple accept="image/*,image/heic,image/heif" onchange="uploadToGallery(this)" style="position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;">
                <div class="grid grid-cols-3 gap-3" id="galleryGrid">
                    <?php foreach($data['gallery'] as $img): ?>
                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100" id="gallery-item-<?= $img->id ?>">
                            <img src="<?= BASE_URL ?>/../uploads/gallery/<?= $img->image_path ?>" class="w-full h-full object-cover">
                            <button onclick="deleteGallery(<?= $img->id ?>)" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(empty($data['gallery'])): ?>
                    <p id="emptyGalleryText" class="text-center text-xs text-gray-400 py-4 italic">ยังไม่มีรูปภาพเพิ่มเติม</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet & Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        
        document.getElementById('placeEditForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveAll();
        });
    });

    function initMap() {
        const lat = parseFloat(document.getElementById('latInput').value);
        const lng = parseFloat(document.getElementById('lngInput').value);
        
        map = L.map('editMap').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OSM' }).addTo(map);
        
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        
        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            document.getElementById('latInput').value = pos.lat.toFixed(6);
            document.getElementById('lngInput').value = pos.lng.toFixed(6);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latInput').value = e.latlng.lat.toFixed(6);
            document.getElementById('lngInput').value = e.latlng.lng.toFixed(6);
        });
    }

    let coverBlob = null;
    let qrBlob    = null;

    function previewImage(input, previewId, btnId = 'btnUpdateCover') {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const ext  = file.name.split('.').pop().toLowerCase();

        if (ext === 'eps') {
            Swal.fire('ไม่รองรับ', 'ไฟล์ .eps ไม่สามารถประมวลผลได้บนเบราว์เซอร์ กรุณาใช้ .jpg, .png, .heic หรือ .gif', 'error');
            input.value = '';
            return;
        }

        const fileMB  = file.size / (1024 * 1024);
        // สร้าง objectUrl ทันทีในขณะที่ยังอยู่ใน user-gesture context (onchange)
        // iOS Safari บล็อก createObjectURL ถ้าเรียกใน async Promise callback
        const objectUrl = URL.createObjectURL(file);

        function doProcess() {
            Swal.fire({ title: 'กำลังประมวลผลรูปภาพ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const img = new Image();

            img.onerror = function() {
                URL.revokeObjectURL(objectUrl);
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดรูปภาพได้ อาจเป็นรูปแบบที่เบราว์เซอร์ไม่รองรับในอุปกรณ์นี้', 'error');
                input.value = '';
            };

            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width  = img.width;
                let height = img.height;
                const max_size = previewId === 'coverPreview' ? 1200 : 800;

                if (width > height) {
                    if (width > max_size) { height *= max_size / width; width = max_size; }
                } else {
                    if (height > max_size) { width *= max_size / height; height = max_size; }
                }

                canvas.width  = Math.round(width);
                canvas.height = Math.round(height);
                canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
                URL.revokeObjectURL(objectUrl);

                const MAX_BYTES = 800 * 1024;
                let quality = 0.82;

                function tryBlob() {
                    canvas.toBlob(function(blob) {
                        if (blob.size > MAX_BYTES && quality > 0.3) {
                            quality = Math.max(quality - 0.1, 0.3);
                            tryBlob();
                            return;
                        }

                        document.getElementById(previewId).src = URL.createObjectURL(blob);

                        if (previewId === 'coverPreview') { coverBlob = blob; }
                        else                              { qrBlob    = blob; }

                        const btn = document.getElementById(btnId);
                        btn.classList.remove('hidden');
                        btn.classList.add('animate-bounce');
                        setTimeout(() => btn.classList.remove('animate-bounce'), 1000);

                        const kb = Math.round(blob.size / 1024);
                        Swal.fire({
                            icon: 'success', title: 'เตรียมรูปภาพสำเร็จ',
                            text: `ขนาดไฟล์: ${kb} KB · กรุณากดปุ่ม "อัปเดต" เพื่อบันทึกลงระบบ`,
                            timer: 3000, showConfirmButton: false, toast: true, position: 'top-end'
                        });
                    }, 'image/jpeg', quality);
                }
                tryBlob();
            };

            img.src = objectUrl;
        }

        if (fileMB > 2) {
            Swal.fire({
                icon: 'warning',
                title: 'ขนาดไฟล์ใหญ่เกินไป',
                text: `ไฟล์มีขนาด ${fileMB.toFixed(1)} MB ระบบจะทำการลดขนาดเพื่อให้สามารถอัปโหลดได้`,
                showCancelButton: true,
                confirmButtonText: 'OK ดำเนินการต่อ',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#2795F5',
            }).then(result => {
                if (result.isConfirmed) doProcess();
                else { URL.revokeObjectURL(objectUrl); input.value = ''; }
            });
        } else {
            doProcess();
        }
    }

    async function uploadLineQr() {
        if (!qrBlob) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกรูปภาพ QR Code ใหม่ก่อน', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('id', <?= $data['place']->id ?>);
        formData.append('line_qr', qrBlob, 'lineqr.jpg');

        try {
            Swal.fire({ title: 'กำลังอัปโหลด QR...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            const response = await fetch('<?= BASE_URL ?>/api/admin/places/lineqr/update', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('เซิร์ฟเวอร์ตอบกลับ HTTP ' + response.status + ' — ไฟล์อาจมีขนาดใหญ่เกินไป');
            }

            const res = await response.json();
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'อัปเดต LINE QR เรียบร้อยแล้ว', timer: 1500, showConfirmButton: false });
                document.getElementById('btnUpdateLineQr').classList.add('hidden');
                qrBlob = null;
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }

    async function uploadCover() {
        if (!coverBlob) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกรูปภาพหน้าปกใหม่ก่อน', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('id', <?= $data['place']->id ?>);
        formData.append('cover_image', coverBlob, 'cover.jpg');

        try {
            Swal.fire({
                title: 'กำลังอัปโหลด...',
                html: 'กรุณารอสักครู่ ระบบกำลังปรับจูนรูปภาพให้เหมาะสม',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const response = await fetch('<?= BASE_URL ?>/api/admin/places/cover/update', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('เซิร์ฟเวอร์ตอบกลับ HTTP ' + response.status + ' — ไฟล์อาจมีขนาดใหญ่เกินไป');
            }

            const res = await response.json();
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'อัปเดตรูปหน้าปกเรียบร้อยแล้ว', timer: 1500, showConfirmButton: false });
                document.getElementById('btnUpdateCover').classList.add('hidden');
                coverBlob = null;
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', error.message || 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
        }
    }

    async function saveAll() {
        const formData = new FormData(document.getElementById('placeEditForm'));
        const coverInput = document.getElementById('coverInput');
        if(coverInput.files[0]) {
            formData.append('cover_image', coverInput.files[0]);
        }

        const response = await fetch('<?= BASE_URL ?>/api/admin/places/update', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();

        if (res.success) {
            Swal.fire({ icon: 'success', title: 'อัปเดตเรียบร้อย', text: res.message, timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire('ข้อผิดพลาด', res.message, 'error');
        }
    }

    // Convert any image (incl. HEIC) to a JPEG Blob via canvas — required for iOS Safari
    function fileToJpegBlob(file) {
        return new Promise((resolve, reject) => {
            const objectUrl = URL.createObjectURL(file);
            const img = new Image();
            img.onerror = () => { URL.revokeObjectURL(objectUrl); reject(new Error('ไม่สามารถโหลดรูปภาพได้')); };
            img.onload = () => {
                URL.revokeObjectURL(objectUrl);
                const canvas = document.createElement('canvas');
                let w = img.width, h = img.height;
                const MAX = 1600;
                if (w > h) { if (w > MAX) { h = Math.round(h * MAX / w); w = MAX; } }
                else        { if (h > MAX) { w = Math.round(w * MAX / h); h = MAX; } }
                canvas.width = w; canvas.height = h;
                canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                canvas.toBlob(blob => {
                    if (blob) resolve(blob);
                    else reject(new Error('canvas.toBlob failed'));
                }, 'image/jpeg', 0.82);
            };
            img.src = objectUrl;
        });
    }

    async function uploadToGallery(input) {
        if (!input.files.length) return;

        const files = Array.from(input.files);
        let successCount = 0;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            Swal.fire({ title: `กำลังประมวลผล ${i + 1}/${files.length}...`, allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                // Convert to JPEG blob first (fixes iOS Safari HEIC upload error)
                const jpegBlob = await fileToJpegBlob(file);

                Swal.update({ title: `กำลังอัปโหลด ${i + 1}/${files.length}...` });

                const formData = new FormData();
                formData.append('place_id', <?= $data['place']->id ?>);
                formData.append('image', jpegBlob, 'photo.jpg');

                const response = await fetch('<?= BASE_URL ?>/api/admin/places/gallery/upload', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error('Server error HTTP ' + response.status);

                const res = await response.json();

                if (res.success) {
                    successCount++;
                    const emptyText = document.getElementById('emptyGalleryText');
                    if (emptyText) emptyText.remove();

                    const grid = document.getElementById('galleryGrid');
                    const div  = document.createElement('div');
                    div.id        = 'gallery-item-' + res.id;
                    div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-100';
                    div.innerHTML = `
                        <img src="<?= BASE_URL ?>/../uploads/gallery/${res.filename}" class="w-full h-full object-cover">
                        <button onclick="deleteGallery(${res.id})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    `;
                    grid.prepend(div);
                } else {
                    Swal.fire('ข้อผิดพลาด', res.message || 'อัปโหลดไม่สำเร็จ', 'error');
                    break;
                }
            } catch (err) {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถอัปโหลดได้: ' + err.message, 'error');
                break;
            }
        }

        input.value = '';

        if (successCount > 0) {
            Swal.fire({ icon: 'success', title: `อัปโหลดสำเร็จ ${successCount} รูป`, timer: 1500, showConfirmButton: false });
        }
    }

    async function deleteGallery(id) {
        if(!confirm('ลบรูปภาพนี้?')) return;
        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch('<?= BASE_URL ?>/api/admin/places/gallery/delete', {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        if(res.success) {
            const item = document.getElementById(`gallery-item-${id}`);
            item.classList.add('opacity-0', 'scale-50', 'transition-all', 'duration-300');
            setTimeout(() => item.remove(), 300);
        }
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>