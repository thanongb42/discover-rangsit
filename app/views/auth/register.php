<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - Discover Rangsit</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#009933',
                            600: '#007a29',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        .hero-panel {
            background: linear-gradient(145deg, #064e3b 0%, #065f46 30%, #0f766e 70%, #0d9488 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(20,184,166,.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(6,78,59,.4) 0%, transparent 60%);
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .18;
            animation: blobFloat 8s ease-in-out infinite;
        }
        .blob-1 { width:380px;height:380px;background:#2dd4bf;top:-80px;left:-80px; }
        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            50%      { transform: translate(15px,-20px) scale(1.05); }
        }
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #0f766e;
            z-index: 2;
        }
        .input-wrap input {
            padding-left: 2.75rem;
            transition: all .25s;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15,118,110,.12);
        }
        .btn-primary {
            background: linear-gradient(135deg, #065f46, #0f766e);
            transition: all .3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #064e3b, #0d9488);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(15,118,110,.35);
        }
        .fade-right { animation: fadeRight .7s ease both; }
        @keyframes fadeRight { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:none} }
    </style>
</head>
<body class="overflow-hidden">
<div class="flex h-screen">

    <!-- Left - Hero Panel -->
    <div class="hero-panel hidden lg:flex lg:w-[45%] flex-col justify-between p-10 xl:p-14 relative text-white">
        <div class="blob blob-1"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center shadow-lg overflow-hidden p-2">
                    <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-bold leading-tight uppercase tracking-wider">Discover Rangsit</h1>
                    <p class="text-teal-200 text-sm">Smart City Platform</p>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-4xl font-extrabold leading-tight mb-4">
                    ขยายโอกาสทางธุรกิจ<br>
                    <span class="text-teal-300">สู่โลกดิจิทัล</span>
                </h2>
                <p class="text-teal-100 text-base leading-relaxed max-w-sm">
                    ร่วมเป็นส่วนหนึ่งของเครือข่ายธุรกิจในเมืองรังสิต 
                    เพิ่มการมองเห็น และเข้าถึงลูกค้ากลุ่มใหม่ได้ทันที
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-teal-500/20 flex items-center justify-center border border-teal-500/30">
                        <i class="fas fa-check text-teal-300 text-xs"></i>
                    </div>
                    <p class="text-sm font-medium">สมัครสมาชิกฟรี ไม่มีค่าใช้จ่าย</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-teal-500/20 flex items-center justify-center border border-teal-500/30">
                        <i class="fas fa-check text-teal-300 text-xs"></i>
                    </div>
                    <p class="text-sm font-medium">จัดการข้อมูลธุรกิจได้ด้วยตัวเอง</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-teal-500/20 flex items-center justify-center border border-teal-500/30">
                        <i class="fas fa-check text-teal-300 text-xs"></i>
                    </div>
                    <p class="text-sm font-medium">รายงานสถิติการเข้าชมแบบ Real-time</p>
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <p class="text-teal-300/60 text-xs uppercase tracking-widest font-bold">
                Discover Rangsit &copy; 2026
            </p>
        </div>
    </div>

    <!-- Right - Form Panel -->
    <div class="w-full lg:w-[55%] flex flex-col justify-center bg-slate-50 px-8 sm:px-16 xl:px-24 py-10 overflow-y-auto">
        
        <div class="max-w-md mx-auto w-full">
            <div class="mb-10 fade-right">
                <h2 class="text-3xl font-black text-gray-800 mb-2">ลงทะเบียนธุรกิจ</h2>
                <p class="text-gray-500 text-sm">สร้างบัญชีผู้ใช้งานเพื่อเริ่มต้นเพิ่มธุรกิจของคุณบนแผนที่</p>
                <div class="h-1.5 w-12 bg-primary-500 rounded-full mt-4"></div>
            </div>

            <form action="<?= BASE_URL ?>/register" method="POST" class="space-y-5 fade-right">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อ-นามสกุล</label>
                        <div class="input-wrap">
                            <i class="fas fa-user icon"></i>
                            <input type="text" name="name" class="w-full py-3 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="ชื่อของคุณ" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                        <div class="input-wrap">
                            <i class="fas fa-phone icon"></i>
                            <input type="text" name="phone" class="w-full py-3 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="08x-xxx-xxxx">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">อีเมลสำหรับเข้าใช้งาน</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" class="w-full py-3 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="example@email.com" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">รหัสผ่าน</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" class="w-full py-3 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="กำหนดรหัสผ่าน" required>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            โดยการคลิก "สร้างบัญชีผู้ใช้งาน" คุณยอมรับเงื่อนไขการให้บริการและนโยบายความเป็นส่วนตัวของเทศบาลนครรังสิต
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-900/20 transition flex items-center justify-center gap-2 mt-4">
                    สร้างบัญชีผู้ใช้งาน
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Social Login Divider -->
            <div class="relative my-8 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <span class="relative px-4 bg-slate-50 text-gray-400 text-xs font-bold uppercase tracking-widest">หรือสมัครสมาชิกด้วย</span>
            </div>

            <!-- LINE Register Button -->
            <a href="<?= BASE_URL ?>/login/line" class="w-full bg-[#00b900] hover:bg-[#009900] text-white font-bold py-3.5 rounded-2xl shadow-md transition flex items-center justify-center gap-3 mb-4">
                <i class="fab fa-line text-2xl"></i>
                สมัครสมาชิกด้วย LINE
            </a>
            
            <div class="text-center">
                <a href="<?= BASE_URL ?>/line-manual" class="text-[10px] text-gray-400 hover:text-primary-600 transition flex items-center justify-center gap-1">
                    <i class="fas fa-info-circle"></i> คู่มือการลงทะเบียนด้วย LINE
                </a>
            </div>

            <div class="mt-10 text-center fade-right">
                <p class="text-gray-500 text-sm font-medium">
                    มีบัญชีอยู่แล้ว? 
                    <a href="<?= BASE_URL ?>/login" class="text-primary-600 font-black ml-1 hover:underline">เข้าสู่ระบบที่นี่</a>
                </p>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex justify-center fade-right">
                <a href="<?= BASE_URL ?>" class="text-xs font-bold text-gray-400 hover:text-primary-600 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>