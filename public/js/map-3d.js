import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

let map3d;
let map3dPlaces = [];
let map3dMarkers = [];
let map3dPopup = null;
let map3dAutoRotateFrame = null;
let map3dAutoRotateEnabled = false;
let map3dHasRendered = false;
let cityHallLayer = null;
let cityHallVisible = true;
let cityHallDebugMarker = null;

const RANGSIT_CENTER = [100.6125, 13.9840];
const CITY_HALL_STORAGE_KEY = 'discoverRangsitCityHallModel';
const CITY_HALL_BASE_ORIGIN = [100.60965695117441, 13.987025197824837];
const CITY_HALL_DEFAULTS = {
    east: -15,
    north: -60,
    altitude: 0,
    rotateZ: Math.PI,
    scale: 125
};
const cityHallState = loadCityHallState();
const CITY_HALL_MODEL = {
    id: 'rangsit-city-hall-model',
    url: `${BASE_URL}/assets/3d/Rangsicity_building.glb`,
    origin: () => offsetLngLat(CITY_HALL_BASE_ORIGIN, cityHallState.east, cityHallState.north),
    altitude: () => cityHallState.altitude,
    rotateZ: () => cityHallState.rotateZ,
    scale: () => cityHallState.scale
};

function offsetLngLat(origin, eastMeters, northMeters) {
    const metersPerDegreeLat = 111320;
    const lat = origin[1] + (northMeters / metersPerDegreeLat);
    const lng = origin[0] + (eastMeters / (metersPerDegreeLat * Math.cos(origin[1] * Math.PI / 180)));
    return [lng, lat];
}

function loadCityHallState() {
    try {
        const saved = JSON.parse(localStorage.getItem(CITY_HALL_STORAGE_KEY) || '{}');
        return { ...CITY_HALL_DEFAULTS, ...saved };
    } catch (error) {
        return { ...CITY_HALL_DEFAULTS };
    }
}

function saveCityHallState() {
    localStorage.setItem(CITY_HALL_STORAGE_KEY, JSON.stringify(cityHallState));
}

document.addEventListener('DOMContentLoaded', () => {
    renderFallbackScene();
    set3dStatus('Starting 3D map...');

    if (!window.maplibregl) {
        set3dStatus('MapLibre GL failed to load. Please check CDN/network access.');
        return;
    }

    const isSupported = typeof maplibregl.supported === 'function'
        ? maplibregl.supported()
        : browserSupportsWebGL();

    if (!isSupported) {
        set3dStatus('This device does not support WebGL for 3D map.');
        return;
    }

    try {
        init3dMap();
        bind3dControls();
    } catch (error) {
        console.error(error);
        set3dStatus(`3D map init failed: ${error.message}`);
        return;
    }

    load3dPlaces();
});

function init3dMap() {
    const style = {
        version: 8,
        sources: {
            osm: {
                type: 'raster',
                tiles: [
                    'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    'https://b.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    'https://c.tile.openstreetmap.org/{z}/{x}/{y}.png'
                ],
                tileSize: 256,
                attribution: '&copy; OpenStreetMap contributors'
            }
        },
        layers: [
            {
                id: 'osm',
                type: 'raster',
                source: 'osm'
            }
        ]
    };

    map3d = new maplibregl.Map({
        container: 'map3d',
        center: RANGSIT_CENTER,
        zoom: 14.8,
        pitch: 62,
        bearing: -24,
        antialias: true,
        style
    });

    map3d.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'bottom-left');

    map3d.on('load', () => {
        map3dHasRendered = true;
        hideFallbackScene();
        try {
            addDemoBuildings();
            addCityHallModel();
            if (map3dPlaces.length) addHeatmapSource(map3dPlaces);
        } catch (error) {
            console.error(error);
            set3dStatus(`3D layer failed: ${error.message}`);
            return;
        }
        set3dStatus('3D map ready');
    });

    map3d.on('render', () => {
        map3dHasRendered = true;
    });

    map3d.on('idle', () => {
        if (map3dHasRendered) {
            set3dStatus('3D map ready');
        }
    });

    map3d.on('error', (event) => {
        const message = event && event.error ? event.error.message : 'Unknown map error';
        console.error(event);
        set3dStatus(`Map error: ${message}`);
    });

    window.setTimeout(() => {
        if (map3d && !map3dHasRendered && !is3dStyleReady()) {
            set3dStatus('Map is still loading. Check console/network for blocked tiles or CDN files.');
        }
    }, 6000);
}

function is3dStyleReady() {
    try {
        return !!(map3d && map3d.isStyleLoaded && map3d.isStyleLoaded());
    } catch (error) {
        return false;
    }
}

function bind3dControls() {
    document.getElementById('map3dSearchBtn').addEventListener('click', filter3dPlaces);
    document.getElementById('map3dSearch').addEventListener('keyup', (event) => {
        if (event.key === 'Enter') filter3dPlaces();
    });
    document.getElementById('map3dReset').addEventListener('click', reset3dCamera);
    document.getElementById('map3dRotateLeft').addEventListener('click', () => rotate3dCamera(-22));
    document.getElementById('map3dRotateRight').addEventListener('click', () => rotate3dCamera(22));
    document.getElementById('map3dTiltUp').addEventListener('click', () => tilt3dCamera(8));
    document.getElementById('map3dTiltDown').addEventListener('click', () => tilt3dCamera(-8));
    document.getElementById('map3dZoomIn').addEventListener('click', () => zoom3dCamera(0.8));
    document.getElementById('map3dZoomOut').addEventListener('click', () => zoom3dCamera(-0.8));
    document.getElementById('map3dAutoRotate').addEventListener('click', toggle3dAutoRotate);
    document.getElementById('map3dFocusCityHall').addEventListener('click', focusCityHallModel);
    bindCityHallSettings();
    document.getElementById('layer3dPlaces').addEventListener('change', (event) => {
        setMarkersVisible(event.target.checked);
    });
    document.getElementById('layer3dBuildings').addEventListener('change', (event) => {
        setLayerVisibility('demo-buildings-3d', event.target.checked);
    });
    document.getElementById('layer3dCityHall').addEventListener('change', (event) => {
        cityHallVisible = event.target.checked;
        if (map3d) map3d.triggerRepaint();
    });
    document.getElementById('layer3dHeatmap').addEventListener('change', (event) => {
        setLayerVisibility('places-heat-3d', event.target.checked);
    });
    document.getElementById('map3dListToggle').addEventListener('click', () => {
        document.getElementById('map3dList').classList.toggle('hidden');
    });

    const controlsPanel = document.getElementById('map3dControlsPanel');
    const controlsToggle = document.getElementById('map3dControlsToggle');
    const controlsClose = document.getElementById('map3dControlsClose');

    if (controlsPanel && controlsToggle) {
        controlsToggle.addEventListener('click', () => {
            const isOpen = controlsPanel.classList.toggle('is-open');
            controlsToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    if (controlsPanel && controlsToggle && controlsClose) {
        controlsClose.addEventListener('click', () => {
            controlsPanel.classList.remove('is-open');
            controlsToggle.setAttribute('aria-expanded', 'false');
        });
    }

    const mapContainer = document.getElementById('map3d');
    if (mapContainer) {
        mapContainer.addEventListener('click', handleMarkerHit, true);
        mapContainer.addEventListener('pointerup', handleMarkerHit, true);
        mapContainer.addEventListener('touchend', handleMarkerHit, { capture: true, passive: false });
    }

    if (map3d) {
        ['dragstart', 'rotatestart', 'pitchstart', 'zoomstart'].forEach(eventName => {
            map3d.on(eventName, stop3dAutoRotate);
        });
    }
}

function rotate3dCamera(delta) {
    if (!map3d) return;
    stop3dAutoRotate();
    map3d.easeTo({
        bearing: map3d.getBearing() + delta,
        duration: 450,
        easing: easeOutCubic
    });
}

function tilt3dCamera(delta) {
    if (!map3d) return;
    stop3dAutoRotate();
    map3d.easeTo({
        pitch: clamp(map3d.getPitch() + delta, 25, 78),
        duration: 450,
        easing: easeOutCubic
    });
}

function zoom3dCamera(delta) {
    if (!map3d) return;
    stop3dAutoRotate();
    map3d.easeTo({
        zoom: clamp(map3d.getZoom() + delta, 12, 18.5),
        duration: 450,
        easing: easeOutCubic
    });
}

function reset3dCamera() {
    if (!map3d) return;
    stop3dAutoRotate();
    map3d.easeTo({ center: RANGSIT_CENTER, zoom: 14.8, pitch: 62, bearing: -24, duration: 900 });
}

function focusCityHallModel() {
    if (!map3d) return;
    stop3dAutoRotate();
    set3dStatus('Focusing City Hall model...');
    map3d.flyTo({
        center: CITY_HALL_MODEL.origin(),
        zoom: 18,
        pitch: 72,
        bearing: -32,
        speed: 0.65,
        curve: 1.4,
        essential: true
    });
}

function bindCityHallSettings() {
    document.getElementById('model3dScaleDown').addEventListener('click', () => updateCityHallSetting({ scale: Math.max(10, cityHallState.scale - 25) }));
    document.getElementById('model3dScaleUp').addEventListener('click', () => updateCityHallSetting({ scale: cityHallState.scale + 25 }));
    document.getElementById('model3dRotateLeft').addEventListener('click', () => updateCityHallSetting({ rotateZ: cityHallState.rotateZ - Math.PI / 12 }));
    document.getElementById('model3dRotateRight').addEventListener('click', () => updateCityHallSetting({ rotateZ: cityHallState.rotateZ + Math.PI / 12 }));
    document.getElementById('model3dMoveForward').addEventListener('click', () => moveCityHallRelative(5, 0));
    document.getElementById('model3dMoveBack').addEventListener('click', () => moveCityHallRelative(-5, 0));
    document.getElementById('model3dMoveLeft').addEventListener('click', () => moveCityHallRelative(0, -5));
    document.getElementById('model3dMoveRight').addEventListener('click', () => moveCityHallRelative(0, 5));
    document.getElementById('model3dReset').addEventListener('click', () => {
        Object.assign(cityHallState, CITY_HALL_DEFAULTS);
        applyCityHallSettings(true);
    });
    renderCityHallSettingValues();
}

function moveCityHallRelative(forwardMeters, rightMeters) {
    const heading = cityHallState.rotateZ;
    const east = Math.sin(heading) * forwardMeters + Math.sin(heading + Math.PI / 2) * rightMeters;
    const north = Math.cos(heading) * forwardMeters + Math.cos(heading + Math.PI / 2) * rightMeters;
    updateCityHallSetting({
        east: cityHallState.east + east,
        north: cityHallState.north + north
    });
}

function updateCityHallSetting(next) {
    Object.assign(cityHallState, next);
    applyCityHallSettings(false);
}

function applyCityHallSettings(shouldFocus) {
    saveCityHallState();
    renderCityHallSettingValues();
    updateCityHallDebugMarker();
    if (map3d) map3d.triggerRepaint();
    if (shouldFocus) focusCityHallModel();
}

function renderCityHallSettingValues() {
    const scale = document.getElementById('model3dScaleValue');
    const rotate = document.getElementById('model3dRotateValue');
    const offset = document.getElementById('model3dOffsetValue');

    if (scale) scale.textContent = String(Math.round(cityHallState.scale));
    if (rotate) rotate.textContent = `${Math.round(radToDeg(cityHallState.rotateZ))}deg`;
    if (offset) offset.textContent = `${Math.round(cityHallState.east)}, ${Math.round(cityHallState.north)}m`;
}

function radToDeg(value) {
    return value * 180 / Math.PI;
}

function toggle3dAutoRotate() {
    if (!map3d) return;

    if (map3dAutoRotateEnabled) {
        stop3dAutoRotate();
        return;
    }

    map3dAutoRotateEnabled = true;
    updateAutoRotateButton();
    set3dStatus('Auto rotate on');
    run3dAutoRotate();
}

function run3dAutoRotate() {
    if (!map3d || !map3dAutoRotateEnabled) return;

    map3d.rotateTo(map3d.getBearing() + 0.12, { duration: 0 });
    map3dAutoRotateFrame = requestAnimationFrame(run3dAutoRotate);
}

function stop3dAutoRotate() {
    if (map3dAutoRotateFrame) {
        cancelAnimationFrame(map3dAutoRotateFrame);
        map3dAutoRotateFrame = null;
    }

    if (map3dAutoRotateEnabled) {
        map3dAutoRotateEnabled = false;
        updateAutoRotateButton();
        set3dStatus('Auto rotate off');
    }
}

function updateAutoRotateButton() {
    const button = document.getElementById('map3dAutoRotate');
    if (!button) return;

    button.classList.toggle('is-active', map3dAutoRotateEnabled);
    button.setAttribute('aria-pressed', map3dAutoRotateEnabled ? 'true' : 'false');
    button.innerHTML = map3dAutoRotateEnabled
        ? '<i class="fas fa-pause"></i><span>Stop Rotate</span>'
        : '<i class="fas fa-play"></i><span>Auto Rotate</span>';
}

function clamp(value, min, max) {
    return Math.max(min, Math.min(max, value));
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}

async function load3dPlaces() {
    try {
        const response = await fetch(BASE_URL + '/api/places');
        if (!response.ok) throw new Error(`Places API returned ${response.status}`);
        const places = await response.json();
        map3dPlaces = places.filter(place => place.latitude && place.longitude);
        render3dPlaces(map3dPlaces);
        if (map3d && map3d.loaded()) addHeatmapSource(map3dPlaces);
        set3dStatus(`Loaded ${map3dPlaces.length} places`);
    } catch (error) {
        console.error(error);
        set3dStatus(`Places API failed: ${error.message}`);
    }
}

function filter3dPlaces() {
    const keyword = document.getElementById('map3dSearch').value.trim().toLowerCase();
    const filtered = !keyword ? map3dPlaces : map3dPlaces.filter(place => {
        return String(place.name || '').toLowerCase().includes(keyword)
            || String(place.category_name || '').toLowerCase().includes(keyword)
            || String(place.address || '').toLowerCase().includes(keyword);
    });
    render3dPlaces(filtered);
}

function render3dPlaces(places) {
    clear3dMarkers();
    document.getElementById('map3dCount').textContent = places.length;
    render3dList(places);
    renderFallbackPlaces(places);

    if (!map3d) return;

    places.forEach(place => {
        const markerEl = document.createElement('div');
        markerEl.className = 'map3d-marker-hit';
        markerEl.dataset.placeId = String(place.id);
        markerEl.title = place.name;
        markerEl.innerHTML = `
            <button type="button" class="map3d-marker" style="--marker-color: ${place.category_color || '#0088CC'}" aria-label="${escapeHtml(place.name || '')}">
                <i class="fas ${place.category_icon || 'fa-map-marker-alt'}"></i>
            </button>
        `;

        markerEl.addEventListener('mouseenter', () => show3dTooltip(place));
        markerEl.addEventListener('mouseleave', () => close3dPopup(false));

        const activateMarker = (event) => {
            event.preventDefault();
            event.stopPropagation();
            open3dPlace(place);
        };

        markerEl.addEventListener('click', activateMarker);
        markerEl.addEventListener('pointerup', activateMarker);
        markerEl.addEventListener('touchend', activateMarker, { passive: false });

        const marker = new maplibregl.Marker({ element: markerEl, anchor: 'bottom', clickTolerance: 8 })
            .setLngLat([Number(place.longitude), Number(place.latitude)])
            .addTo(map3d);

        map3dMarkers.push(marker);
    });
}

function handleMarkerHit(event) {
    const target = event.target && event.target.closest ? event.target.closest('.map3d-marker-hit') : null;
    if (!target) return;

    event.preventDefault();
    event.stopPropagation();

    const place = map3dPlaces.find(item => String(item.id) === String(target.dataset.placeId));
    if (place) open3dPlace(place);
}

function render3dList(places) {
    const list = document.getElementById('map3dList');
    if (!places.length) {
        list.innerHTML = '<div class="py-8 text-center text-sm font-bold text-slate-400">No places found.</div>';
        return;
    }

    list.innerHTML = places.map(place => `
        <button class="map3d-place-card" data-place-id="${place.id}">
            <img src="${getCoverUrl(place)}" alt="${escapeHtml(place.name || '')}" onerror="this.src='${BASE_URL}/images/rangsit-logo.png'">
            <span>
                <strong>${escapeHtml(place.name || '')}</strong>
                <small>${escapeHtml(place.category_name || '')}</small>
            </span>
        </button>
    `).join('');

    list.querySelectorAll('.map3d-place-card').forEach(card => {
        card.addEventListener('click', () => {
            const place = places.find(item => String(item.id) === String(card.dataset.placeId));
            if (place) open3dPlace(place);
        });
    });
}

function open3dPlace(place) {
    if (!map3d) return;
    const lngLat = [Number(place.longitude), Number(place.latitude)];
    closeAll3dPopups();

    if (map3dPopup) map3dPopup.remove();
    map3dPopup = new maplibregl.Popup({ offset: 28, maxWidth: '240px' })
        .setLngLat(lngLat)
        .setHTML(build3dPopupHtml(place))
        .addTo(map3d);

    map3d.flyTo({ center: lngLat, zoom: 17, pitch: 66, bearing: -20, speed: 0.8 });
}

function show3dTooltip(place) {
    if (!map3d || window.innerWidth < 768) return;

    close3dPopup(false);
    map3dPopup = new maplibregl.Popup({
        closeButton: false,
        closeOnClick: false,
        offset: 24,
        maxWidth: '220px',
        className: 'map3d-name-tooltip'
    })
        .setLngLat([Number(place.longitude), Number(place.latitude)])
        .setHTML(`<div class="map3d-tooltip-name">${escapeHtml(place.name || '')}</div>`)
        .addTo(map3d);
}

function close3dPopup(force) {
    if (!map3dPopup) return;
    if (!force && map3dPopup.options && map3dPopup.options.className !== 'map3d-name-tooltip') return;
    map3dPopup.remove();
    map3dPopup = null;
}

function closeAll3dPopups() {
    if (map3dPopup) {
        map3dPopup.remove();
        map3dPopup = null;
    }

    map3dMarkers.forEach(marker => {
        const popup = marker.getPopup ? marker.getPopup() : null;
        if (popup && popup.isOpen && popup.isOpen()) {
            popup.remove();
        }
    });

}

function build3dPopupHtml(place) {
    const name = escapeHtml(place.name || '');
    const category = escapeHtml(place.category_name || '');
    const cover = getCoverUrl(place);
    const slug = encodeURIComponent(place.slug || '');
    const lat = encodeURIComponent(place.latitude || '');
    const lng = encodeURIComponent(place.longitude || '');
    const rating = escapeHtml(place.rating_avg || '0');
    const ratingCount = escapeHtml(place.rating_count || '0');

    return `
        <div class="map3d-popup">
            <img src="${cover}" alt="${name}" onerror="this.src='${BASE_URL}/images/rangsit-logo.png'">
            <div>
                <h3>${name}</h3>
                <p>${category}</p>
                <div class="map3d-popup-rating">
                    <i class="fas fa-star"></i>
                    <strong>${rating}</strong>
                    <span>(${ratingCount})</span>
                </div>
                <div class="map3d-popup-actions">
                    <a href="${BASE_URL}/place/${slug}">View</a>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank" rel="noopener">Navigate</a>
                </div>
            </div>
        </div>
    `;
}

function getCoverUrl(place) {
    if (!place || !place.cover_image || place.cover_image === 'default.jpg') {
        return `${BASE_URL}/images/rangsit-logo.png`;
    }
    return `${BASE_URL}/uploads/covers/${encodeURIComponent(place.cover_image)}`;
}

function clear3dMarkers() {
    map3dMarkers.forEach(marker => marker.remove());
    map3dMarkers = [];
}

function setMarkersVisible(visible) {
    map3dMarkers.forEach(marker => {
        marker.getElement().style.display = visible ? '' : 'none';
    });
}

function addHeatmapSource(places) {
    if (!map3d || !map3d.loaded()) return;

    const heatData = {
        type: 'FeatureCollection',
        features: places.map(place => ({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [Number(place.longitude), Number(place.latitude)]
            },
            properties: {
                weight: Math.max(1, Number(place.views_count || 1))
            }
        }))
    };

    if (map3d.getSource('places-heat')) {
        map3d.getSource('places-heat').setData(heatData);
        return;
    }

    map3d.addSource('places-heat', { type: 'geojson', data: heatData });
    map3d.addLayer({
        id: 'places-heat-3d',
        type: 'heatmap',
        source: 'places-heat',
        layout: { visibility: 'none' },
        paint: {
            'heatmap-weight': ['interpolate', ['linear'], ['get', 'weight'], 1, 0.25, 5000, 1],
            'heatmap-intensity': ['interpolate', ['linear'], ['zoom'], 12, 0.6, 17, 1.6],
            'heatmap-radius': ['interpolate', ['linear'], ['zoom'], 12, 18, 17, 42],
            'heatmap-opacity': 0.72,
            'heatmap-color': [
                'interpolate',
                ['linear'],
                ['heatmap-density'],
                0, 'rgba(0, 136, 204, 0)',
                0.25, 'rgba(56, 189, 248, 0.55)',
                0.5, 'rgba(34, 197, 94, 0.65)',
                0.75, 'rgba(250, 204, 21, 0.72)',
                1, 'rgba(239, 68, 68, 0.82)'
            ]
        }
    });
}

function addDemoBuildings() {
    if (!map3d || map3d.getSource('demo-buildings')) return;

    const features = [];
    const baseLng = RANGSIT_CENTER[0];
    const baseLat = RANGSIT_CENTER[1];
    let index = 0;

    for (let row = -5; row <= 5; row += 1) {
        for (let col = -6; col <= 6; col += 1) {
            if ((row + col) % 3 === 0) continue;
            const lng = baseLng + col * 0.00115;
            const lat = baseLat + row * 0.00085;
            const width = 0.00028 + ((index % 3) * 0.00007);
            const depth = 0.00022 + ((index % 4) * 0.00005);
            const height = 8 + ((index * 7) % 46);
            features.push(rectFeature(lng, lat, width, depth, height, index));
            index += 1;
        }
    }

    map3d.addSource('demo-buildings', {
        type: 'geojson',
        data: { type: 'FeatureCollection', features }
    });

    map3d.addLayer({
        id: 'demo-buildings-3d',
        type: 'fill-extrusion',
        source: 'demo-buildings',
        paint: {
            'fill-extrusion-color': ['case', ['get', 'civic'], '#dbeafe', '#f8fafc'],
            'fill-extrusion-height': ['get', 'height'],
            'fill-extrusion-base': 0,
            'fill-extrusion-opacity': 0.88,
            'fill-extrusion-vertical-gradient': true
        }
    });
}

function rectFeature(lng, lat, width, depth, height, index) {
    return {
        type: 'Feature',
        geometry: {
            type: 'Polygon',
            coordinates: [[
                [lng - width, lat - depth],
                [lng + width, lat - depth],
                [lng + width, lat + depth],
                [lng - width, lat + depth],
                [lng - width, lat - depth]
            ]]
        },
        properties: {
            height,
            civic: index % 17 === 0
        }
    };
}

function addCityHallModel() {
    if (!map3d || map3d.getLayer(CITY_HALL_MODEL.id)) return;

    addCityHallDebugMarker();
    cityHallLayer = createCityHallLayer();
    map3d.addLayer(cityHallLayer);
}

function addCityHallDebugMarker() {
    const markerEl = document.createElement('div');
    markerEl.className = 'map3d-cityhall-debug';
    markerEl.innerHTML = '<i class="fas fa-building"></i><span>ตึกเทศบาล</span>';
    markerEl.title = 'City Hall model origin';

    cityHallDebugMarker = new maplibregl.Marker({ element: markerEl, anchor: 'bottom' })
        .setLngLat(CITY_HALL_MODEL.origin())
        .addTo(map3d);
}

function updateCityHallDebugMarker() {
    if (cityHallDebugMarker) {
        cityHallDebugMarker.setLngLat(CITY_HALL_MODEL.origin());
    }
}

function createCityHallLayer() {
    return {
        id: CITY_HALL_MODEL.id,
        type: 'custom',
        renderingMode: '3d',
        onAdd(map, gl) {
            set3dStatus('Loading City Hall model...');
            this.camera = new THREE.Camera();
            this.scene = new THREE.Scene();
            this.map = map;
            this.renderer = new THREE.WebGLRenderer({
                canvas: map.getCanvas(),
                context: gl,
                antialias: true
            });
            this.renderer.autoClear = false;

            this.scene.add(new THREE.AmbientLight(0xffffff, 1.1));

            const sun = new THREE.DirectionalLight(0xffffff, 1.4);
            sun.position.set(120, -80, 160);
            this.scene.add(sun);

            const loader = new GLTFLoader();
            loader.load(
                CITY_HALL_MODEL.url,
                gltf => {
                    this.model = gltf.scene;
                    const box = new THREE.Box3().setFromObject(this.model);
                    const size = box.getSize(new THREE.Vector3());
                    const center = box.getCenter(new THREE.Vector3());
                    this.model.position.sub(center);
                    this.model.traverse(child => {
                        if (child.isMesh) {
                            child.castShadow = false;
                            child.receiveShadow = true;
                            child.frustumCulled = false;
                        }
                    });
                    this.scene.add(this.model);
                    set3dStatus(`City Hall model loaded (${size.x.toFixed(1)} x ${size.y.toFixed(1)} x ${size.z.toFixed(1)})`);
                    map.triggerRepaint();
                },
                undefined,
                error => {
                    console.error(error);
                    set3dStatus(`City Hall model failed: ${error.message}`);
                }
            );
        },
        render(gl, matrix) {
            if (!cityHallVisible) return;

            const modelTransform = getCityHallModelTransform();
            const rotationX = new THREE.Matrix4().makeRotationAxis(
                new THREE.Vector3(1, 0, 0),
                modelTransform.rotateX
            );
            const rotationY = new THREE.Matrix4().makeRotationAxis(
                new THREE.Vector3(0, 1, 0),
                modelTransform.rotateY
            );
            const rotationZ = new THREE.Matrix4().makeRotationAxis(
                new THREE.Vector3(0, 0, 1),
                modelTransform.rotateZ
            );
            const mapMatrix = new THREE.Matrix4().fromArray(matrix);
            const modelMatrix = new THREE.Matrix4()
                .makeTranslation(
                    modelTransform.translateX,
                    modelTransform.translateY,
                    modelTransform.translateZ
                )
                .scale(new THREE.Vector3(
                    modelTransform.scale,
                    -modelTransform.scale,
                    modelTransform.scale
                ))
                .multiply(rotationX)
                .multiply(rotationY)
                .multiply(rotationZ);

            this.camera.projectionMatrix = mapMatrix.multiply(modelMatrix);
            this.renderer.resetState();
            this.renderer.render(this.scene, this.camera);
            this.map.triggerRepaint();
        }
    };
}

function getCityHallModelTransform() {
    const mercator = maplibregl.MercatorCoordinate.fromLngLat(
        CITY_HALL_MODEL.origin(),
        CITY_HALL_MODEL.altitude()
    );
    const meterScale = mercator.meterInMercatorCoordinateUnits();
    return {
        translateX: mercator.x,
        translateY: mercator.y,
        translateZ: mercator.z,
        rotateX: -Math.PI / 2,
        rotateY: 0,
        rotateZ: CITY_HALL_MODEL.rotateZ(),
        scale: meterScale * CITY_HALL_MODEL.scale()
    };
}

function setLayerVisibility(layerId, visible) {
    if (!map3d || !map3d.getLayer(layerId)) return;
    map3d.setLayoutProperty(layerId, 'visibility', visible ? 'visible' : 'none');
}

function set3dStatus(message) {
    const status = document.getElementById('map3dStatus');
    if (status) status.textContent = message;
}

function browserSupportsWebGL() {
    try {
        const canvas = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (
            canvas.getContext('webgl') || canvas.getContext('experimental-webgl')
        ));
    } catch (error) {
        return false;
    }
}

function renderFallbackScene() {
    const fallback = document.getElementById('map3dFallback');
    if (!fallback) return;

    const blocks = [];
    for (let i = 0; i < 80; i += 1) {
        const x = 8 + (i % 10) * 8.8;
        const y = 16 + Math.floor(i / 10) * 9.5;
        const h = 24 + ((i * 11) % 78);
        if (i % 7 === 0) continue;
        blocks.push(`<span class="map3d-fallback-building" style="left:${x}%; top:${y}%; height:${h}px;"></span>`);
    }

    fallback.innerHTML = `
        <div class="map3d-fallback-world">
            <div class="map3d-fallback-grid"></div>
            <div class="map3d-fallback-water"></div>
            ${blocks.join('')}
            <div id="map3dFallbackPlaces"></div>
        </div>
    `;
}

function renderFallbackPlaces(places) {
    const container = document.getElementById('map3dFallbackPlaces');
    if (!container) return;

    const visiblePlaces = places.slice(0, 24);
    container.innerHTML = visiblePlaces.map((place, index) => {
        const x = 12 + (index % 8) * 9.5;
        const y = 24 + Math.floor(index / 8) * 14;
        return `<button class="map3d-fallback-pin" style="left:${x}%; top:${y}%;" title="${escapeHtml(place.name || '')}" data-place-id="${place.id}"></button>`;
    }).join('');

    container.querySelectorAll('.map3d-fallback-pin').forEach(pin => {
        pin.addEventListener('click', () => {
            const place = places.find(item => String(item.id) === String(pin.dataset.placeId));
            if (place) open3dPlace(place);
        });
    });
}

function hideFallbackScene() {
    const fallback = document.getElementById('map3dFallback');
    if (fallback) fallback.classList.add('is-hidden');
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
