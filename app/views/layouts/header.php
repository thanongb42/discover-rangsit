<!DOCTYPE html>
<html lang="<?= currentLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Discover Rangsit - ค้นพบของเด็ด ของดัง และทุกสิ่งในเมืองรังสิต' ?></title>
    
    <?php
        $seo_desc     = $data['description'] ?? 'ค้นหาของดีรังสิต ของดีเมืองรังสิต ของดีนครรังสิต ร้านค้ารังสิต ร้านอาหารรังสิต คาเฟ่รังสิต สถานที่ท่องเที่ยวรังสิต ครบจบในที่เดียว Discover Rangsit แพลตฟอร์มของเทศบาลนครรังสิต';
        $seo_title    = $data['title'] ?? 'Discover Rangsit — ของดีรังสิต ร้านค้า ร้านอาหาร คาเฟ่ สถานที่ท่องเที่ยวนครรังสิต';
        $seo_image    = $data['og_image'] ?? BASE_URL . '/images/og-cover.jpg';
        $seo_url      = $data['og_url']   ?? BASE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $seo_keywords = $data['keywords'] ?? 'ของดีรังสิต, ของดีเมืองรังสิต, ของดีนครรังสิต, ของดีย่านรังสิต, ของกินรังสิต, ร้านค้ารังสิต, ร้านอาหารรังสิต, ร้านดังรังสิต, ร้านดังย่านรังสิต, ร้านเด็ดย่านรังสิต, คาเฟ่รังสิต, เที่ยวรังสิต, ที่เที่ยวรังสิต, ของเด็ดรังสิต, ของดังรังสิต, เทศบาลนครรังสิต, Discover Rangsit, แผนที่รังสิต, ก๋วยเตี๋ยวเรือรังสิต';
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-99N779E4B7"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-99N779E4B7');
    </script>

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="o-7xL1Np4Q3qQU_5JJLugZKRpj15gTxtJ1z6qEkWfdk" />

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

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/images/rangsit-logo.png">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/images/rangsit-logo.png">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/images/rangsit-logo.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($seo_url) ?>">

    <!-- JSON-LD Structured Data for WebSite -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "Discover Rangsit",
      "alternateName": "ของดีรังสิต",
      "url": "<?= BASE_URL ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= BASE_URL ?>/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      },
      "description": "ค้นหาของดีรังสิต ของดีนครรังสิต ร้านค้ารังสิต ร้านอาหาร คาเฟ่ สถานที่ท่องเที่ยวในเมืองรังสิต ครบจบในที่เดียว",
      "publisher": {
        "@type": "GovernmentOrganization",
        "name": "เทศบาลนครรังสิต",
        "url": "https://www.rangsitcity.go.th",
        "areaServed": {
          "@type": "City",
          "name": "รังสิต",
          "alternateName": ["นครรังสิต", "เมืองรังสิต", "Rangsit"],
          "containedInPlace": {
            "@type": "State",
            "name": "ปทุมธานี"
          }
        }
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
                <?php else: ?>
                    <div class="w-px h-5 bg-white/20"></div>
                    <a href="<?= BASE_URL ?>/my-businesses" class="hover:text-blue-100 transition font-medium flex items-center gap-1.5">
                        <i class="fas fa-building text-sm"></i> ธุรกิจของฉัน
                    </a>
                    <a href="<?= BASE_URL ?>/dashboard/add-place" class="bg-yellow-400 hover:bg-yellow-300 text-blue-900 px-4 py-2 rounded-xl text-sm font-extrabold transition flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> เพิ่มธุรกิจ
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
                                $profile_img  = $_SESSION['profile_image'] ?? null;
                                $display_name = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'User';
                                if ($profile_img) {
                                    $img_src = (strpos($profile_img, 'http') === 0) ? $profile_img : BASE_URL . '/' . $profile_img;
                                } else {
                                    $img_src = 'https://ui-avatars.com/api/?name=' . urlencode($display_name) . '&size=80&background=1e3a8a&color=fff';
                                }
                            ?>
                            <img src="<?= htmlspecialchars($img_src) ?>" class="w-full h-full object-cover header-avatar-img" alt="<?= htmlspecialchars($display_name) ?>">
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 animate-fade-in text-slate-800">
                            <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                <p class="text-sm font-bold text-gray-800 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider"><?= $_SESSION['user_role'] ?? 'member' ?></p>
                            </div>
                            <a href="<?= BASE_URL ?>/my-businesses" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition font-semibold">
                                <i class="fas fa-building mr-3 text-primary-400 w-4 text-center"></i> ธุรกิจของฉัน
                            </a>
                            <a href="<?= BASE_URL ?>/dashboard/add-place" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition font-semibold">
                                <i class="fas fa-plus-circle mr-3 text-primary-400 w-4 text-center"></i> เพิ่มธุรกิจใหม่
                            </a>
                            <a href="<?= BASE_URL ?>/my-reviews" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition font-semibold">
                                <i class="fas fa-star mr-3 text-yellow-400 w-4 text-center"></i> รีวิวของฉัน
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="<?= BASE_URL ?>/profile" class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition">
                                <i class="fas fa-user-circle mr-3 text-gray-400 w-4 text-center"></i> <?= t('nav.profile') ?>
                            </a>
                            <div class="border-t border-gray-100 mt-1">
                                <a href="<?= BASE_URL ?>/logout" class="flex items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i> <?= t('nav.logout') ?>
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
                <?php else: ?>
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/15">
                        <p class="text-white font-bold text-sm mb-3 truncate">
                            <i class="fas fa-user-circle mr-2 text-blue-200"></i>
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="<?= BASE_URL ?>/my-businesses" class="flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition">
                                <i class="fas fa-building"></i> ธุรกิจของฉัน
                            </a>
                            <a href="<?= BASE_URL ?>/dashboard/add-place" class="flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition">
                                <i class="fas fa-plus-circle"></i> เพิ่มธุรกิจ
                            </a>
                            <a href="<?= BASE_URL ?>/my-reviews" class="flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition">
                                <i class="fas fa-star text-yellow-300"></i> รีวิวของฉัน
                            </a>
                            <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition">
                                <i class="fas fa-user-circle"></i> โปรไฟล์
                            </a>
                        </div>
                        <a href="<?= BASE_URL ?>/logout" class="flex items-center justify-center gap-2 mt-3 bg-red-500/30 hover:bg-red-500/50 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition w-full">
                            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                        </a>
                    </div>
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