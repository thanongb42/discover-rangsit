<?php
/**
 * Admin Layout Header
 * Adapted from admin-layout/header.php & sidebar.php
 */
$page_title = $data['title'] ?? 'Admin Dashboard';
$current_page = $data['current_page'] ?? 'dashboard';

// Role check
$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
$is_operator = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'operator');
$is_member = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'member');

// Menu items configuration
$menu_groups = [
    'main' => [
        'label' => '',
        'items' => [
            ['id' => 'landing', 'icon' => 'fa-door-open', 'label' => 'หน้าแรก', 'url' => BASE_URL . '/'],
            ['id' => 'dashboard', 'icon' => 'fa-home', 'label' => 'แดชบอร์ด', 'url' => BASE_URL . '/dashboard'],
            ['id' => 'map', 'icon' => 'fa-map-marked-alt', 'label' => 'แผนที่เมือง', 'url' => BASE_URL . '/city-map'],
        ]
    ]
];

if ($is_admin) {
    $menu_groups['manage'] = [
        'label' => 'จัดการระบบ',
        'items' => [
            ['id' => 'pending', 'icon' => 'fa-clock', 'label' => 'รอการอนุมัติ', 'url' => BASE_URL . '/admin/pending'],
            ['id' => 'admin_places', 'icon' => 'fa-map-location-dot', 'label' => 'จัดการสถานที่', 'url' => BASE_URL . '/admin/places'],
            ['id' => 'usermanager', 'icon' => 'fa-users-cog', 'label' => 'จัดการผู้ใช้งาน', 'url' => BASE_URL . '/admin/users'],
            ['id' => 'categories', 'icon' => 'fa-list', 'label' => 'หมวดหมู่ธุรกิจ', 'url' => BASE_URL . '/admin/categories'],
            ['id' => 'logs', 'icon' => 'fa-history', 'label' => 'ประวัติการใช้งาน', 'url' => BASE_URL . '/admin/logs'],
        ]
    ];
}

$menu_groups['business'] = [
    'label' => 'ธุรกิจของฉัน',
    'items' => [
        ['id' => 'add_business', 'icon' => 'fa-plus-circle', 'label' => 'เพิ่มธุรกิจใหม่', 'url' => BASE_URL . '/dashboard/add-place'],
        ['id' => 'my_businesses', 'icon' => 'fa-building', 'label' => 'ธุรกิจของฉัน', 'url' => BASE_URL . '/my-businesses'],
        ['id' => 'my_reviews', 'icon' => 'fa-star', 'label' => 'รีวิวของฉัน', 'url' => BASE_URL . '/my-reviews'],
    ]
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Discover Rangsit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            300: '#7cc8fb',
                            400: '#2795F5', // The target color
                            500: '#2795F5',
                            600: '#1d76cc',
                            700: '#1a5ea3',
                            800: '#1a4f86',
                            900: '#1a436f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Blue Button Styles */
        .btn-primary {
            background-color: #2795F5 !important;
            border-color: #2795F5 !important;
            color: white !important;
        }
        .btn-primary:hover {
            background-color: #1d76cc !important;
            border-color: #1d76cc !important;
        }
        /* Sidebar Styles */
        #sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 45%, #2795F5 100%);
            box-shadow: 2px 0 12px rgba(30, 58, 138, 0.2);
        }
        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            margin: 0.125rem 0.5rem;
            border-radius: 0.5rem;
            color: rgba(255,255,255,.78);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .sidebar-menu-item:hover {
            background: rgba(255,255,255,.12);
            color: #ffffff;
        }
        .sidebar-menu-item.active {
            background: rgba(255,255,255,.18);
            color: #ffffff;
            border-left: 3px solid #60a5fa;
            font-weight: 600;
        }
        .sidebar-menu-item i {
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
            color: rgba(255,255,255,.55);
        }
        .sidebar-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: rgba(255,255,255,.38);
            padding: 1rem 1rem 0.4rem 1.25rem;
        }
        .sidebar-transition { transition: all 0.3s ease-in-out; }
        .sidebar-expanded { width: 280px; }
        .sidebar-collapsed { width: 80px; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.2s ease-out forwards;
        }
        @media (max-width: 1023px) {
            .sidebar-mobile { transform: translateX(-100%); }
            .sidebar-mobile.active { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleMobileSidebar()"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-screen sidebar-expanded sidebar-transition z-50 shadow-sm sidebar-mobile">
        <div class="flex flex-col h-full">
            <div class="p-5 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center overflow-hidden">
                        <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit Logo" class="w-full h-full object-contain p-1">
                    </div>
                    <div class="menu-text">
                        <h1 class="text-white font-bold text-lg leading-tight">Discover</h1>
                        <p class="text-white/60 text-xs">Rangsit City</p>
                    </div>
                </div>
                <button class="lg:hidden text-white" onclick="toggleMobileSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4">
                <?php foreach ($menu_groups as $group): ?>
                    <?php if (!empty($group['label'])): ?>
                        <div class="sidebar-section-label"><?= $group['label'] ?></div>
                    <?php endif; ?>
                    <?php foreach ($group['items'] as $item): ?>
                        <a href="<?= $item['url'] ?>" class="sidebar-menu-item <?= $current_page === $item['id'] ? 'active' : '' ?>">
                            <i class="fas <?= $item['icon'] ?> mr-3"></i>
                            <span class="menu-text"><?= $item['label'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </nav>
            
            <div class="p-4 border-t border-white/10">
                <a href="<?= BASE_URL ?>/logout" class="sidebar-menu-item hover:bg-red-500/20 hover:text-red-300">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span class="menu-text">ออกจากระบบ</span>
                </a>
            </div>
        </div>
    </aside>

    <div id="mainContent" class="sidebar-transition lg:ml-[280px] min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-4 py-3">
            <div class="flex items-center justify-between">
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-gray-600">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex-1 hidden md:block">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>
                        <?= date('d M Y') ?> | ระบบจัดการเมืองอัจฉริยะรังสิต
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800"><?= $_SESSION['user_name'] ?? 'User' ?></p>
                        <p class="text-xs text-gray-500"><?= ucfirst($_SESSION['user_role'] ?? 'member') ?></p>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button onclick="toggleUserDropdown()" class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold border border-primary-200 hover:bg-primary-200 transition overflow-hidden">
                            <?php 
                                $profile_img = $_SESSION['profile_image'] ?? null;
                                if($profile_img): 
                                    // Check if it's a full URL (LINE) or a local path
                                    $img_src = (strpos($profile_img, 'http') === 0) ? $profile_img : BASE_URL . '/' . $profile_img;
                            ?>
                                <img src="<?= $img_src ?>" class="w-full h-full object-cover header-avatar-img">
                            <?php else: ?>
                                <span class="header-avatar-img"><?= strtoupper(substr($_SESSION['username'] ?? $_SESSION['user_name'] ?? 'U', 0, 1)) ?></span>
                            <?php endif; ?>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 animate-fade-in">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1 lg:hidden">
                                <p class="text-sm font-bold text-gray-800"><?= $_SESSION['user_name'] ?? 'User' ?></p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider"><?= $_SESSION['user_role'] ?? 'member' ?></p>
                            </div>
                            <a href="<?= BASE_URL ?>/profile" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition">
                                <i class="fas fa-user-circle mr-2"></i> โปรไฟล์ของฉัน
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition">
                                <i class="fas fa-cog mr-2"></i> ตั้งค่าระบบ
                            </a>
                            <div class="border-t border-gray-50 mt-1">
                                <a href="<?= BASE_URL ?>/logout" class="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleUserDropdown() {
                        const dropdown = document.getElementById('userDropdown');
                        dropdown.classList.toggle('hidden');
                        
                        // Close when clicking outside
                        window.onclick = function(event) {
                            if (!event.target.closest('.relative')) {
                                dropdown.classList.add('hidden');
                            }
                        }
                    }
                </script>
            </div>
        </header>

        <main class="p-6 flex-1">
