<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Lidmašīnu radars</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>

<script>
const map = L.map('map').setView([20, 0], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

const planeIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/61/61212.png',
    iconSize: [28, 28],
    iconAnchor: [14, 14],
});

let planes = {};
const UPDATE_INTERVAL = 5000;
const ANIMATION_STEPS = 50;

function animateMarker(marker, from, to) {
    let step = 0;
    const latStep = (to.lat - from.lat) / ANIMATION_STEPS;
    const lngStep = (to.lng - from.lng) / ANIMATION_STEPS;

    const interval = setInterval(() => {
        step++;
        marker.setLatLng([
            from.lat + latStep * step,
            from.lng + lngStep * step
        ]);
        if (step >= ANIMATION_STEPS) clearInterval(interval);
    }, UPDATE_INTERVAL / ANIMATION_STEPS);
}

async function loadPlanes() {
    const res = await fetch('http://127.0.0.1:8000/api/planes');
    const data = await res.json();

    data.forEach(p => {
        if (!p.latitude || !p.longitude) return;

        const id = p.icao24;
        const newPos = L.latLng(p.latitude, p.longitude);

        if (planes[id]) {
            const oldPos = planes[id].getLatLng();
            animateMarker(planes[id], oldPos, newPos);
            planes[id].setRotationAngle(p.heading || 0);
        } else {
            planes[id] = L.marker(newPos, {
                icon: planeIcon,
                rotationAngle: p.heading || 0,
                rotationOrigin: 'center center'
            }).addTo(map);
        }
    });
}


loadPlanes();
setInterval(loadPlanes, UPDATE_INTERVAL);
</script>

</body>
</html>
