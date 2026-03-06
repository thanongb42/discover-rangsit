<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="relative flex flex-col md:flex-row h-[calc(100vh-64px)] overflow-hidden">
    <!-- Map Container (Priority 1 on Mobile) -->
    <div class="flex-1 relative bg-slate-200 order-1 md:order-2">
        <div id="map" class="absolute inset-0 w-full h-full" style="min-height: 300px;"></div>
        
        <!-- Mobile Toggle Button (Only visible on mobile) -->
        <div class="md:hidden absolute bottom-24 left-1/2 -translate-x-1/2 z-[999]">
            <button onclick="toggleMobileSidebar()" class="bg-navy-800 text-white px-6 py-3 rounded-full font-black shadow-2xl flex items-center gap-2 border-2 border-white/20 active:scale-95 transition">
                <i class="fas fa-list-ul"></i>
                <span>ดูรายการร้านค้า</span>
            </button>
        </div>

        <!-- Map Controls Overlays -->
        <div class="absolute top-4 right-4 z-[998] flex flex-col gap-2">
            <div class="bg-white/90 backdrop-blur p-3 rounded-2xl shadow-lg border border-white flex items-center gap-3">
                <span class="text-xs font-bold text-slate-700">Heatmap</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="heatmapToggle" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-navy-800"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Sidebar (Bottom Sheet on Mobile) -->
    <aside id="mapSidebar" class="fixed inset-x-0 bottom-0 z-[1000] md:relative md:inset-auto md:w-80 lg:w-96 bg-white shadow-[0_-10px_40px_rgba(0,0,0,0.1)] md:shadow-xl flex flex-col translate-y-[calc(100%-60px)] md:translate-y-0 transition-transform duration-500 ease-in-out h-[85vh] md:h-full order-2 md:order-1 rounded-t-[2.5rem] md:rounded-none">
        
        <!-- Pull Handle (Mobile Only) -->
        <div class="md:hidden flex flex-col items-center py-3 cursor-pointer border-b border-slate-50 shrink-0" onclick="toggleMobileSidebar()">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mb-1"></div>
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">แตะเพื่อปิดรายการ</span>
        </div>

        <!-- Fixed Header Section (Search & Filters) -->
        <div class="p-5 pb-4 shrink-0 border-b border-slate-50">
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
            <div class="space-y-3 mb-4">
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
            <div>
                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block px-1">Categories & Filters</label>
                <div class="grid grid-cols-1 gap-3">
                    <select id="categoryFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-navy-800">
                        <option value="">All Categories</option>
                    </select>
                    <div class="grid grid-cols-2 gap-2">
                        <select id="ratingFilter" onchange="filterPlaces()" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 text-xs focus:ring-2 focus:ring-navy-800">
                            <option value="0">Any Rating</option>
                            <option value="4">4.0+ Stars</option>
                            <option value="3">3.0+ Stars</option>
                        </select>
                        <select id="sortFilter" onchange="filterPlaces()" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 text-xs focus:ring-2 focus:ring-navy-800">
                            <option value="default">Default</option>
                            <option value="rating">Top Rated</option>
                            <option value="views">Most Viewed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Results Section -->
        <div class="flex-1 overflow-y-auto sidebar-scroll p-5 pt-4 bg-slate-50/30">
            <div class="flex items-center justify-between mb-4 sticky top-0 bg-white/80 backdrop-blur-sm z-10 py-1 rounded-full px-3 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Results</h3>
                <span id="resultCount" class="bg-navy-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
            </div>
            
            <div id="placesList" class="space-y-3 pb-20">
                <!-- Cards will be populated here via AJAX -->
                <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <span class="text-xs font-medium">Loading places...</span>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
    
    let isSidebarOpen = false;
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('mapSidebar');
        isSidebarOpen = !isSidebarOpen;
        
        if (isSidebarOpen) {
            sidebar.style.transform = 'translateY(0)';
        } else {
            sidebar.style.transform = 'translateY(calc(100% - 60px))';
        }
    }
</script>
<script src="<?= BASE_URL ?>/js/map.js"></script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>