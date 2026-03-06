let map, markers, userMarker, userCircle;
let placesData = [];
let categoriesData = [];

document.addEventListener('DOMContentLoaded', () => {
    initMap();
    loadCategories();
    loadPlaces();

    // Event Listeners
    document.getElementById('searchBtn').addEventListener('click', filterPlaces);
    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        if(e.key === 'Enter') filterPlaces();
    });
    document.getElementById('categoryFilter').addEventListener('change', filterPlaces);
    
    // Geolocation
    document.getElementById('btnNearby').addEventListener('click', toggleNearby);
    document.getElementById('radiusSelect').addEventListener('change', filterNearby);
});

function initMap() {
    // Center of Rangsit, Thailand approximately
    map = L.map('map').setView([13.9840, 100.6125], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    markers = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50
    });
    
    map.addLayer(markers);
}

function loadCategories() {
    fetch(BASE_URL + '/api/categories')
        .then(res => {
            if (!res.ok) throw new Error('API Error');
            return res.json();
        })
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
        .then(res => {
            if (!res.ok) throw new Error('API Error');
            return res.json();
        })
        .then(data => {
            placesData = data;
            renderPlaces(data);
        })
        .catch(err => {
            console.error('Failed to load places:', err);
            document.getElementById('placesList').innerHTML = '<div class="text-center text-red-400 py-10 text-xs">Error loading data. Check console.</div>';
        });
}

function renderPlaces(data) {
    markers.clearLayers();
    const listContainer = document.getElementById('placesList');
    listContainer.innerHTML = '';
    document.getElementById('resultCount').textContent = data.length;

    if(data.length === 0) {
        listContainer.innerHTML = '<div class="text-center text-slate-400 py-10 italic">No places found.</div>';
        return;
    }

    data.forEach(place => {
        // Map Marker
        if(place.latitude && place.longitude) {
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
                            <a href="${BASE_URL}/place/${place.slug}" class="bg-navy-800 text-white text-center py-2 rounded-xl text-[10px] font-bold hover:bg-navy-900 transition shadow-sm">View</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${place.latitude},${place.longitude}" target="_blank" class="bg-slate-100 text-slate-600 text-center py-2 rounded-xl text-[10px] font-bold hover:bg-slate-200 transition">Navigate</a>
                        </div>
                    </div>
                </div>
            `;
            const marker = L.marker([place.latitude, place.longitude])
                            .bindPopup(popupContent, { padding: [0, 0], maxWidth: 200, minWidth: 200 });
            markers.addLayer(marker);
        }

        // Sidebar List Card
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
            if(place.latitude && place.longitude) {
                map.setView([place.latitude, place.longitude], 16);
                
                // Close mobile sidebar automatically if open
                if (window.innerWidth < 768 && typeof toggleMobileSidebar === 'function') {
                    if (isSidebarOpen) {
                        toggleMobileSidebar();
                    }
                }

                // Find marker and open popup
                markers.eachLayer(function(marker) {
                    if(marker.getLatLng().lat == place.latitude && marker.getLatLng().lng == place.longitude) {
                        marker.openPopup();
                    }
                });
            }
        });
        listContainer.appendChild(card);
    });
}

function filterPlaces() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const minRating = parseFloat(document.getElementById('ratingFilter').value);
    const sortBy = document.getElementById('sortFilter').value;

    let filtered = placesData.filter(place => {
        const matchKeyword = place.name.toLowerCase().includes(keyword) || (place.description && place.description.toLowerCase().includes(keyword));
        const matchCat = category === "" || place.category_id == category;
        const matchRating = place.rating_avg >= minRating;
        return matchKeyword && matchCat && matchRating;
    });

    // Sorting
    if (sortBy === 'rating') {
        filtered.sort((a, b) => b.rating_avg - a.rating_avg);
    } else if (sortBy === 'views') {
        filtered.sort((a, b) => b.views_count - a.views_count);
    }

    renderPlaces(filtered);

    // If searching keyword, send analytics log
    if (keyword.length > 2) {
        fetch(BASE_URL + '/api/search?q=' + encodeURIComponent(keyword));
    }
}

// Nearby Geolocation Feature
let isNearbyActive = false;
let heatLayer = null;

document.addEventListener('DOMContentLoaded', () => {
    // ... previous listeners
    document.getElementById('heatmapToggle').addEventListener('change', toggleHeatmap);
});

function toggleHeatmap(e) {
    if(e.target.checked) {
        // Create heat points: lat, lng, intensity
        const heatPoints = placesData.map(p => {
            if(p.latitude && p.longitude) {
                // calculate intensity based on views
                let intensity = p.views_count ? Math.min(1.0, p.views_count / 1000) : 0.2;
                return [p.latitude, p.longitude, intensity];
            }
            return null;
        }).filter(p => p !== null);

        heatLayer = L.heatLayer(heatPoints, {
            radius: 25,
            blur: 15,
            maxZoom: 15,
            gradient: {0.4: 'blue', 0.6: 'cyan', 0.7: 'lime', 0.8: 'yellow', 1.0: 'red'}
        }).addTo(map);
        
        map.removeLayer(markers);
    } else {
        if(heatLayer) {
            map.removeLayer(heatLayer);
            heatLayer = null;
        }
        map.addLayer(markers);
    }
}

function toggleNearby() {
    isNearbyActive = !isNearbyActive;
    const btn = document.getElementById('btnNearby');
    const wrapper = document.getElementById('radiusSelectWrapper');

    if(isNearbyActive) {
        btn.classList.replace('btn-outline-success', 'btn-success');
        wrapper.classList.remove('d-none');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    showUserLocation(lat, lng);
                    filterNearby();
                },
                error => {
                    alert('Error getting location. Please enable location services.');
                    toggleNearby();
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
            toggleNearby();
        }
    } else {
        btn.classList.replace('btn-success', 'btn-outline-success');
        wrapper.classList.add('d-none');
        if(userMarker) map.removeLayer(userMarker);
        if(userCircle) map.removeLayer(userCircle);
        renderPlaces(placesData);
        map.setView([13.9840, 100.6125], 13);
    }
}

function showUserLocation(lat, lng) {
    if(userMarker) map.removeLayer(userMarker);
    if(userCircle) map.removeLayer(userCircle);

    userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).bindPopup('<b>You are here!</b>').addTo(map);
    map.setView([lat, lng], 14);
}

function filterNearby() {
    if(!userMarker) return;
    const radiusKm = parseFloat(document.getElementById('radiusSelect').value);
    const userLat = userMarker.getLatLng().lat;
    const userLng = userMarker.getLatLng().lng;

    if(userCircle) map.removeLayer(userCircle);
    userCircle = L.circle([userLat, userLng], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.1,
        radius: radiusKm * 1000
    }).addTo(map);

    const filtered = placesData.filter(place => {
        if(!place.latitude || !place.longitude) return false;
        const dist = getDistanceFromLatLonInKm(userLat, userLng, place.latitude, place.longitude);
        return dist <= radiusKm;
    });

    // Sort by distance
    filtered.sort((a, b) => {
        const distA = getDistanceFromLatLonInKm(userLat, userLng, a.latitude, a.longitude);
        const distB = getDistanceFromLatLonInKm(userLat, userLng, b.latitude, b.longitude);
        return distA - distB;
    });

    renderPlaces(filtered);
    map.fitBounds(userCircle.getBounds());
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the earth in km
    const dLat = deg2rad(lat2-lat1);  // deg2rad below
    const dLon = deg2rad(lon2-lon1); 
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2)
        ; 
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    const d = R * c; // Distance in km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI/180)
}
