let map, userMarker, userCircle;
let placesData = [];
let categoriesData = [];
let categoryLayerGroups = {}; // { catId: L.markerClusterGroup }
let visibleCategories = new Set(); // catIds currently shown on map
let heatLayer = null;
let isNearbyActive = false;

document.addEventListener('DOMContentLoaded', () => {
    initMap();
    loadCategories();
    loadPlaces();

    document.getElementById('searchBtn').addEventListener('click', filterPlaces);
    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        if (e.key === 'Enter') filterPlaces();
    });
    document.getElementById('categoryFilter').addEventListener('change', filterPlaces);
    document.getElementById('btnNearby').addEventListener('click', toggleNearby);
    document.getElementById('radiusSelect').addEventListener('change', filterNearby);
    document.getElementById('heatmapToggle').addEventListener('change', toggleHeatmap);
});

// ─── Cluster options from MAP_SETTINGS ───────────────────────────────────────
function buildClusterOptions() {
    const s = (typeof MAP_SETTINGS !== 'undefined') ? MAP_SETTINGS : {};
    const opts = {
        chunkedLoading: true,
        maxClusterRadius: s.max_cluster_radius || 50,
        spiderfyOnMaxZoom: s.spiderfy_on_max_zoom !== false,
        spiderfyDistanceMultiplier: 2,
        showCoverageOnHover: false
    };
    if (s.clustering_enabled === false) {
        opts.disableClusteringAtZoom = 1;
    } else {
        opts.disableClusteringAtZoom = s.disable_clustering_at_zoom || 14;
    }
    return opts;
}

// ─── Map Init ─────────────────────────────────────────────────────────────────
function initMap() {
    map = L.map('map').setView([13.9840, 100.6125], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
}

// ─── Load Data ────────────────────────────────────────────────────────────────
function loadCategories() {
    fetch(BASE_URL + '/api/categories')
        .then(res => { if (!res.ok) throw new Error('API Error'); return res.json(); })
        .then(data => {
            categoriesData = data;
            const select = document.getElementById('categoryFilter');
            data.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.name;
                select.appendChild(option);
            });
        })
        .catch(err => console.error('Failed to load categories:', err));
}

function loadPlaces() {
    fetch(BASE_URL + '/api/places')
        .then(res => { if (!res.ok) throw new Error('API Error'); return res.json(); })
        .then(data => {
            placesData = data;
            initCategoryLayers(data);
            renderPlaces(data);
        })
        .catch(err => {
            console.error('Failed to load places:', err);
            document.getElementById('placesList').innerHTML = '<div class="text-center text-red-400 py-10 text-xs">Error loading data. Check console.</div>';
        });
}

// ─── Init persistent per-category layer groups (called once on load) ─────────
function initCategoryLayers(data) {
    const catIds = [...new Set(data.map(p => String(p.category_id || 'default')))];
    catIds.forEach(catId => {
        if (!categoryLayerGroups[catId]) {
            categoryLayerGroups[catId] = L.markerClusterGroup(buildClusterOptions());
            map.addLayer(categoryLayerGroups[catId]);
            visibleCategories.add(catId);
        }
    });
}

// ─── Render markers + sidebar cards ──────────────────────────────────────────
function renderPlaces(data) {
    // Clear markers in all category layers (keep the layer groups themselves)
    Object.values(categoryLayerGroups).forEach(lg => lg.clearLayers());

    const listContainer = document.getElementById('placesList');
    listContainer.innerHTML = '';
    document.getElementById('resultCount').textContent = data.length;

    if (data.length === 0) {
        listContainer.innerHTML = '<div class="text-center text-slate-400 py-10 italic">No places found.</div>';
        return;
    }

    data.forEach(place => {
        const catId = String(place.category_id || 'default');

        // Map Marker
        if (place.latitude && place.longitude) {
            const categoryIcon = place.category_icon || 'fa-map-marker-alt';
            const categoryColor = place.category_color || '#0088CC';

            const customIcon = L.divIcon({
                html: `<div class="custom-marker" style="background-color: ${categoryColor};">
                        <i class="fas ${categoryIcon}"></i>
                       </div>`,
                className: 'custom-div-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            const popupContent = `
                <div class="marker-popup w-full font-sans overflow-hidden">
                    <div class="h-28 w-full overflow-hidden">
                        <img src="${BASE_URL}/uploads/covers/${place.cover_image || 'default.jpg'}" class="w-full h-full object-cover" alt="${place.name}">
                    </div>
                    <div class="p-3">
                        <h6 class="font-bold text-slate-800 leading-tight mb-1 text-sm line-clamp-2">${place.name}</h6>
                        <p class="text-[10px] text-primary-600 font-bold uppercase tracking-wider mb-2">${place.category_name}</p>
                        <div class="flex items-center text-yellow-500 text-xs mb-3 font-bold">
                            <i class="fas fa-star mr-1"></i> ${place.rating_avg} <span class="text-slate-400 ml-1 font-normal">(${place.rating_count})</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="${BASE_URL}/place/${place.slug}" class="bg-[#0088CC] text-white text-center py-2 rounded-xl text-[10px] font-bold hover:bg-[#006BA8] transition shadow-sm" style="color:white">View</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${place.latitude},${place.longitude}" target="_blank" class="bg-slate-100 text-slate-600 text-center py-2 rounded-xl text-[10px] font-bold hover:bg-slate-200 transition">Navigate</a>
                        </div>
                    </div>
                </div>
            `;

            const marker = L.marker([place.latitude, place.longitude], { icon: customIcon })
                            .bindPopup(popupContent, { padding: [0, 0], maxWidth: 200, minWidth: 200 });

            marker.on('mouseover', function () { this.setZIndexOffset(1000); });
            marker.on('mouseout', function () { this.setZIndexOffset(0); });

            // Create category layer group if it doesn't exist yet
            if (!categoryLayerGroups[catId]) {
                categoryLayerGroups[catId] = L.markerClusterGroup(buildClusterOptions());
                visibleCategories.add(catId);
            }
            categoryLayerGroups[catId].addLayer(marker);
        }

        // Sidebar Card
        const card = document.createElement('div');
        card.className = 'group flex bg-white border border-slate-100 rounded-3xl p-3 cursor-pointer hover:shadow-lg hover:border-navy-100 transition duration-300 transform hover:-translate-y-1';
        card.innerHTML = `
            <div class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden bg-slate-100 mr-4 border border-slate-50 shadow-inner">
                <img src="${BASE_URL}/uploads/covers/${place.cover_image || 'default.jpg'}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="${place.name}">
            </div>
            <div class="flex-1 min-w-0 flex flex-col justify-center">
                <h6 class="font-bold text-slate-800 text-sm truncate mb-0.5">${place.name}</h6>
                <p class="text-[10px] font-bold text-primary-500 mb-1 uppercase tracking-widest">${place.category_name}</p>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center text-yellow-500 text-[10px] font-black">
                        <i class="fas fa-star mr-1 text-[8px]"></i> ${place.rating_avg}
                    </div>
                    <span class="text-[9px] text-slate-400 font-bold bg-slate-50 px-2 py-0.5 rounded-full border border-slate-100">${place.views_count} views</span>
                </div>
            </div>
        `;
        card.addEventListener('click', () => {
            if (place.latitude && place.longitude) {
                map.setView([place.latitude, place.longitude], 16);

                if (window.innerWidth < 768 && typeof toggleMobileSidebar === 'function') {
                    if (isSidebarOpen) toggleMobileSidebar();
                }

                const lg = categoryLayerGroups[catId];
                if (lg) {
                    lg.eachLayer(function (m) {
                        if (m.getLatLng().lat == place.latitude && m.getLatLng().lng == place.longitude) {
                            m.openPopup();
                        }
                    });
                }
            }
        });
        listContainer.appendChild(card);
    });

    // Re-apply visibility state (respect layer panel toggles)
    Object.entries(categoryLayerGroups).forEach(([catId, lg]) => {
        if (visibleCategories.has(catId)) {
            if (!map.hasLayer(lg)) map.addLayer(lg);
        } else {
            if (map.hasLayer(lg)) map.removeLayer(lg);
        }
    });

    buildLayerPanel();
}

// ─── Layer Panel ──────────────────────────────────────────────────────────────
function buildLayerPanel() {
    const container = document.getElementById('layerPanelList');
    if (!container) return;
    container.innerHTML = '';

    const catIds = Object.keys(categoryLayerGroups);
    if (catIds.length === 0) {
        document.getElementById('layerPanelWrapper').classList.add('hidden');
        return;
    }
    document.getElementById('layerPanelWrapper').classList.remove('hidden');

    catIds.forEach(catId => {
        const cat = categoriesData.find(c => String(c.id) === catId);
        const catName = cat ? cat.name : 'อื่นๆ';
        const catColor = cat ? (cat.color || '#0088CC') : '#0088CC';
        const catIcon = cat ? (cat.icon || 'fa-map-marker-alt') : 'fa-map-marker-alt';
        const isVisible = visibleCategories.has(catId);

        const label = document.createElement('label');
        label.className = 'flex items-center gap-2.5 cursor-pointer py-1 hover:opacity-80 transition';

        const dot = document.createElement('div');
        dot.className = 'w-6 h-6 rounded-lg flex items-center justify-center shrink-0 border-2 transition-all';
        dot.style.borderColor = catColor;
        dot.style.backgroundColor = isVisible ? catColor : 'transparent';

        const ico = document.createElement('i');
        ico.className = `fas ${catIcon} text-[9px]`;
        ico.style.color = isVisible ? 'white' : catColor;
        dot.appendChild(ico);

        const nameSpan = document.createElement('span');
        nameSpan.className = 'text-xs font-medium text-slate-700 leading-tight';
        nameSpan.textContent = catName;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'sr-only';
        checkbox.checked = isVisible;
        checkbox.addEventListener('change', function () {
            const visible = this.checked;
            if (visible) {
                visibleCategories.add(catId);
                map.addLayer(categoryLayerGroups[catId]);
                dot.style.backgroundColor = catColor;
                ico.style.color = 'white';
            } else {
                visibleCategories.delete(catId);
                map.removeLayer(categoryLayerGroups[catId]);
                dot.style.backgroundColor = 'transparent';
                ico.style.color = catColor;
            }
        });

        label.appendChild(checkbox);
        label.appendChild(dot);
        label.appendChild(nameSpan);
        container.appendChild(label);
    });
}

function toggleLayerPanel() {
    const panel = document.getElementById('layerPanel');
    const chevron = document.getElementById('layerPanelChevron');
    panel.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

// ─── Filter ───────────────────────────────────────────────────────────────────
function filterPlaces() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const minRating = parseFloat(document.getElementById('ratingFilter').value);
    const sortBy = document.getElementById('sortFilter').value;

    let filtered = placesData.filter(place => {
        const matchKeyword = place.name.toLowerCase().includes(keyword) || (place.description && place.description.toLowerCase().includes(keyword));
        const matchCat = category === '' || place.category_id == category;
        const matchRating = place.rating_avg >= minRating;
        return matchKeyword && matchCat && matchRating;
    });

    if (sortBy === 'rating') filtered.sort((a, b) => b.rating_avg - a.rating_avg);
    else if (sortBy === 'views') filtered.sort((a, b) => b.views_count - a.views_count);

    renderPlaces(filtered);

    if (keyword.length > 2) {
        fetch(BASE_URL + '/api/search?q=' + encodeURIComponent(keyword));
    }
}

// ─── Heatmap ──────────────────────────────────────────────────────────────────
function toggleHeatmap(e) {
    if (e.target.checked) {
        const heatPoints = placesData.map(p => {
            if (p.latitude && p.longitude) {
                return [p.latitude, p.longitude, Math.min(1.0, (p.views_count || 200) / 1000)];
            }
            return null;
        }).filter(Boolean);

        heatLayer = L.heatLayer(heatPoints, {
            radius: 25, blur: 15, maxZoom: 15,
            gradient: { 0.4: 'blue', 0.6: 'cyan', 0.7: 'lime', 0.8: 'yellow', 1.0: 'red' }
        }).addTo(map);

        Object.entries(categoryLayerGroups).forEach(([catId, lg]) => {
            if (visibleCategories.has(catId)) map.removeLayer(lg);
        });
    } else {
        if (heatLayer) { map.removeLayer(heatLayer); heatLayer = null; }
        Object.entries(categoryLayerGroups).forEach(([catId, lg]) => {
            if (visibleCategories.has(catId)) map.addLayer(lg);
        });
    }
}

// ─── Nearby ───────────────────────────────────────────────────────────────────
function toggleNearby() {
    isNearbyActive = !isNearbyActive;
    const wrapper = document.getElementById('radiusSelectWrapper');

    if (isNearbyActive) {
        wrapper.classList.remove('hidden');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    showUserLocation(position.coords.latitude, position.coords.longitude);
                    filterNearby();
                },
                () => { alert('Error getting location. Please enable location services.'); toggleNearby(); }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
            toggleNearby();
        }
    } else {
        wrapper.classList.add('hidden');
        if (userMarker) map.removeLayer(userMarker);
        if (userCircle) map.removeLayer(userCircle);
        renderPlaces(placesData);
        map.setView([13.9840, 100.6125], 13);
    }
}

function showUserLocation(lat, lng) {
    if (userMarker) map.removeLayer(userMarker);
    if (userCircle) map.removeLayer(userCircle);
    userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        })
    }).bindPopup('<b>You are here!</b>').addTo(map);
    map.setView([lat, lng], 14);
}

function filterNearby() {
    if (!userMarker) return;
    const radiusKm = parseFloat(document.getElementById('radiusSelect').value);
    const userLat = userMarker.getLatLng().lat;
    const userLng = userMarker.getLatLng().lng;

    if (userCircle) map.removeLayer(userCircle);
    userCircle = L.circle([userLat, userLng], {
        color: 'red', fillColor: '#f03', fillOpacity: 0.1, radius: radiusKm * 1000
    }).addTo(map);

    const filtered = placesData
        .filter(p => p.latitude && p.longitude && getDistanceFromLatLonInKm(userLat, userLng, p.latitude, p.longitude) <= radiusKm)
        .sort((a, b) => getDistanceFromLatLonInKm(userLat, userLng, a.latitude, a.longitude)
                      - getDistanceFromLatLonInKm(userLat, userLng, b.latitude, b.longitude));

    renderPlaces(filtered);
    map.fitBounds(userCircle.getBounds());
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function deg2rad(deg) { return deg * (Math.PI / 180); }
