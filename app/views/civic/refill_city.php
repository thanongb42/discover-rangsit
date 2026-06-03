<?php
$kiosks = $data['kiosks'] ?? [];
$places = $data['places'] ?? [];

$points = [];
function refill_extract_code(...$values) {
    foreach ($values as $value) {
        if (!empty($value) && preg_match('/rsc\d{4}/i', (string)$value, $matches)) {
            return strtoupper($matches[0]);
        }
    }

    return null;
}

foreach ($kiosks as $kiosk) {
    if (empty($kiosk->latitude) || empty($kiosk->longitude)) continue;
    $points[] = [
        'id' => 'kiosk-' . ($kiosk->id ?? $kiosk->kiosk_code),
        'code' => $kiosk->kiosk_code ?? 'Water Kiosk',
        'name' => $kiosk->kiosk_code ?? 'Water Kiosk',
        'address' => $kiosk->address ?? '',
        'lat' => (float)$kiosk->latitude,
        'lng' => (float)$kiosk->longitude,
        'type' => 'kiosk',
        'status' => $kiosk->status ?? 'active',
        'qrcode_img' => $kiosk->qrcode_img ?? '',
    ];
}

foreach ($places as $place) {
    if (empty($place->latitude) || empty($place->longitude)) continue;
    $placeCode = refill_extract_code(
        $place->slug ?? '',
        $place->line_qr ?? '',
        $place->name ?? '',
        $place->description ?? '',
        $place->address ?? ''
    ) ?: $place->name;

    $points[] = [
        'id' => 'place-' . $place->id,
        'code' => $placeCode,
        'name' => $place->name,
        'address' => $place->address ?? '',
        'lat' => (float)$place->latitude,
        'lng' => (float)$place->longitude,
        'type' => 'place',
        'slug' => $place->slug,
        'status' => 'active',
        'qrcode_img' => $place->line_qr ?? '',
    ];
}

require_once APP_ROOT . '/app/views/layouts/header.php';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

<section class="bg-gradient-to-br from-cyan-50 via-white to-blue-50 border-b border-slate-100">
    <div class="container mx-auto px-4 py-10">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white border border-cyan-100 text-cyan-700 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-5 shadow-sm">
                    <i class="fas fa-tint"></i>
                    Refill City
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-4">จุดตู้น้ำดื่มเมืองรังสิต</h1>
                <p class="text-slate-600 text-lg leading-relaxed">
                    แผนที่จุดติดตั้งระบบตู้น้ำดื่มและจุดเติมน้ำสะอาดในพื้นที่เทศบาลนครรังสิต
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3 min-w-[240px]">
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <div class="text-3xl font-black text-cyan-700"><?= count($points) ?></div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active Points</div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <div class="text-3xl font-black text-cyan-700"><?= count($kiosks) ?></div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Water Kiosks</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 py-8">
    <div class="bg-white border border-slate-100 shadow-sm rounded-3xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-[360px_minmax(0,1fr)] min-h-[680px]">
            <aside class="border-b lg:border-b-0 lg:border-r border-slate-100 flex flex-col min-h-[360px]">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-800">รายการจุดบริการ</h2>
                    <p class="text-sm text-slate-400 mt-1">ค้นหาและเลือกจุดบนแผนที่</p>
                    <div class="relative mt-4">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        <input id="refillSearch" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" placeholder="ค้นหารหัสตู้หรือที่อยู่...">
                    </div>
                </div>
                <div id="refillList" class="flex-1 overflow-y-auto sidebar-scroll p-3 space-y-2 max-h-[420px] lg:max-h-none"></div>
            </aside>
            <div class="relative min-h-[520px]">
                <?php if (empty($points)): ?>
                    <div class="absolute inset-0 flex items-center justify-center bg-slate-50 text-center p-8">
                        <div>
                            <i class="fas fa-tint text-cyan-200 text-6xl mb-5"></i>
                            <h3 class="text-2xl font-black text-slate-700 mb-2">ยังไม่มีข้อมูลจุดตู้น้ำดื่ม</h3>
                            <p class="text-slate-400 max-w-xl">เมื่อมีข้อมูลในตาราง `water_kiosks` หรือสถานที่ `place_type = facility` ระบบจะแสดงบนแผนที่นี้อัตโนมัติ</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div id="refillMap" class="absolute inset-0"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($points)): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
const REFILL_POINTS = <?= json_encode($points, JSON_UNESCAPED_UNICODE) ?>;

document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('refillMap', { zoomControl: true }).setView([13.9840, 100.6125], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markerGroup = L.markerClusterGroup({
        chunkedLoading: true,
        showCoverageOnHover: false,
        maxClusterRadius: 48
    }).addTo(map);

    const markerMap = new Map();

    function iconFor(point) {
        return L.divIcon({
            html: `<div class="refill-marker"><i class="fas fa-tint"></i></div>`,
            className: 'refill-marker-wrap',
            iconSize: [42, 42],
            iconAnchor: [21, 42],
            popupAnchor: [0, -38]
        });
    }

    function normalizeUploadPath(path) {
        return String(path || '')
            .replace(/\\/g, '/')
            .replace(/^public\//, '')
            .replace(/^\/+/, '');
    }

    function basename(path) {
        const cleanPath = normalizeUploadPath(path);
        return cleanPath.split('/').pop();
    }

    function extractKioskCode(...values) {
        for (const value of values) {
            const match = String(value || '').match(/rsc\d{4}/i);
            if (match) {
                return match[0].toUpperCase();
            }
        }

        return '';
    }

    function uniqueUrls(urls) {
        const seen = new Set();
        return urls.filter(url => {
            if (!url || seen.has(url)) return false;
            seen.add(url);
            return true;
        });
    }

    function qrUrlsFor(point) {
        const cleanPath = normalizeUploadPath(point.qrcode_img);
        const fileName = basename(cleanPath);
        const kioskCode = extractKioskCode(point.code, point.name, point.slug, point.qrcode_img, point.address);
        const codeFileName = kioskCode ? `qrcode_${kioskCode}.png` : '';
        const urls = [];

        if (fileName) {
            urls.push(`<?= rtrim(BASE_URL, '/') ?>/uploads/gallery/${encodeURIComponent(fileName)}`);
        }

        if (codeFileName) {
            urls.push(`<?= rtrim(BASE_URL, '/') ?>/uploads/gallery/${encodeURIComponent(codeFileName)}`);
        }

        if (fileName) {
            urls.push(`<?= rtrim(BASE_URL, '/') ?>/uploads/qrcode_refillcity/${encodeURIComponent(fileName)}`);
        }

        if (codeFileName) {
            urls.push(`<?= rtrim(BASE_URL, '/') ?>/uploads/qrcode_refillcity/${encodeURIComponent(codeFileName)}`);
        }

        if (cleanPath && cleanPath.includes('/')) {
            urls.push(`<?= rtrim(BASE_URL, '/') ?>/${cleanPath.split('/').map(encodeURIComponent).join('/')}`);
        }

        return uniqueUrls(urls);
    }

    function qrImageHtml(point) {
        const urls = qrUrlsFor(point);
        if (!urls.length) {
            return '';
        }

        return `<img class="refill-popup-qr" src="${escapeHtml(urls[0])}" alt="QR ${escapeHtml(point.code || '')}" data-qr-urls='${escapeHtml(JSON.stringify(urls))}' data-qr-index="0" onerror="nextQrImage(this)">`;
    }

    function popupHtml(point) {
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(point.lat)},${encodeURIComponent(point.lng)}`;
        const detailUrl = point.slug ? `<?= BASE_URL ?>/place/${encodeURIComponent(point.slug)}` : '';
    return `
            <div class="refill-popup">
                <div class="refill-popup-head">
                    <div class="refill-popup-icon"><i class="fas fa-tint"></i></div>
                    <div>
                        <h3>${escapeHtml(point.name)}</h3>
                        <p>${point.type === 'kiosk' ? 'Water Kiosk' : 'Refill Point'}</p>
                    </div>
                </div>
                <div class="refill-popup-body">
                    ${qrImageHtml(point)}
                    <p><i class="fas fa-location-dot"></i> ${escapeHtml(point.address || '-')}</p>
                    <p><i class="fas fa-hashtag"></i> ${escapeHtml(point.code || '-')}</p>
                </div>
                <div class="refill-popup-actions">
                    ${detailUrl ? `<a href="${detailUrl}">รายละเอียด</a>` : ''}
                    <a href="${mapsUrl}" target="_blank" rel="noopener">นำทาง</a>
                </div>
            </div>
        `;
    }

    REFILL_POINTS.forEach(point => {
        const marker = L.marker([point.lat, point.lng], { icon: iconFor(point) })
            .bindPopup(popupHtml(point), { maxWidth: 260, minWidth: 240 })
            .bindTooltip(point.code || point.name, { direction: 'top', offset: [0, -34] });
        markerGroup.addLayer(marker);
        markerMap.set(String(point.id), marker);
    });

    if (REFILL_POINTS.length > 1) {
        const bounds = L.latLngBounds(REFILL_POINTS.map(point => [point.lat, point.lng]));
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
    }

    function renderList(points) {
        const list = document.getElementById('refillList');
        if (!points.length) {
            list.innerHTML = '<div class="text-center text-slate-400 py-10 text-sm font-bold">ไม่พบข้อมูล</div>';
            return;
        }
        list.innerHTML = points.map(point => `
            <button class="refill-item" data-id="${escapeHtml(point.id)}">
                <span class="refill-item-icon"><i class="fas fa-tint"></i></span>
                <span class="min-w-0">
                    <strong>${escapeHtml(point.name)}</strong>
                    <small>${escapeHtml(point.address || '-')}</small>
                </span>
            </button>
        `).join('');

        list.querySelectorAll('.refill-item').forEach(item => {
            item.addEventListener('click', () => {
                const marker = markerMap.get(String(item.dataset.id));
                const point = REFILL_POINTS.find(row => String(row.id) === String(item.dataset.id));
                if (!marker || !point) return;
                map.setView([point.lat, point.lng], 17);
                marker.openPopup();
                if (window.innerWidth < 1024) {
                    document.getElementById('refillMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }

    document.getElementById('refillSearch').addEventListener('input', event => {
        const keyword = event.target.value.trim().toLowerCase();
        const filtered = !keyword ? REFILL_POINTS : REFILL_POINTS.filter(point => {
            return String(point.name || '').toLowerCase().includes(keyword)
                || String(point.code || '').toLowerCase().includes(keyword)
                || String(point.address || '').toLowerCase().includes(keyword);
        });
        renderList(filtered);
    });

    renderList(REFILL_POINTS);
});

function escapeHtml(value) {
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function nextQrImage(img) {
    try {
        const urls = JSON.parse(img.dataset.qrUrls || '[]');
        const nextIndex = Number(img.dataset.qrIndex || 0) + 1;
        if (urls[nextIndex]) {
            img.dataset.qrIndex = String(nextIndex);
            img.src = urls[nextIndex];
            return;
        }
    } catch (error) {
        // Hide below.
    }

    img.style.display = 'none';
}
</script>
<?php endif; ?>

<style>
#refillMap { min-height: 520px; height: 100%; background: #e2e8f0; }
.refill-marker {
    width: 42px;
    height: 42px;
    border-radius: 50% 50% 50% 8px;
    transform: rotate(-45deg);
    background: linear-gradient(135deg, #06b6d4, #0088CC);
    color: white;
    border: 3px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 22px rgba(8, 145, 178, 0.35);
}
.refill-marker i { transform: rotate(45deg); font-size: 17px; }
.refill-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    background: white;
    text-align: left;
    transition: all .18s ease;
}
.refill-item:hover { border-color: #67e8f9; box-shadow: 0 8px 20px rgba(15,23,42,.08); transform: translateY(-1px); }
.refill-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    background: #ecfeff;
    color: #0891b2;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}
.refill-item strong,
.refill-item small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.refill-item strong { color: #0f172a; font-size: 13px; }
.refill-item small { color: #64748b; font-size: 11px; margin-top: 3px; }
.refill-popup { font-family: inherit; overflow: hidden; }
.refill-popup-head { display: flex; align-items: center; gap: 10px; padding: 12px; background: #ecfeff; }
.refill-popup-icon { width: 38px; height: 38px; border-radius: 12px; background: #0891b2; color: white; display:flex; align-items:center; justify-content:center; }
.refill-popup h3 { margin: 0; color: #0f172a; font-size: 14px; font-weight: 900; }
.refill-popup p { margin: 0; }
.refill-popup-head p { color: #0891b2; font-size: 11px; font-weight: 800; }
.refill-popup-body { padding: 12px; color: #475569; font-size: 12px; display: grid; gap: 6px; }
.refill-popup-body i { color: #0891b2; width: 14px; }
.refill-popup-qr { width: 112px; height: 112px; object-fit: contain; justify-self: center; border: 1px solid #e2e8f0; border-radius: 12px; padding: 6px; background: #fff; margin-bottom: 4px; }
.refill-popup-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 0 12px 12px; }
.refill-popup-actions a { text-align: center; background: #0088CC; color: white; border-radius: 10px; padding: 8px; font-size: 11px; font-weight: 900; }
.refill-popup-actions a:last-child { background: #f1f5f9; color: #334155; }
.leaflet-popup-content-wrapper { border-radius: 16px !important; overflow: hidden; }
.leaflet-popup-content { margin: 0 !important; width: 240px !important; }
</style>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
