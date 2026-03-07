<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="relative flex flex-col md:flex-row h-[calc(100vh-64px)] overflow-hidden">
    <!-- Map Container (Priority 1 on Mobile) -->
    <div class="flex-1 relative bg-slate-200 order-1 md:order-2">
        <div id="map" class="absolute inset-0 w-full h-full" style="min-height: 300px;"></div>
        
        <!-- Mobile Toggle Button (Only visible on mobile) -->
        <div class="md:hidden absolute bottom-24 left-1/2 -translate-x-1/2 z-[999]">
            <button onclick="toggleMobileSidebar()" class="bg-navy-800 text-white px-6 py-3 rounded-full font-black shadow-2xl flex items-center gap-2 border-2 border-white/20 active:scale-95 transition">
                <i class="fas fa-list-ul"></i>
                <span><?= t('map.view_list') ?></span>
            </button>
        </div>

        <!-- PM2.5 Floating Widget (top-center) -->
        <div id="mapAqiWidget" class="hidden absolute top-4 left-1/2 -translate-x-1/2 z-[998]">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-xl border border-white/80 px-6 py-4 flex items-center gap-5 min-w-[340px]">
                <!-- Color Indicator -->
                <div class="relative flex-shrink-0">
                    <div id="mapAqiDot" class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-wind text-white text-2xl"></i>
                    </div>
                </div>
                <!-- Data -->
                <div class="flex-1">
                    <div class="flex items-baseline gap-2 mb-1">
                        <span class="text-3xl font-black text-slate-900" id="mapAqiPM25">--</span>
                        <span class="text-sm font-bold text-slate-400">μg/m³</span>
                        <span class="text-slate-200 mx-1">|</span>
                        <span class="text-sm font-bold text-slate-500">AQI</span>
                        <span class="text-2xl font-black text-slate-800" id="mapAqiVal">--</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="mapAqiLabel" class="text-xs font-black px-3 py-1 rounded-full text-white"></span>
                        <span class="text-xs font-bold text-slate-400">PM2.5</span>
                    </div>
                </div>
                <!-- Time -->
                <div class="text-right flex-shrink-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5"><?= isLang('en') ? 'Last update' : 'อัปเดตล่าสุด' ?></div>
                    <div class="text-xs font-black text-slate-600" id="mapAqiTime">--:--</div>
                    <div class="text-[10px] text-slate-400 mt-0.5"><?= isLang('en') ? 'Rangsit Campus' : 'วิทยาเขตรังสิต' ?></div>
                </div>
            </div>
        </div>

        <!-- Map Controls Overlays -->
        <div class="absolute top-4 right-4 z-[998] flex flex-col gap-2">
            <!-- Heatmap Toggle -->
            <div class="bg-white/90 backdrop-blur p-3 rounded-2xl shadow-lg border border-white flex items-center gap-3">
                <span class="text-xs font-bold text-slate-700">Heatmap</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="heatmapToggle" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0088CC]"></div>
                </label>
            </div>

            <!-- Category Layer Panel -->
            <div id="layerPanelWrapper" class="hidden bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-white overflow-hidden">
                <button onclick="toggleLayerPanel()" class="w-full flex items-center justify-between gap-2 px-3 py-2.5 hover:bg-slate-50 transition">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-layer-group text-[#0088CC] text-sm"></i>
                        <span class="text-xs font-bold text-slate-700">Layers</span>
                    </div>
                    <i id="layerPanelChevron" class="fas fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200"></i>
                </button>
                <div id="layerPanel" class="hidden border-t border-slate-100 px-3 py-2 min-w-[170px] max-h-64 overflow-y-auto">
                    <div id="layerPanelList" class="space-y-1"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar (Bottom Sheet on Mobile) -->
    <aside id="mapSidebar" class="fixed inset-x-0 bottom-0 z-[1000] md:relative md:inset-auto md:w-80 lg:w-96 bg-white shadow-[0_-10px_40px_rgba(0,0,0,0.1)] md:shadow-xl flex flex-col translate-y-[calc(100%-60px)] md:translate-y-0 transition-transform duration-500 ease-in-out h-[85vh] md:h-full order-2 md:order-1 rounded-t-[2.5rem] md:rounded-none">
        
        <!-- Pull Handle (Mobile Only) -->
        <div class="md:hidden flex flex-col items-center py-3 cursor-pointer border-b border-slate-50 shrink-0" onclick="toggleMobileSidebar()">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mb-1"></div>
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?= t('map.close_list') ?></span>
        </div>

        <!-- Fixed Header Section (Search & Filters) -->
        <div class="p-5 pb-4 shrink-0 border-b border-slate-50">
            <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                <i class="fas fa-search-location mr-2 text-navy-800"></i>
                <?= t('map.explore') ?>
            </h2>

            <!-- Search Box -->
            <div class="relative mb-4">
                <input type="text" id="searchInput" class="w-full bg-slate-100 border-none rounded-xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-navy-800 transition text-sm" placeholder="<?= t('map.search.placeholder') ?>">
                <button id="searchBtn" class="absolute right-2 top-1.5 bg-navy-800 text-white w-9 h-9 rounded-lg flex items-center justify-center hover:bg-navy-900 transition">
                    <i class="fas fa-search text-sm"></i>
                </button>
            </div>
            
            <!-- Nearby Search -->
            <div class="space-y-3 mb-4">
                <button id="btnNearby" class="w-full bg-emerald-50 text-emerald-700 border border-emerald-200 py-3 rounded-xl font-bold text-sm hover:bg-emerald-100 transition flex items-center justify-center">
                    <i class="fas fa-location-arrow mr-2"></i>
                    <?= t('map.nearby') ?>
                </button>
                <div id="radiusSelectWrapper" class="hidden animate-fade-in">
                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block px-1"><?= t('map.radius.label') ?></label>
                    <select id="radiusSelect" class="w-full bg-white border border-slate-200 rounded-lg py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="1"><?= t('map.radius.1km') ?></option>
                        <option value="3" selected><?= t('map.radius.3km') ?></option>
                        <option value="5"><?= t('map.radius.5km') ?></option>
                        <option value="10"><?= t('map.radius.10km') ?></option>
                    </select>
                </div>
            </div>

            <!-- Filters -->
            <div>
                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block px-1"><?= t('map.filter.label') ?></label>
                <div class="grid grid-cols-1 gap-3">
                    <select id="categoryFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-navy-800">
                        <option value=""><?= t('map.filter.all_cat') ?></option>
                    </select>
                    <div class="grid grid-cols-2 gap-2">
                        <select id="ratingFilter" onchange="filterPlaces()" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 text-xs focus:ring-2 focus:ring-navy-800">
                            <option value="0"><?= t('map.filter.any_rating') ?></option>
                            <option value="4"><?= t('map.filter.rating4') ?></option>
                            <option value="3"><?= t('map.filter.rating3') ?></option>
                        </select>
                        <select id="sortFilter" onchange="filterPlaces()" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 text-xs focus:ring-2 focus:ring-navy-800">
                            <option value="default"><?= t('map.filter.default') ?></option>
                            <option value="rating"><?= t('map.filter.top_rated') ?></option>
                            <option value="views"><?= t('map.filter.most_viewed') ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Results Section -->
        <div class="flex-1 overflow-y-auto sidebar-scroll p-5 pt-4 bg-slate-50/30">
            <div class="flex items-center justify-between mb-4 sticky top-0 bg-white/80 backdrop-blur-sm z-10 py-1 rounded-full px-3 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider"><?= t('map.results') ?></h3>
                <span id="resultCount" class="bg-navy-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
            </div>
            
            <div id="placesList" class="space-y-3 pb-20">
                <!-- Cards will be populated here via AJAX -->
                <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <span class="text-xs font-medium"><?= t('map.loading') ?></span>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
    <?php
        $mapSettingsFile = APP_ROOT . '/config/map_settings.json';
        $mapSettings = file_exists($mapSettingsFile) ? json_decode(file_get_contents($mapSettingsFile), true) : [];
        $mapSettings = array_merge([
            'clustering_enabled' => true,
            'disable_clustering_at_zoom' => 14,
            'max_cluster_radius' => 50,
            'spiderfy_on_max_zoom' => true
        ], $mapSettings);
    ?>
    const MAP_SETTINGS = <?= json_encode($mapSettings) ?>;
    
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

<script>
(async function loadMapAQI() {
    try {
        const res  = await fetch('<?= BASE_URL ?>/api/air-quality');
        const data = await res.json();
        if (!data.success) return;

        const isEN  = <?= isLang('en') ? 'true' : 'false' ?>;
        const label = isEN ? data.labelEN : data.labelTH;

        document.getElementById('mapAqiDot').style.backgroundColor   = data.color_hex;
        document.getElementById('mapAqiPM25').textContent             = data.pm25_value;
        document.getElementById('mapAqiVal').textContent              = data.pm25_aqi;
        document.getElementById('mapAqiLabel').textContent            = label;
        document.getElementById('mapAqiLabel').style.backgroundColor  = data.color_hex;
        document.getElementById('mapAqiTime').textContent             = data.time;

        document.getElementById('mapAqiWidget').classList.remove('hidden');
    } catch(e) {}
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>