<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<style>
    .pr-poster {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #0f766e 100%);
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 30px 80px rgba(30,58,138,.35);
    }
    .pr-bg-circle {
        position: absolute;
        border-radius: 50%;
        opacity: .07;
        background: white;
        pointer-events: none;
    }
    .pr-bg-circle.c1 { width: 500px; height: 500px; top: -200px; right: -150px; }
    .pr-bg-circle.c2 { width: 300px; height: 300px; bottom: -100px; left: -80px; }
    .pr-bg-circle.c3 { width: 200px; height: 200px; top: 180px; left: 80px; }

    .guide-step-connector {
        position: absolute;
        left: 23px;
        top: 56px;
        bottom: -8px;
        width: 2px;
        background: linear-gradient(180deg, #1e3a8a33, #0f766e22);
    }
    @media print {
        .no-print { display: none !important; }
        header, footer, nav { display: none !important; }
        .pr-poster { box-shadow: none !important; }
    }
</style>

<main class="bg-slate-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-4xl">

        <!-- ══════════════════ POSTER ══════════════════ -->
        <div class="pr-poster mb-10">
            <div class="pr-bg-circle c1"></div>
            <div class="pr-bg-circle c2"></div>
            <div class="pr-bg-circle c3"></div>

            <div class="relative z-10 p-6 sm:p-10 md:p-14">
                <!-- Brand + FREE badge row -->
                <div class="flex items-center justify-between gap-3 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl overflow-hidden shrink-0">
                            <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit" class="w-10 h-10 sm:w-14 sm:h-14 object-contain">
                        </div>
                        <div class="text-white">
                            <div class="text-base sm:text-xl font-extrabold leading-tight">Discover Rangsit</div>
                            <div class="text-xs sm:text-sm opacity-70 font-normal">แพลตฟอร์มค้นพบทุกสิ่งในเมืองรังสิต</div>
                        </div>
                    </div>
                    <div class="bg-red-500 text-white font-extrabold text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full shadow-lg shadow-red-500/40 rotate-3 shrink-0">
                        ฟรี! 🎉
                    </div>
                </div>

                <span class="inline-block bg-yellow-400 text-blue-900 text-xs sm:text-sm font-extrabold px-4 py-1.5 rounded-full mb-4 tracking-wide">
                    📢 ประชาสัมพันธ์ร้านของคุณ
                </span>

                <h1 class="text-white text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
                    ฝากร้านดัง<br>
                    <span class="text-yellow-300">รังสิต</span> ของคุณ<br>
                    ให้คนรู้จัก!
                </h1>

                <p class="text-white/80 text-base sm:text-lg leading-relaxed mb-8 max-w-xl">
                    เปิดร้านอยู่ที่รังสิต? สมัครฟรี! โพสต์ข้อมูลร้านคุณบน Discover Rangsit
                    ให้ลูกค้าค้นหาเจอง่ายขึ้น ทุกวัน ทุกที่
                </p>

                <!-- Features -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <?php $features = [
                        ['📍','แสดงบนแผนที่'],
                        ['⭐','รับรีวิวจากลูกค้า'],
                        ['📱','ลิงก์โซเชียล'],
                        ['💚','เชื่อมต่อ LINE'],
                        ['🆓','สมัครฟรี'],
                        ['🗺️','City Map'],
                    ]; foreach ($features as $f): ?>
                    <span class="bg-white/10 backdrop-blur border border-white/20 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl flex items-center gap-1.5">
                        <span><?= $f[0] ?></span><?= $f[1] ?>
                    </span>
                    <?php endforeach; ?>
                </div>

                <!-- CTA -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <a href="<?= BASE_URL ?>/register" class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-extrabold text-base sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-2xl shadow-xl shadow-yellow-400/30 transition-all hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                        🚀 สมัครฟรี เริ่มโพสต์เลย
                    </a>
                    <div class="text-white/70 font-semibold text-sm">
                        🌐 <strong class="text-white">discover.rangsitcity.go.th</strong>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-8 pt-6 border-t border-white/15 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php $stats = [
                        ['ฟรี','ไม่มีค่าใช้จ่าย'],
                        ['24/7','เข้าถึงได้ตลอดเวลา'],
                        ['📲','รองรับมือถือ'],
                        ['🗺️','แสดงบน City Map'],
                    ]; foreach ($stats as $s): ?>
                    <div>
                        <div class="text-yellow-300 text-2xl sm:text-3xl font-extrabold leading-none"><?= $s[0] ?></div>
                        <div class="text-white/60 text-xs sm:text-sm mt-1"><?= $s[1] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ══════════════════ GUIDE ══════════════════ -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 overflow-hidden">

            <div class="bg-gradient-to-r from-[#1e3a8a] to-[#1e40af] px-6 sm:px-10 py-6 sm:py-8">
                <h2 class="text-white text-xl sm:text-2xl font-extrabold mb-1">📋 คู่มือการโพสต์ร้านบน Discover Rangsit</h2>
                <p class="text-white/70 text-sm">ทำตามขั้นตอนง่ายๆ ภายใน 5 นาที — ฟรี ไม่มีค่าใช้จ่าย</p>
            </div>

            <div class="p-5 sm:p-8 md:p-12">
                <?php
                $steps = [
                    [
                        'title' => 'เข้าเว็บไซต์ Discover Rangsit',
                        'tag'   => 'เริ่มต้น',
                        'desc'  => 'เปิดเบราว์เซอร์แล้วไปที่ URL ด้านล่าง',
                        'items' => [
                            'พิมพ์ <strong>discover.rangsitcity.go.th</strong> ในแถบ URL',
                            'กดปุ่ม <strong>"เข้าสู่ระบบ"</strong> ที่มุมขวาบนของหน้าเว็บ',
                        ],
                        'tip'   => null,
                    ],
                    [
                        'title' => 'สมัครสมาชิก / เข้าสู่ระบบ',
                        'tag'   => 'ต้องมีบัญชี',
                        'desc'  => 'เลือกวิธีเข้าสู่ระบบที่สะดวกที่สุดสำหรับคุณ',
                        'items' => [
                            '<strong>LINE Login</strong> — กดปุ่มสีเขียว "เข้าสู่ระบบด้วย LINE" แล้วอนุญาต ระบบ Login อัตโนมัติ',
                            '<strong>Google Login</strong> — กดปุ่ม "เข้าสู่ระบบด้วย Google" แล้วเลือกบัญชี Gmail',
                            '<strong>Email/Password</strong> — กรอกอีเมลและรหัสผ่าน หรือกด "สมัครสมาชิก" ถ้ายังไม่มีบัญชี',
                        ],
                        'tip'   => '💡 แนะนำให้ใช้ <strong>LINE Login</strong> เพราะสะดวกและรวดเร็วที่สุด ไม่ต้องจำรหัสผ่าน',
                    ],
                    [
                        'title' => 'ไปที่ Dashboard แล้วกด "เพิ่มสถานที่"',
                        'tag'   => 'เพิ่มร้าน',
                        'desc'  => 'หลัง Login แล้วจะเห็นเมนูด้านบน กดที่ชื่อของคุณ → "Dashboard"',
                        'items' => [
                            'กดปุ่ม <strong>"+ เพิ่มสถานที่ใหม่"</strong> ในหน้า Dashboard',
                            'หรือกดเมนู <strong>"เพิ่มสถานที่"</strong> ในแถบด้านบน',
                        ],
                        'tip'   => null,
                    ],
                    [
                        'title' => 'กรอกข้อมูลร้านของคุณ',
                        'tag'   => 'ข้อมูลสำคัญ',
                        'desc'  => 'กรอกให้ครบถ้วน ยิ่งครบยิ่งดี ลูกค้าจะค้นหาเจอง่ายขึ้น',
                        'items' => [
                            '<strong>ชื่อร้าน / สถานที่</strong> — กรอกชื่อที่คนรู้จัก',
                            '<strong>หมวดหมู่</strong> — เลือกประเภทของร้าน เช่น ร้านอาหาร, คาเฟ่, ร้านค้า',
                            '<strong>รายละเอียด</strong> — เขียนอธิบายร้าน สินค้า บริการ จุดเด่น',
                            '<strong>ที่อยู่</strong> — กรอกให้ชัดเจน เพื่อแสดงบนแผนที่',
                            '<strong>เบอร์โทร</strong> — ลูกค้าจะได้ติดต่อได้ง่าย',
                            '<strong>รูปภาพหน้าปก</strong> — อัปโหลดรูปสวยๆ ของร้านคุณ',
                            '<strong>โซเชียล</strong> — Facebook, LINE, Instagram (ถ้ามี)',
                        ],
                        'tip'   => '📸 รูปภาพสำคัญมาก! ร้านที่มีรูปสวยจะได้รับความสนใจมากกว่า แนะนำรูปขนาดอย่างน้อย 800×600px',
                    ],
                    [
                        'title' => 'ปักหมุดตำแหน่งบนแผนที่',
                        'tag'   => 'แผนที่',
                        'desc'  => 'ช่วยให้ลูกค้าหาร้านคุณเจอบน City Map ได้ง่ายขึ้น',
                        'items' => [
                            'กดที่แผนที่เพื่อปักหมุดตำแหน่งร้านของคุณ',
                            'ลากหมุดเพื่อปรับตำแหน่งให้ถูกต้อง',
                        ],
                        'tip'   => null,
                    ],
                    [
                        'title' => 'กดส่ง รอการอนุมัติ',
                        'tag'   => 'เกือบเสร็จแล้ว!',
                        'desc'  => 'กดปุ่ม <strong>"ส่งเพื่อรอการอนุมัติ"</strong> แล้วรอทีมงานตรวจสอบ',
                        'items' => [
                            'ทีมงานจะตรวจสอบข้อมูลภายใน <strong>1–3 วันทำการ</strong>',
                            'เมื่ออนุมัติแล้ว ร้านของคุณจะแสดงบนเว็บทันที',
                            'ดูสถานะได้ที่ Dashboard → "ร้านของฉัน"',
                        ],
                        'tip'   => '⏳ ถ้าข้อมูลครบถ้วนและถูกต้อง ทีมงานจะอนุมัติให้เร็วที่สุด',
                    ],
                ];
                ?>

                <div class="space-y-0">
                    <?php foreach ($steps as $i => $step): $last = $i === count($steps) - 1; ?>
                    <div class="flex gap-6 relative <?= $last ? 'pb-0' : 'pb-8' ?>">
                        <?php if (!$last): ?>
                        <div class="guide-step-connector"></div>
                        <?php endif; ?>

                        <!-- Number -->
                        <div class="w-10 h-10 sm:w-12 sm:h-12 min-w-[40px] sm:min-w-[48px] bg-gradient-to-br from-[#1e3a8a] to-[#1e40af] text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-base sm:text-xl font-extrabold shadow-lg shadow-blue-900/20 relative z-10">
                            <?= $i + 1 ?>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <h3 class="text-[#1e3a8a] font-extrabold text-base sm:text-lg"><?= $step['title'] ?></h3>
                                <span class="text-[11px] bg-blue-50 text-blue-700 font-bold px-3 py-0.5 rounded-full whitespace-nowrap"><?= $step['tag'] ?></span>
                            </div>
                            <p class="text-slate-500 text-sm mb-3 leading-relaxed"><?= $step['desc'] ?></p>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-4">
                                <ul class="space-y-2">
                                    <?php foreach ($step['items'] as $item): ?>
                                    <li class="flex items-start gap-2.5 text-sm text-slate-700 leading-relaxed">
                                        <span class="text-teal-600 font-bold mt-0.5 shrink-0">✓</span>
                                        <span><?= $item ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <?php if ($step['tip']): ?>
                            <div class="mt-3 bg-amber-50 border border-yellow-300 rounded-xl px-4 py-3 text-sm text-amber-800 leading-relaxed">
                                <?= $step['tip'] ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Success box -->
                <div class="mt-8 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 sm:p-8 text-center">
                    <div class="text-4xl mb-3">🎉</div>
                    <h3 class="text-xl font-extrabold text-green-900 mb-2">ยินดีด้วย! ร้านของคุณพร้อมให้คนค้นหาแล้ว</h3>
                    <p class="text-green-700 text-sm leading-relaxed">
                        ร้านของคุณจะแสดงบนหน้าหลัก, แผนที่ City Map และผลการค้นหา<br>
                        แชร์ลิงก์ร้านของคุณให้ลูกค้าและเพื่อนๆ ได้เลย!
                    </p>
                    <a href="<?= BASE_URL ?>/register" class="inline-flex items-center gap-2 mt-5 bg-[#1e3a8a] hover:bg-[#1e40af] text-white font-bold px-8 py-3 rounded-xl transition-colors">
                        🚀 สมัครและเริ่มโพสต์เลย
                    </a>
                </div>
            </div>
        </div>

        <!-- Print button -->
        <div class="mt-6 flex justify-end no-print">
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                🖨️ พิมพ์ / บันทึก PDF
            </button>
        </div>

    </div>
</main>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
