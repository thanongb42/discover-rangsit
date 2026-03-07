<!DOCTYPE html>
<html lang="<?= currentLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Discover Rangsit - ค้นพบของเด็ด ของดัง และทุกสิ่งในเมืองรังสิต' ?></title>
    
    <?php
        $seo_desc     = $data['description'] ?? 'Discover Rangsit แพลตฟอร์มรวมร้านค้า ร้านอาหาร ของเด็ดรังสิต ของดังรังสิต ก๋วยเตี๋ยวเรือรังสิต สถานที่ท่องเที่ยว และบริการในเทศบาลนครรังสิต ค้นหาง่าย ครบจบในที่เดียว';
        $seo_title    = $data['title'] ?? 'Discover Rangsit - ค้นพบทุกสิ่งในเมืองรังสิต';
        $seo_image    = $data['og_image'] ?? BASE_URL . '/images/og-cover.jpg';
        $seo_url      = $data['og_url']   ?? BASE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $seo_keywords = $data['keywords'] ?? 'รังสิต, ของเด็ดรังสิต, ของดังรังสิต, ก๋วยเตี๋ยวเรือรังสิต, ที่เที่ยวรังสิต, ร้านอาหารรังสิต, คาเฟ่รังสิต, เทศบาลนครรังสิต, Discover Rangsit, แผนที่รังสิต';
    ?>
    <!-- SEO Metadata -->
    <meta name="description" content="<?= htmlspecialchars($seo_desc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seo_keywords) ?>">
    <meta name="author" content="เทศบาลนครรังสิต">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= isset($data['og_type']) ? $data['og_type'] : 'website' ?>">
    <meta property="og:url" content="<?= htmlspecialchars($seo_url) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seo_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seo_desc) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seo_image) ?>">
    <meta property="og:locale" content="th_TH">
    <meta property="og:site_name" content="Discover Rangsit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= htmlspecialchars($seo_url) ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($seo_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seo_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($seo_image) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($seo_url) ?>">

    <!-- JSON-LD Structured Data for WebSite -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "Discover Rangsit",
      "url": "<?= BASE_URL ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= BASE_URL ?>/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      },
      "description": "แพลตฟอร์มรวบรวมข้อมูลธุรกิจ ของเด็ด ของดัง ก๋วยเตี๋ยวเรือ และสถานที่ท่องเที่ยวในเมืองรังสิต",
      "publisher": {
        "@type": "GovernmentOrganization",
        "name": "เทศบาลนครรังสิต"
      }
    }
    </script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css?v=1.1">
    
    <!-- Scripts Core -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
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
        .leaflet-container { font-family: inherit; }
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-popup-content-wrapper { padding: 0; overflow: hidden; border-radius: 1.5rem; }
        .leaflet-popup-content { margin: 0 !important; width: 200px !important; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <!-- Navbar -->
    <nav class="bg-[#0088CC] text-white shadow-lg sticky top-0 z-[2000]">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 text-xl font-bold tracking-tight">
                <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit Logo" class="h-10 w-auto">
                <span>Discover Rangsit</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= BASE_URL ?>" class="hover:text-blue-100 transition font-medium"><?= t('nav.home') ?></a>
                <a href="<?= BASE_URL ?>/city-map" class="hover:text-blue-100 transition font-medium"><?= t('nav.map') ?></a>
                <a href="<?= BASE_URL ?>/trending" class="hover:text-blue-100 transition font-medium"><?= t('nav.trending') ?></a>

                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/login" class="hover:text-blue-100 transition font-medium"><?= t('nav.login') ?></a>
                    <a href="<?= BASE_URL ?>/register" class="bg-white/15 hover:bg-white/25 border border-white/30 px-5 py-2 rounded-xl text-sm font-bold transition">
                        <i class="fas fa-plus mr-1"></i> <?= t('nav.add_business') ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Language Toggle -->
                <div class="hidden md:flex items-center bg-white/10 rounded-xl overflow-hidden border border-white/20 text-xs font-black">
                    <a href="<?= BASE_URL ?>/lang/th" class="px-3 py-1.5 transition <?= isLang('th') ? 'bg-white text-[#0088CC]' : 'text-white hover:bg-white/10' ?>">TH</a>
                    <a href="<?= BASE_URL ?>/lang/en" class="px-3 py-1.5 transition <?= isLang('en') ? 'bg-white text-[#0088CC]' : 'text-white hover:bg-white/10' ?>">EN</a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- User Dropdown (Visible on all screens) -->
                    <div class="relative">
                        <button onclick="toggleUserDropdown()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white font-bold border border-white/20 hover:bg-white/20 transition overflow-hidden">
                            <?php 
                                $profile_img = $_SESSION['profile_image'] ?? null;
                                if($profile_img): 
                                    $img_src = (strpos($profile_img, 'http') === 0) ? $profile_img : BASE_URL . '/' . $profile_img;
                            ?>
                                <img src="<?= $img_src ?>" class="w-full h-full object-cover header-avatar-img">
                            <?php else: ?>
                                <span class="header-avatar-img"><?= strtoupper(substr($_SESSION['username'] ?? $_SESSION['user_name'] ?? 'U', 0, 1)) ?></span>
                            <?php endif; ?>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 animate-fade-in text-slate-800">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1 lg:hidden">
                                <p class="text-sm font-bold text-gray-800"><?= $_SESSION['user_name'] ?? 'User' ?></p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider"><?= $_SESSION['user_role'] ?? 'member' ?></p>
                            </div>
                            <a href="<?= BASE_URL ?>/profile" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition">
                                <i class="fas fa-user-circle mr-2"></i> <?= t('nav.profile') ?>
                            </a>
                            <a href="<?= BASE_URL ?>/dashboard" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition">
                                <i class="fas fa-tachometer-alt mr-2"></i> <?= t('nav.dashboard') ?>
                            </a>
                            <div class="border-t border-gray-50 mt-1">
                                <a href="<?= BASE_URL ?>/logout" class="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> <?= t('nav.logout') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Mobile Menu Btn -->
                <button onclick="togglePublicMobileMenu()" class="md:hidden text-2xl p-2 hover:bg-white/10 rounded-xl transition">
                    <i class="fas fa-bars" id="publicMobileMenuIcon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Container -->
        <div id="publicMobileMenu" class="hidden md:hidden bg-[#006BA8] border-t border-white/10 animate-fade-in">
            <div class="container mx-auto px-4 py-6 flex flex-col space-y-4">
                <a href="<?= BASE_URL ?>" class="text-white hover:text-blue-100 font-bold py-2 border-b border-white/5 transition"><?= t('nav.home') ?></a>
                <a href="<?= BASE_URL ?>/city-map" class="text-white hover:text-blue-100 font-bold py-2 border-b border-white/5 transition"><?= t('nav.map') ?></a>
                <a href="<?= BASE_URL ?>/trending" class="text-white hover:text-blue-100 font-bold py-2 border-b border-white/5 transition"><?= t('nav.trending') ?></a>

                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/login" class="text-white hover:text-blue-100 font-bold py-2 border-b border-white/5 transition"><?= t('nav.login') ?></a>
                    <a href="<?= BASE_URL ?>/register" class="bg-white/15 border border-white/30 text-white text-center py-4 rounded-2xl font-bold transition">
                        <?= t('nav.add_business') ?>
                    </a>
                <?php endif; ?>

                <!-- Language Toggle (Mobile) -->
                <div class="flex items-center gap-3 pt-2">
                    <span class="text-white/50 text-xs font-bold uppercase tracking-wider">Language</span>
                    <div class="flex items-center bg-white/10 rounded-xl overflow-hidden border border-white/20 text-xs font-black">
                        <a href="<?= BASE_URL ?>/lang/th" class="px-4 py-2 transition <?= isLang('th') ? 'bg-white text-[#0088CC]' : 'text-white' ?>">TH</a>
                        <a href="<?= BASE_URL ?>/lang/en" class="px-4 py-2 transition <?= isLang('en') ? 'bg-white text-[#0088CC]' : 'text-white' ?>">EN</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function togglePublicMobileMenu() {
            const menu = document.getElementById('publicMobileMenu');
            const icon = document.getElementById('publicMobileMenuIcon');
            menu.classList.toggle('hidden');
            
            if (menu.classList.contains('hidden')) {
                icon.classList.replace('fa-times', 'fa-bars');
            } else {
                icon.classList.replace('fa-bars', 'fa-times');
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
            
            // Close when clicking outside
            window.onclick = function(event) {
                if (!event.target.closest('.relative')) {
                    const d = document.getElementById('userDropdown');
                    if (d) d.classList.add('hidden');
                }
            }
        }
    </script>
    <main>