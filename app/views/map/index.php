<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="flex flex-col md:flex-row h-[calc(100vh-64px)] overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-full md:w-80 lg:w-96 bg-white shadow-xl z-20 flex flex-col sidebar-scroll overflow-y-auto">
        <div class="p-5">
            <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                <i class="fas fa-search-location mr-2 text-navy-800"></i>
                Explore Rangsit
            </h2>
            
            <!-- Search Box -->
            <div class="relative mb-4">
                <input type="text" id="searchInput" class="w-full bg-slate-100 border-none rounded-xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-navy-800 transition text-sm" placeholder="Search businesses, cafes...">
                <button id="searchBtn" class="absolute right-2 top-1.5 bg-navy-800 text-white w-9 h-9 rounded-lg flex items-center justify-center hover:bg-navy-900 transition">
                    <i class="fas fa-search text-sm"></i>
                </button>
            </div>
            
            <!-- Nearby Search -->
            <div class="space-y-3 mb-6">
                <button id="btnNearby" class="w-full bg-emerald-50 text-emerald-700 border border-emerald-200 py-3 rounded-xl font-bold text-sm hover:bg-emerald-100 transition flex items-center justify-center">
                    <i class="fas fa-location-arrow mr-2"></i>
                    Businesses Near Me
                </button>
                <div id="radiusSelectWrapper" class="hidden animate-fade-in">
                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block px-1">Search Radius</label>
                    <select id="radiusSelect" class="w-full bg-white border border-slate-200 rounded-lg py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="1">Within 1 km</option>
                        <option value="3" selected>Within 3 km</option>
                        <option value="5">Within 5 km</option>
                        <option value="10">Within 10 km</option>
                    </select>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6">
                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block px-1">Categories</label>
                <div id="categoryFilterContainer" class="flex flex-wrap gap-2">
                    <select id="categoryFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-navy-800">
                        <option value="">All Categories</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800">Results</h3>
                    <span id="resultCount" class="bg-navy-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                
                <div id="placesList" class="space-y-3">
                    <!-- Cards will be populated here via AJAX -->
                    <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <span class="text-xs font-medium">Loading places...</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Map -->
    <div class="flex-1 relative">
        <div id="map" class="w-full h-full"></div>
        
        <!-- Map Controls Overlays -->
        <div class="absolute top-4 right-4 z-[1000] flex flex-col gap-2">
            <div class="bg-white/90 backdrop-blur p-3 rounded-2xl shadow-lg border border-white flex items-center gap-3">
                <span class="text-xs font-bold text-slate-700">Heatmap</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="heatmapToggle" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-navy-800"></div>
                </label>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>/js/map.js"></script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>