<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="bg-slate-50 min-h-screen py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <nav class="flex text-sm font-medium text-slate-400 mb-8">
            <a href="<?= BASE_URL ?>" class="hover:text-navy-800 transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-navy-800 font-bold">ข้อกำหนดและเงื่อนไข</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-white p-10 md:p-16">
            <h1 class="text-3xl font-black text-slate-800 mb-8 flex items-center gap-3">
                <i class="fas fa-file-contract text-blue-500"></i>
                ข้อกำหนดและเงื่อนไขการใช้งาน
            </h1>

            <div class="prose prose-slate max-w-none space-y-6 text-slate-600 leading-relaxed">
                <p>
                    ยินดีต้อนรับสู่ Discover Rangsit แพลตฟอร์มเพื่อการค้นหาข้อมูลและส่งเสริมเศรษฐกิจดิจิทัลในเขตเทศบาลนครรังสิต การใช้งานเว็บไซต์นี้ถือว่าคุณยอมรับข้อกำหนดดังต่อไปนี้:
                </p>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">1. การใช้งานระบบ</h2>
                    <p>
                        ผู้ใช้งานตกลงที่จะไม่ใช้ระบบนี้ในทางที่ผิดกฎหมาย หรือขัดต่อศีลธรรมอันดีงาม รวมถึงไม่กระทำการใดๆ ที่ส่งผลกระทบต่อเสถียรภาพของระบบ
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">2. ข้อมูลธุรกิจและสถานที่</h2>
                    <p class="mb-3">
                        เจ้าของธุรกิจที่ลงทะเบียนข้อมูล ตกลงว่าข้อมูลที่ให้ไว้เป็นข้อมูลสาธารณะที่สามารถเปิดเผยได้เพื่อประโยชน์ในการประชาสัมพันธ์เมืองรังสิต
                    </p>
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-2xl text-amber-800 text-sm">
                        <p class="font-bold mb-1">ข้อกำหนดเรื่องข้อมูลส่วนบุคคลในรูปภาพ:</p>
                        <p>ข้อมูลส่วนตัวหรือรูปภาพที่มีข้อมูลส่วนบุคคล (เช่น ใบหน้าบุคคลที่ไม่เกี่ยวข้อง, ป้ายทะเบียนรถ) จะต้องทำการอำพรางหรือได้รับอนุญาตจากผู้ที่อยู่ในเหตุการณ์ก่อนการนำเข้าข้อมูลทุกครั้ง</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">3. การคุ้มครองข้อมูลตาม พรบ.คอมพิวเตอร์</h2>
                    <p>
                        การกระทำใดๆ ที่เป็นการเข้าถึงระบบโดยมิชอบ (Hacking), การทำลายข้อมูล หรือการนำเข้าข้อมูลอันเป็นเท็จสู่ระบบคอมพิวเตอร์ จะมีความผิดตามพระราชบัญญัติว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์ และจะถูกดำเนินคดีตามกฎหมายสูงสุด
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">4. ความรับผิดชอบ</h2>
                    <p>
                        เทศบาลนครรังสิตจัดทำระบบนี้ขึ้นเพื่อเป็นสื่อกลางข้อมูลเท่านั้น เราจะไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดจากการทำธุรกรรมระหว่างผู้ซื้อและผู้ขายผ่านแพลตฟอร์มนี้
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-3">5. การระงับการใช้งาน</h2>
                    <p>
                        ผู้ดูแลระบบมีสิทธิ์ในการระงับบัญชีผู้ใช้งาน หรือลบข้อมูลสถานที่ใดๆ ที่ตรวจสอบพบว่าไม่เป็นความจริง หรือมีการกระทำที่ละเมิดข้อกำหนด โดยไม่ต้องแจ้งให้ทราบล่วงหน้า
                    </p>
                </section>

                <div class="mt-12 p-6 bg-slate-50 rounded-3xl border border-slate-100 text-xs text-slate-400">
                    อัปเดตล่าสุดเมื่อ: 6 มีนาคม 2569<br>
                    เทศบาลนครรังสิต
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>