<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<?php
$s = $data['settings'];
$hero   = $s['hero']   ?? [];
$footer = $s['footer'] ?? [];
$email  = $s['email']  ?? [];
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">ตั้งค่าระบบ</h1>
    <p class="text-gray-500 text-sm">จัดการการตั้งค่าทั่วไปของเว็บไซต์ ฐานข้อมูล อีเมล และเนื้อหาหน้าแรก</p>
</div>

<!-- Tab Navigation -->
<div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
    <?php
    $tabs = [
        ['id' => 'db',     'icon' => 'fa-database',    'label' => 'ฐานข้อมูล'],
        ['id' => 'email',  'icon' => 'fa-envelope',    'label' => 'อีเมล'],
        ['id' => 'footer', 'icon' => 'fa-align-center','label' => 'Footer'],
        ['id' => 'hero',   'icon' => 'fa-image',       'label' => 'Hero Section'],
    ];
    foreach ($tabs as $tab): ?>
        <button onclick="switchTab('<?= $tab['id'] ?>')" id="tab-<?= $tab['id'] ?>"
            class="tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-all">
            <i class="fas <?= $tab['icon'] ?> text-xs"></i>
            <?= $tab['label'] ?>
        </button>
    <?php endforeach; ?>
</div>

<!-- ========== TAB: DATABASE ========== -->
<div id="panel-db" class="tab-panel max-w-2xl space-y-6">
    <!-- Backup -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fas fa-download text-green-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">สำรองข้อมูล (Backup)</h2>
                <p class="text-xs text-gray-500">ดาวน์โหลดไฟล์ .sql สำรองข้อมูลทั้งหมด</p>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">ระบบจะสร้างไฟล์ SQL ที่มีโครงสร้างตารางและข้อมูลทั้งหมด สามารถนำไปกู้คืนได้ในอนาคต</p>
            <a href="<?= BASE_URL ?>/api/admin/db-backup" id="backupBtn"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition">
                <i class="fas fa-download"></i> ดาวน์โหลด Backup ตอนนี้
            </a>
        </div>
    </div>

    <!-- Restore -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-red-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fas fa-upload text-red-500"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">คืนค่าฐานข้อมูล (Restore)</h2>
                <p class="text-xs text-gray-500">นำเข้าไฟล์ .sql เพื่อกู้คืนข้อมูล</p>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4 flex gap-3">
                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 shrink-0"></i>
                <p class="text-xs text-red-700 leading-relaxed">
                    <strong>คำเตือน:</strong> การกู้คืนข้อมูลจะ <strong>แทนที่ข้อมูลปัจจุบันทั้งหมด</strong> กรุณา backup ก่อนดำเนินการ
                </p>
            </div>
            <div class="flex items-center gap-3">
                <input type="file" id="sqlFile" accept=".sql" class="block text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <button onclick="restoreDB()" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-upload"></i> กู้คืน
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== TAB: EMAIL ========== -->
<div id="panel-email" class="tab-panel hidden max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-envelope text-blue-500"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">ตั้งค่า SMTP (PHPMailer)</h2>
                <p class="text-xs text-gray-500">กำหนดค่าเซิร์ฟเวอร์อีเมลสำหรับส่งอีเมลในระบบ</p>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">SMTP Host</label>
                    <input type="text" id="email_host" value="<?= htmlspecialchars($email['host'] ?? '') ?>"
                        placeholder="smtp.gmail.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Port</label>
                    <input type="number" id="email_port" value="<?= htmlspecialchars($email['port'] ?? '587') ?>"
                        placeholder="587"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Encryption</label>
                <select id="email_encryption" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 outline-none transition">
                    <option value="tls"  <?= ($email['encryption'] ?? 'tls') === 'tls'  ? 'selected' : '' ?>>TLS (แนะนำ)</option>
                    <option value="ssl"  <?= ($email['encryption'] ?? '') === 'ssl'  ? 'selected' : '' ?>>SSL</option>
                    <option value="none" <?= ($email['encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Username</label>
                    <input type="text" id="email_username" value="<?= htmlspecialchars($email['username'] ?? '') ?>"
                        placeholder="your@email.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="email_password" value="<?= htmlspecialchars($email['password'] ?? '') ?>"
                            placeholder="App Password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="passIcon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">From Name</label>
                    <input type="text" id="email_from_name" value="<?= htmlspecialchars($email['from_name'] ?? 'Discover Rangsit') ?>"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">From Email</label>
                    <input type="email" id="email_from_email" value="<?= htmlspecialchars($email['from_email'] ?? '') ?>"
                        placeholder="noreply@example.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button onclick="saveSection('email')" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-save"></i> บันทึกการตั้งค่าอีเมล
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== TAB: FOOTER ========== -->
<div id="panel-footer" class="tab-panel hidden max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <i class="fas fa-align-center text-slate-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">เนื้อหา Footer</h2>
                <p class="text-xs text-gray-500">แก้ไขข้อความที่แสดงในส่วนท้ายของเว็บไซต์ (รองรับ HTML)</p>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">ชื่อแพลตฟอร์ม</label>
                <input type="text" id="footer_platform_name"
                    value="<?= htmlspecialchars($footer['platform_name'] ?? '') ?>"
                    placeholder="Discover Rangsit - Smart City Platform"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition">
                <p class="text-xs text-gray-400 mt-1">ปล่อยว่างเพื่อใช้ค่าเริ่มต้น</p>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">เครดิตผู้พัฒนา</label>
                <textarea id="footer_dev_credit" rows="3"
                    placeholder="พัฒนาโดย : ..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition resize-none"><?= htmlspecialchars($footer['dev_credit'] ?? '') ?></textarea>
                <p class="text-xs text-gray-400 mt-1">รองรับ HTML เช่น &lt;br&gt; สำหรับขึ้นบรรทัดใหม่ — ปล่อยว่างเพื่อใช้ค่าเริ่มต้น</p>
            </div>
            <div class="flex justify-end pt-2">
                <button onclick="saveSection('footer')" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-save"></i> บันทึก Footer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== TAB: HERO ========== -->
<div id="panel-hero" class="tab-panel hidden max-w-3xl space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                <i class="fas fa-image text-yellow-500"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">Hero Section (หน้าแรก)</h2>
                <p class="text-xs text-gray-500">แก้ไขหัวเรื่องและคำอธิบายบน Hero ของหน้าแรก — ปล่อยว่างเพื่อใช้ค่าเริ่มต้น</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">
                        <span class="inline-flex items-center gap-1"><i class="fas fa-th-large text-xs text-blue-400"></i> Title (ภาษาไทย)</span>
                    </label>
                    <textarea id="hero_title_th" rows="2"
                        placeholder="ค้นพบ <span class=&quot;text-blue-100 italic&quot;>รังสิต</span> ในแบบที่คุณไม่เคยเห็น"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 outline-none transition resize-none font-mono"><?= htmlspecialchars($hero['title_th'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">รองรับ HTML เช่น &lt;span&gt;</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">
                        <span class="inline-flex items-center gap-1"><i class="fas fa-th-large text-xs text-blue-400"></i> Title (English)</span>
                    </label>
                    <textarea id="hero_title_en" rows="2"
                        placeholder="Discover Rangsit Like Never Before"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 outline-none transition resize-none font-mono"><?= htmlspecialchars($hero['title_en'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Description (ภาษาไทย)</label>
                    <textarea id="hero_desc_th" rows="3"
                        placeholder="แพลตฟอร์มรวมข้อมูลร้านค้า..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 outline-none transition resize-none"><?= htmlspecialchars($hero['desc_th'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Description (English)</label>
                    <textarea id="hero_desc_en" rows="3"
                        placeholder="Your guide to shops, restaurants..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 outline-none transition resize-none"><?= htmlspecialchars($hero['desc_en'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Background Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="hero_bg_color"
                            value="<?= htmlspecialchars($hero['bg_color'] ?? '#0088CC') ?>"
                            class="w-12 h-10 border border-gray-200 rounded-xl cursor-pointer">
                        <input type="text" id="hero_bg_color_text"
                            value="<?= htmlspecialchars($hero['bg_color'] ?? '#0088CC') ?>"
                            class="w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-primary-300 outline-none"
                            oninput="document.getElementById('hero_bg_color').value = this.value">
                    </div>
                </div>
                <!-- Live Preview -->
                <div id="heroBgPreview" class="flex-1 h-10 rounded-xl flex items-center justify-center text-white text-xs font-bold transition-all"
                    style="background: <?= htmlspecialchars($hero['bg_color'] ?? '#0088CC') ?>">
                    Preview Background
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button onclick="saveSection('hero')" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-save"></i> บันทึก Hero
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-btn { color: #6b7280; }
    .tab-btn.active { background: white; color: #0088CC; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
</style>

<script>
const BASE_URL = '<?= BASE_URL ?>';

function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('active');
}
switchTab('db');

function togglePass() {
    const inp = document.getElementById('email_password');
    const ico = document.getElementById('passIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'fas fa-eye-slash text-sm';
    } else {
        inp.type = 'password';
        ico.className = 'fas fa-eye text-sm';
    }
}

document.getElementById('hero_bg_color').addEventListener('input', function() {
    document.getElementById('hero_bg_color_text').value = this.value;
    document.getElementById('heroBgPreview').style.background = this.value;
});

async function saveSection(section) {
    let data = {};
    if (section === 'email') {
        data = {
            host:       document.getElementById('email_host').value.trim(),
            port:       document.getElementById('email_port').value.trim(),
            encryption: document.getElementById('email_encryption').value,
            username:   document.getElementById('email_username').value.trim(),
            password:   document.getElementById('email_password').value,
            from_name:  document.getElementById('email_from_name').value.trim(),
            from_email: document.getElementById('email_from_email').value.trim(),
        };
    } else if (section === 'footer') {
        data = {
            platform_name: document.getElementById('footer_platform_name').value,
            dev_credit:    document.getElementById('footer_dev_credit').value,
        };
    } else if (section === 'hero') {
        data = {
            title_th: document.getElementById('hero_title_th').value,
            title_en: document.getElementById('hero_title_en').value,
            desc_th:  document.getElementById('hero_desc_th').value,
            desc_en:  document.getElementById('hero_desc_en').value,
            bg_color: document.getElementById('hero_bg_color').value,
        };
    }

    Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(BASE_URL + '/api/admin/settings/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ section, data })
        });
        const json = await res.json();
        if (json.success) {
            Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', text: json.message, timer: 1800, showConfirmButton: false });
        } else {
            Swal.fire('ข้อผิดพลาด', json.message, 'error');
        }
    } catch (e) {
        Swal.fire('Error', e.message, 'error');
    }
}

async function restoreDB() {
    const fileInput = document.getElementById('sqlFile');
    if (!fileInput.files || !fileInput.files[0]) {
        Swal.fire('แจ้งเตือน', 'กรุณาเลือกไฟล์ .sql ก่อน', 'warning');
        return;
    }
    const result = await Swal.fire({
        icon: 'warning',
        title: 'ยืนยันการกู้คืนข้อมูล?',
        html: 'ข้อมูลปัจจุบัน<strong>จะถูกแทนที่ทั้งหมด</strong><br>คุณแน่ใจหรือไม่?',
        showCancelButton: true,
        confirmButtonText: 'ใช่ กู้คืนเลย',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#ef4444',
    });
    if (!result.isConfirmed) return;

    Swal.fire({ title: 'กำลังกู้คืน...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const formData = new FormData();
    formData.append('sql_file', fileInput.files[0]);

    try {
        const res = await fetch(BASE_URL + '/api/admin/db-restore', {
            method: 'POST',
            body: formData
        });
        const text = await res.text();
        let json;
        try { json = JSON.parse(text); } catch(e) {
            Swal.fire('Error', 'Server error: ' + text.substring(0, 200), 'error');
            return;
        }
        if (json.success) {
            Swal.fire({ icon: 'success', title: 'กู้คืนสำเร็จ', text: json.message, confirmButtonColor: '#0088CC' });
        } else {
            Swal.fire('ข้อผิดพลาด', json.message, 'error');
        }
    } catch(e) {
        Swal.fire('Error', e.message, 'error');
    }
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
