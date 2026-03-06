<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="bg-slate-50 min-h-screen py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <nav class="flex text-sm font-medium text-slate-400 mb-8">
            <a href="<?= BASE_URL ?>" class="hover:text-navy-800 transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-navy-800 font-bold">นโยบายความเป็นส่วนตัว</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-white p-10 md:p-16">
            <h1 class="text-3xl font-black text-slate-800 mb-8 flex items-center gap-3">
                <i class="fas fa-user-shield text-teal-500"></i>
                นโยบายความเป็นส่วนตัว (Privacy Policy)
            </h1>

            <div class="prose prose-slate max-w-none space-y-6 text-slate-600 leading-relaxed">
                <p>
                    เทศบาลนครรังสิต ("เรา") ให้ความสำคัญกับความปลอดภัยของข้อมูลส่วนบุคคลของคุณ แพลตฟอร์ม Discover Rangsit จัดทำขึ้นเพื่อให้สอดคล้องกับพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)
                </p>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">1. ข้อมูลที่เราจัดเก็บ</h2>
                    <ul class="list-disc pl-6 space-y-2">
                        <li><strong>ข้อมูลบัญชี:</strong> ชื่อ-นามสกุล, อีเมล, เบอร์โทรศัพท์ และรหัสผ่านที่ถูกเข้ารหัส</li>
                        <li><strong>ข้อมูลโซเชียล:</strong> กรณีเชื่อมต่อผ่าน LINE เราจะจัดเก็บ LINE ID และรูปโปรไฟล์เพื่อใช้ในการระบุตัวตน</li>
                        <li><strong>ข้อมูลการใช้งาน:</strong> หมายเลข IP Address, ชนิดของบราวเซอร์ และประวัติการเข้าชมสถานที่ต่างๆ ในระบบ</li>
                    </ul>
                    <div class="mt-4 p-4 bg-teal-50 border border-teal-100 rounded-2xl text-xs text-teal-800">
                        <i class="fas fa-info-circle mr-1"></i> ผู้ใช้งานมีหน้าที่ตรวจสอบว่าข้อมูลที่นำเข้าเป็นข้อมูลสาธารณะ และต้องอำพรางข้อมูลส่วนบุคคลอื่นที่ปรากฏในรูปภาพ (เช่น ใบหน้าบุคคลภายนอก) ก่อนการอัปโหลดทุกครั้ง
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">2. วัตถุประสงค์ในการเก็บข้อมูล</h2>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>เพื่อให้บริการระบบสมาชิกและบันทึกข้อมูลธุรกิจ</li>
                        <li>เพื่อใช้ในการวิเคราะห์ข้อมูลสถิติ (Analytics) เพื่อปรับปรุงการให้บริการของเทศบาล</li>
                        <li>เพื่อรักษาความปลอดภัยของระบบตาม พรบ. ว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">3. การเก็บรักษาข้อมูล</h2>
                    <p>
                        เราจะเก็บรักษาข้อมูลของคุณไว้ในเซิร์ฟเวอร์ที่มีระบบรักษาความปลอดภัยสูง ข้อมูลรหัสผ่านจะถูกเข้ารหัสแบบทางเดียว (One-way Hash) ซึ่งแม้แต่ผู้ดูแลระบบก็ไม่สามารถอ่านได้
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">4. คุกกี้ (Cookies)</h2>
                    <p>
                        เว็บไซต์นี้มีการใช้คุกกี้เพื่อบันทึกสถานะการเข้าสู่ระบบและจดจำการตั้งค่าการใช้งานของคุณ คุณสามารถเลือกยอมรับหรือปฏิเสธคุกกี้ได้ผ่านแถบแจ้งเตือนในหน้าแรก
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">5. สิทธิ์ของเจ้าของข้อมูล</h2>
                    <p>
                        คุณมีสิทธิ์ในการเข้าถึง แก้ไข หรือขอให้ลบข้อมูลส่วนบุคคลของคุณได้ตลอดเวลาผ่านเมนู "โปรไฟล์ของฉัน" หรือติดต่อเจ้าหน้าที่เทศบาลนครรังสิต
                    </p>
                </section>

                <div class="mt-12 p-6 bg-slate-50 rounded-3xl border border-slate-100 text-xs text-slate-400">
                    อัปเดตล่าสุดเมื่อ: 6 มีนาคม 2569<br>
                    งานสถิติและข้อมูลสารสนเทศ กองยุทธศาสตร์และงบประมาณ เทศบาลนครรังสิต
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>