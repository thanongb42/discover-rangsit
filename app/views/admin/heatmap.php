<?php require_once APP_ROOT . '/app/views/layouts/admin_header.php'; ?>

<!-- Leaflet + Heat plugin (admin layout doesn't load these) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<!-- Header -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#1e3a8a,#0088CC)">
            <i class="fas fa-fire-alt text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Heatmap Analytics</h1>
            <p class="text-gray-400 text-xs">ความหนาแน่นการเข้าชมธุรกิจในพื้นที่รังสิต</p>
        </div>
    </div>
    <!-- Time Filter -->
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-2xl p-1.5 shadow-sm">
        <?php foreach ([7 => '7 วัน', 30 => '30 วัน', 90 => '90 วัน', 0 => 'ทั้งหมด'] as $d => $label): ?>
        <button class="period-btn px-4 py-2 rounded-xl text-xs font-bold transition <?= $d === 30 ? 'bg-[#0088CC] text-white' : 'text-gray-500 hover:bg-gray-100' ?>"
                data-days="<?= $d ?>">
            <?= $label ?>
        </button>
        <?php endforeach; ?>
    </div>
</div>

<!-- KPI Row -->
<div class="grid grid-cols-3 gap-4 mb-6" id="kpi-row">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 mb-1">การเข้าชมในช่วงเวลา</p>
        <p class="text-2xl font-black text-gray-800" id="kpi-views">—</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 mb-1">สถานที่ที่มีการเข้าชม</p>
        <p class="text-2xl font-black text-gray-800" id="kpi-places">—</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 mb-1">ชั่วโมง Peak</p>
        <p class="text-2xl font-black text-gray-800" id="kpi-peak">—</p>
    </div>
</div>

<!-- Map -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
        <div class="flex items-center gap-3 text-xs text-gray-400 font-bold">
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> น้อย
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-lime-400 inline-block"></span> ปานกลาง
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> สูง
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> สูงมาก
            </span>
        </div>
        <span id="map-status" class="text-xs text-gray-400">กำลังโหลด...</span>
    </div>
    <div id="heatmap" style="height:520px; width:100%;"></div>
</div>

<script>
(function () {
    const BASE = '<?= BASE_URL ?>';
    let map, heatLayer, markerLayer;
    let activeDays = 30;

    function initMap() {
        map = L.map('heatmap', { zoomControl: true }).setView([14.0, 100.75], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        markerLayer = L.layerGroup().addTo(map);
    }

    function fmt(n) { return Number(n).toLocaleString('th-TH'); }

    async function load(days) {
        document.getElementById('map-status').textContent = 'กำลังโหลด...';
        try {
            const res  = await fetch(`${BASE}/api/admin/heatmap-data?days=${days}`);
            const data = await res.json();
            if (!data.success) return;

            // KPI
            const s = data.stats;
            document.getElementById('kpi-views').textContent  = fmt(s.total_views  ?? 0);
            document.getElementById('kpi-places').textContent = fmt(s.active_places ?? 0);
            document.getElementById('kpi-peak').textContent   = s.peak_hour != null
                ? s.peak_hour + ':00 น.'
                : '—';

            // Heat layer
            if (heatLayer) map.removeLayer(heatLayer);
            markerLayer.clearLayers();

            const pts = data.points || [];
            if (pts.length === 0) {
                document.getElementById('map-status').textContent = 'ยังไม่มีข้อมูล';
                return;
            }

            const heat = pts.map(p => [p[0], p[1], p[2]]);
            heatLayer = L.heatLayer(heat, {
                radius: 30,
                blur: 20,
                maxZoom: 16,
                max: 1.0,
                gradient: { 0.2: '#3b82f6', 0.5: '#84cc16', 0.7: '#facc15', 1.0: '#ef4444' }
            }).addTo(map);

            document.getElementById('map-status').textContent =
                pts.length.toLocaleString('th-TH') + ' สถานที่';
        } catch (e) {
            document.getElementById('map-status').textContent = 'โหลดข้อมูลไม่สำเร็จ';
        }
    }

    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.period-btn').forEach(b => {
                b.classList.remove('bg-[#0088CC]', 'text-white');
                b.classList.add('text-gray-500', 'hover:bg-gray-100');
            });
            this.classList.add('bg-[#0088CC]', 'text-white');
            this.classList.remove('text-gray-500', 'hover:bg-gray-100');
            activeDays = parseInt(this.dataset.days);
            load(activeDays);
        });
    });

    initMap();
    load(activeDays);
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/admin_footer.php'; ?>
