<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Discover Rangsit' ?></title>
    
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            800: '#1e3a8a',
                            900: '#1e3a8a',
                        },
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
        .leaflet-container { font-family: inherit; }
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-popup-content-wrapper { padding: 0; overflow: hidden; border-radius: 1.5rem; }
        .leaflet-popup-content { margin: 0 !important; width: 200px !important; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <!-- Navbar -->
    <nav class="bg-[#1e3a8a] text-white shadow-lg sticky top-0 z-[1001]">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 text-xl font-bold tracking-tight">
                <img src="<?= BASE_URL ?>/images/rangsit-logo.png" alt="Rangsit Logo" class="h-10 w-auto">
                <span>Discover Rangsit</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= BASE_URL ?>" class="hover:text-teal-300 transition font-medium">Home</a>
                <a href="<?= BASE_URL ?>/city-map" class="hover:text-teal-300 transition font-medium">Map Explorer</a>
                <a href="<?= BASE_URL ?>/trending" class="hover:text-teal-300 transition font-medium">Trending</a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="bg-white/10 hover:bg-white/20 px-4 py-1.5 rounded-lg transition text-sm">Dashboard</a>
                    <a href="<?= BASE_URL ?>/logout" class="text-red-300 hover:text-red-400 text-sm"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="hover:text-teal-300 transition font-medium">Login</a>
                    <a href="<?= BASE_URL ?>/register" class="bg-primary-500 hover:bg-primary-600 px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-green-900/20 transition">
                        <i class="fas fa-plus mr-1"></i> Add Business
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Btn -->
            <button onclick="togglePublicMobileMenu()" class="md:hidden text-2xl p-2 hover:bg-white/10 rounded-xl transition">
                <i class="fas fa-bars" id="publicMobileMenuIcon"></i>
            </button>
        </div>

        <!-- Mobile Menu Container -->
        <div id="publicMobileMenu" class="hidden md:hidden bg-navy-900 border-t border-white/10 animate-fade-in">
            <div class="container mx-auto px-4 py-6 flex flex-col space-y-4">
                <a href="<?= BASE_URL ?>" class="text-white hover:text-teal-300 font-bold py-2 border-b border-white/5 transition">Home</a>
                <a href="<?= BASE_URL ?>/city-map" class="text-white hover:text-teal-300 font-bold py-2 border-b border-white/5 transition">Map Explorer</a>
                <a href="<?= BASE_URL ?>/trending" class="text-white hover:text-teal-300 font-bold py-2 border-b border-white/5 transition">Trending</a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="text-teal-400 font-bold py-2 transition">Dashboard</a>
                    <a href="<?= BASE_URL ?>/logout" class="text-red-400 font-bold py-2 transition">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="text-white hover:text-teal-300 font-bold py-2 border-b border-white/5 transition">Login</a>
                    <a href="<?= BASE_URL ?>/register" class="bg-primary-500 text-white text-center py-4 rounded-2xl font-bold shadow-lg transition">
                        Add Business
                    </a>
                <?php endif; ?>
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
    </script>
    <main>