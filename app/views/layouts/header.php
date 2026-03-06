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
        .marker-popup .popup-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <!-- Navbar -->
    <nav class="bg-[#1e3a8a] text-white shadow-lg sticky top-0 z-[1001]">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 text-xl font-bold tracking-tight">
                <i class="fa-solid fa-city text-teal-400"></i>
                <span>Discover Rangsit</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= BASE_URL ?>" class="hover:text-teal-300 transition font-medium">Map Explorer</a>
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
            <button class="md:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    <main>