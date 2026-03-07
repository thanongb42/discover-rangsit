<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Discover Rangsit</title>
    
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
                        navy: {
                            800: '#0088CC',
                            900: '#006BA8',
                        },
                        primary: {
                            500: '#0088CC',
                            600: '#006BA8',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        .hero-panel {
            background: linear-gradient(145deg, #003A5C 0%, #005A8E 30%, #0088CC 70%, #33AADC 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(51,170,220,.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(0,58,92,.4) 0%, transparent 60%);
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .18;
            animation: blobFloat 8s ease-in-out infinite;
        }
        .blob-1 { width:380px;height:380px;background:#33AADC;top:-80px;left:-80px;animation-delay:0s; }
        .blob-2 { width:280px;height:280px;background:#0088CC;bottom:60px;right:-60px;animation-delay:3s; }
        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            50%      { transform: translate(15px,-20px) scale(1.05); }
        }
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .feature-card {
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 12px;
            transition: all .3s ease;
        }
        .feature-card:hover {
            background: rgba(255,255,255,.14);
            transform: translateX(4px);
        }
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #0088CC;
            z-index: 2;
        }
        .input-wrap input {
            padding-left: 2.75rem;
            transition: all .25s;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: #0088CC;
            box-shadow: 0 0 0 3px rgba(0,136,204,.12);
        }
        .btn-primary {
            background: linear-gradient(135deg, #005A8E, #0088CC);
            transition: all .3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #003A5C, #006BA8);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0,136,204,.35);
        }
        .fade-right { animation: fadeRight .7s ease both; }
        @keyframes fadeRight { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:none} }
    </style>
</head>
<body class="overflow-hidden">
<div class="flex h-screen">

    <!-- Left - Hero Panel -->
    <div class="hero-panel hidden lg:flex lg:w-[58%] flex-col justify-between p-10 xl:p-14 relative text-white">
        <div class="grid-bg"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center shadow-lg overflow-hidden p-2">
                    <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-bold leading-tight uppercase tracking-wider">Discover Rangsit</h1>
                    <p class="text-blue-100 text-sm">Smart City Platform</p>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="text-5xl font-extrabold leading-tight mb-4">
                    เชื่อมต่อทุกโอกาส<br>
                    <span class="text-blue-200">ในเมืองรังสิต</span>
                </h2>
                <p class="text-blue-50 text-lg leading-relaxed max-w-md">
                    แพลตฟอร์มที่รวมทุกธุรกิจ บริการ และสถานที่สำคัญ
                    เพื่อให้คุณค้นพบสิ่งใหม่ๆ ในเมืองรังสิตได้ง่ายขึ้น
                </p>
            </div>

            <div class="space-y-4">
                <div class="feature-card flex items-center gap-4 px-5 py-4">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-blue-200"></i>
                    </div>
                    <div>
                        <p class="font-bold">Geospatial Explorer</p>
                        <p class="text-blue-100 text-xs">ค้นหาธุรกิจและสถานที่บนแผนที่แบบอัจฉริยะ</p>
                    </div>
                </div>
                <div class="feature-card flex items-center gap-4 px-5 py-4">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-200"></i>
                    </div>
                    <div>
                        <p class="font-bold">Business Intelligence</p>
                        <p class="text-blue-100 text-xs">วิเคราะห์ข้อมูลและการเข้าชมสำหรับผู้ประกอบการ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <p class="text-blue-200/60 text-xs">
                <i class="fas fa-shield-alt mr-1"></i>
                Discover Rangsit Smart City Platform &copy; 2026
            </p>
        </div>
    </div>

    <!-- Right - Form Panel -->
    <div class="w-full lg:w-[42%] flex flex-col justify-center bg-slate-50 px-8 sm:px-12 xl:px-20 py-10 overflow-y-auto">
        
        <div class="lg:hidden flex items-center justify-between mb-8">
            <a href="<?= BASE_URL ?>" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-3 rounded-2xl transition text-sm">
                <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-city text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm leading-tight">Discover Rangsit</p>
                    <p class="text-[10px] text-gray-500 tracking-wider">Smart City</p>
                </div>
            </div>
        </div>

        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 fade-right">
                <h2 class="text-3xl font-black text-gray-800 mb-2">ยินดีต้อนรับ</h2>
                <p class="text-gray-500 text-sm">เข้าสู่ระบบจัดการข้อมูลธุรกิจของคุณ</p>
                <div class="h-1.5 w-12 bg-primary-500 rounded-full mt-4"></div>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <p class="text-sm text-red-700"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <p class="text-sm text-green-700"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login" method="POST" class="space-y-6 fade-right">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">อีเมลผู้ใช้งาน</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" class="w-full py-3.5 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="example@email.com" required>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-bold text-gray-700">รหัสผ่าน</label>
                        <a href="#" class="text-xs font-bold text-primary-600 hover:text-primary-700">ลืมรหัสผ่าน?</a>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" id="password" class="w-full py-3.5 border border-gray-200 rounded-2xl bg-white text-gray-800 text-sm shadow-sm" placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition z-10">
                            <i class="fas fa-eye text-sm" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <script>
                    function togglePassword() {
                        const input = document.getElementById('password');
                        const icon = document.getElementById('toggleIcon');
                        input.type = input.type === 'password' ? 'text' : 'password';
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                </script>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="remember" class="ml-2 text-sm text-gray-500 font-medium cursor-pointer">จดจำการเข้าสู่ระบบ</label>
                </div>

                <button type="submit" class="btn-primary w-full text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-900/20 transition flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    เข้าสู่ระบบ
                </button>
            </form>

            <!-- Social Login Divider -->
            <div class="relative my-8 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <span class="relative px-4 bg-slate-50 text-gray-400 text-xs font-bold uppercase tracking-widest">หรือเข้าใช้งานด้วย</span>
            </div>

            <!-- Social Login Buttons -->
            <div class="space-y-3">
                <!-- LINE -->
                <a href="<?= BASE_URL ?>/login/line" class="w-full bg-[#00b900] hover:bg-[#009900] text-white font-bold py-3.5 rounded-2xl shadow-md transition flex items-center justify-center gap-3">
                    <i class="fab fa-line text-2xl"></i>
                    เข้าสู่ระบบด้วย LINE
                </a>

                <!-- Google -->
                <a href="<?= BASE_URL ?>/login/google" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-bold py-3.5 rounded-2xl shadow-md border border-gray-200 transition flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>
                    เข้าสู่ระบบด้วย Google
                </a>

            </div>

            <div class="text-center mt-2">
                <a href="<?= BASE_URL ?>/line-manual" class="text-[10px] text-gray-400 hover:text-primary-600 transition flex items-center justify-center gap-1">
                    <i class="fas fa-info-circle"></i> คู่มือการใช้งาน LINE Login
                </a>
            </div>

            <div class="mt-10 text-center fade-right">
                <p class="text-gray-500 text-sm">
                    ยังไม่มีบัญชีธุรกิจ? 
                    <a href="<?= BASE_URL ?>/register" class="text-primary-600 font-black ml-1 hover:underline">สมัครสมาชิกที่นี่</a>
                </p>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col items-center gap-4 fade-right">
                <a href="<?= BASE_URL ?>" class="hidden lg:flex text-xs font-bold text-gray-400 hover:text-primary-600 transition items-center">
                    <i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก
                </a>
                
                <p class="text-[9px] text-gray-300 text-center leading-relaxed">
                    คำเตือน: การเข้าถึงระบบโดยมิชอบมีความผิดตาม พรบ.ว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์<br>
                    ระบบมีการบันทึกหมายเลข IP และประวัติการใช้งานเพื่อความปลอดภัย
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>