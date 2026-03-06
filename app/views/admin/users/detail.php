<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">รายละเอียดผู้ใช้งาน</h1>
        <p class="text-gray-500 text-sm">ข้อมูลส่วนตัว บทบาทหน้าที่ และประวัติการใช้งานระบบ</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/users" class="text-gray-500 hover:text-gray-800 font-bold flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i> กลับหน้ารายการ
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Profile Overview -->
    <div class="space-y-8">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden text-center p-8">
            <div class="relative inline-block mb-6">
                <div class="w-32 h-32 rounded-full border-4 border-white shadow-xl overflow-hidden bg-slate-50 mx-auto">
                    <img src="<?= $data['user']->profile_image ? BASE_URL . '/../' . $data['user']->profile_image : 'https://ui-avatars.com/api/?name=' . $data['user']->username . '&size=128' ?>" class="w-full h-full object-cover">
                </div>
                <div class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-green-500 border-4 border-white"></div>
            </div>
            
            <h2 class="text-xl font-black text-gray-800"><?= htmlspecialchars($data['user']->prefix_name . $data['user']->first_name . ' ' . $data['user']->last_name) ?></h2>
            <p class="text-gray-400 text-sm mb-4">@<?= htmlspecialchars($data['user']->username) ?></p>
            
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-blue-100"><?= $data['user']->role ?></span>
                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-100"><?= $data['user']->status ?></span>
            </div>

            <div class="border-t border-gray-50 pt-6 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">ธุรกิจที่เป็นเจ้าของ</p>
                    <p class="text-lg font-black text-gray-800">0</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">รีวิวที่เขียน</p>
                    <p class="text-lg font-black text-gray-800">0</p>
                </div>
            </div>
        </div>

        <!-- Contact Quick Info -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden p-8">
            <h3 class="text-sm font-bold text-gray-800 mb-6 uppercase tracking-wider border-b border-gray-50 pb-4">ช่องทางติดต่อ</h3>
            <ul class="space-y-4">
                <li class="flex items-center gap-4 group">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">อีเมล</p>
                        <p class="text-sm font-bold text-gray-700 truncate"><?= htmlspecialchars($data['user']->email) ?></p>
                    </div>
                </li>
                <li class="flex items-center gap-4 group">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-green-50 group-hover:text-green-600 transition">
                        <i class="fas fa-phone text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">เบอร์โทรศัพท์</p>
                        <p class="text-sm font-bold text-gray-700"><?= htmlspecialchars($data['user']->phone ?: '-') ?></p>
                    </div>
                </li>
                <?php if($data['user']->line_user_id): ?>
                <li class="flex items-center gap-4 group">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 transition">
                        <i class="fab fa-line text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">LINE Connected</p>
                        <p class="text-sm font-bold text-gray-700"><?= htmlspecialchars($data['user']->line_display_name) ?></p>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Right: Detailed Information & Activity -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">ข้อมูลหน่วยงานและการทำงาน</h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">สังกัด / กอง / สำนัก</label>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-gray-700 font-bold">
                        <?= htmlspecialchars($data['user']->department_name ?: 'ไม่ได้ระบุสังกัด') ?>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ตำแหน่ง</label>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-gray-700 font-bold">
                        <?= htmlspecialchars($data['user']->position ?: 'ไม่ได้ระบุตำแหน่ง') ?>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">สิทธิ์การใช้งานระบบ</label>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-gray-700 font-bold flex items-center">
                        <i class="fas fa-key text-yellow-500 mr-2"></i> <?= ucfirst($data['user']->role) ?>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">สถานะปัจจุบัน</label>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-gray-700 font-bold flex items-center">
                        <i class="fas fa-circle text-green-500 mr-2 text-[8px]"></i> ใช้งานปกติ (Active)
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">ประวัติการเข้าใช้งาน</h3>
            </div>
            <div class="p-8">
                <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary-500 shadow-sm">
                        <i class="fas fa-sign-in-alt text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">เข้าสู่ระบบล่าสุด</p>
                        <p class="text-xl font-black text-gray-800"><?= $data['user']->last_login ? date('d M Y | H:i', strtotime($data['user']->last_login)) : 'ยังไม่มีประวัติการเข้าใช้' ?></p>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase px-4">
                    <span>วันที่สมัครสมาชิก: <?= date('d/m/Y H:i', strtotime($data['user']->created_at)) ?></span>
                    <span>แก้ไขล่าสุดเมื่อ: <?= date('d/m/Y H:i', strtotime($data['user']->updated_at)) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>