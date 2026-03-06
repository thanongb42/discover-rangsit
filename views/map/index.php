<div id="map" style="height:600px;"></div>
<link rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([13.9889,100.6181],13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution:'© OpenStreetMap'
}).addTo(map);

fetch('/api/places')
.then(res => res.json())
.then(data => {

    data.forEach(place => {

        var marker = L.marker([
            place.latitude,
            place.longitude
        ]).addTo(map);

        var popup = `
        <div style="width:200px">
            <img src="/uploads/${place.cover_image}" width="100%">
            <h4>${place.name}</h4>
            ⭐ ${place.rating_avg}
            <br>
            👁 ${place.views_count}
            <br><br>

            <a href="/place/${place.slug}">
            ดูรายละเอียด
            </a>

            <br>

            <a target="_blank"
            href="https://www.google.com/maps/dir/?api=1&destination=${place.latitude},${place.longitude}">
            นำทาง
            </a>
        </div>
        `;

        marker.bindPopup(popup);

    });

});

</script>