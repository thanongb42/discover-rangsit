<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="bg-slate-50 min-h-screen py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex text-sm font-medium text-slate-400 mb-8">
            <a href="<?= BASE_URL ?>" class="hover:text-navy-800 transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-navy-800 font-bold">คู่มือการใช้งาน LINE Login</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 overflow-hidden border border-white">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#00b900] to-[#009900] p-10 text-white text-center">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fab fa-line text-4xl text-[#00b900]"></i>
                </div>
                <h1 class="text-3xl font-black mb-2">คู่มือการใช้งาน LINE Login</h1>
                <p class="text-green-50 opacity-90 text-lg">สะดวก รวดเร็ว และปลอดภัย ด้วยการเชื่อมต่อบัญชี LINE ของคุณ</p>
            </div>

            <!-- Content -->
            <div class="p-10 md:p-16 space-y-12">
                
                <!-- Section 1: Register -->
                <section>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center font-black">1</div>
                        <h2 class="text-2xl font-bold text-slate-800">การลงทะเบียนสมาชิกใหม่ด้วย LINE</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="space-y-4">
                            <p class="text-slate-600 leading-relaxed">
                                ผู้ใช้งานสามารถสมัครสมาชิกได้ทันทีโดยไม่ต้องกรอกฟอร์ม เพียงคลิกปุ่ม <span class="font-bold text-[#00b900]">"สมัครสมาชิกด้วย LINE"</span> ระบบจะดึงข้อมูลพื้นฐานจากโปรไฟล์ LINE ของคุณมาสร้างบัญชีให้โดยอัตโนมัติ
                            </p>
                            <ul class="space-y-3">
                                <li class="flex gap-3 text-sm text-slate-500">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    ไม่ต้องตั้งรหัสผ่านใหม่
                                </li>
                                <li class="flex gap-3 text-sm text-slate-500">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    เชื่อมต่อข้อมูลโปรไฟล์ทันที
                                </li>
                                <li class="flex gap-3 text-sm text-slate-500">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    รับการแจ้งเตือนผ่าน LINE Notify (ในอนาคต)
                                </li>
                            </ul>
                        </div>
                        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 flex justify-center">
                            <div class="w-full max-w-[200px] aspect-[9/16] bg-white rounded-2xl shadow-inner border border-slate-200 p-2 relative overflow-hidden">
                                <div class="h-2 w-12 bg-slate-200 rounded-full mx-auto mb-4 mt-1"></div>
                                <div class="space-y-3">
                                    <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                                    <div class="h-20 bg-green-50 rounded-xl border border-green-100 flex items-center justify-center">
                                        <i class="fab fa-line text-green-400 text-3xl"></i>
                                    </div>
                                    <div class="h-3 bg-slate-100 rounded"></div>
                                    <div class="h-3 bg-slate-100 rounded w-5/6"></div>
                                </div>
                                <div class="absolute bottom-4 left-0 right-0 px-4">
                                    <div class="h-10 bg-[#00b900] rounded-xl flex items-center justify-center text-white text-[10px] font-bold">LINE Login</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-slate-100">

                <!-- Section 2: Login -->
                <section>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-black">2</div>
                        <h2 class="text-2xl font-bold text-slate-800">การเข้าสู่ระบบสำหรับสมาชิกเดิม</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        หากคุณเคยมีบัญชี Discover Rangsit อยู่แล้ว และต้องการเปลี่ยนมาใช้การเข้าสู่ระบบผ่าน LINE สามารถทำได้โดย:
                    </p>
                    <div class="bg-blue-50 rounded-3xl p-8 border border-blue-100">
                        <ol class="space-y-4 text-sm text-blue-800 font-medium">
                            <li class="flex gap-4">
                                <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0 text-[10px]">1</span>
                                <span>เข้าสู่ระบบด้วยอีเมลและรหัสผ่านตามปกติ</span>
                            </li>
                            <li class="flex gap-4">
                                <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0 text-[10px]">2</span>
                                <span>ไปที่หน้า <span class="underline">"โปรไฟล์ของฉัน"</span></span>
                            </li>
                            <li class="flex gap-4">
                                <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0 text-[10px]">3</span>
                                <span>คลิกปุ่ม <span class="font-bold text-[#00b900]">"เชื่อมต่อ LINE"</span> ในส่วนจัดการบัญชี</span>
                            </li>
                            <li class="flex gap-4">
                                <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0 text-[10px]">4</span>
                                <span>หลังจากเชื่อมต่อแล้ว ครั้งต่อไปคุณสามารถคลิกปุ่ม LINE Login เพื่อเข้าสู่ระบบได้ทันที</span>
                            </li>
                        </ol>
                    </div>
                </section>

                <hr class="border-slate-100">

                <!-- Section 3: Privacy -->
                <section class="text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                        <i class="fas fa-shield-halved text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">นโยบายความเป็นส่วนตัว</h3>
                    <p class="text-slate-500 text-sm max-w-2xl mx-auto leading-relaxed">
                        การเชื่อมต่อผ่าน LINE ระบบจะขออนุญาตเข้าถึง <span class="font-bold">ชื่อโปรไฟล์, รูปโปรไฟล์ และอีเมล</span> ของคุณเท่านั้น เราจะไม่สามารถเข้าถึงข้อความส่วนตัวหรือโพสต์สถานะของคุณได้ และข้อมูลทั้งหมดจะถูกเก็บรักษาไว้เป็นความลับตามนโยบายของเทศบาลนครรังสิต
                    </p>
                </section>

            </div>

            <!-- Footer -->
            <div class="bg-slate-50 p-10 text-center border-t border-slate-100">
                <a href="<?= BASE_URL ?>/login" class="inline-flex items-center bg-navy-800 text-white px-8 py-4 rounded-2xl font-black transition hover:bg-navy-900 shadow-lg shadow-navy-900/20">
                    ไปที่หน้าเข้าสู่ระบบ
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>