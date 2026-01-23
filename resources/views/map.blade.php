<!DOCTYPE html>
<html>
<head>
    <title>Flight Radar Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([20, 0], 2);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let markers = [];

// Load planes from API
async function loadPlanes() {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/planes'); // full URL for localhost
        if (!res.ok) throw new Error('Network response was not ok');

        const planes = await res.json();

        // Clear old markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        // Add new markers
        planes.forEach(plane => {
            if (!plane.latitude || !plane.longitude) return;

            const marker = L.marker([plane.latitude, plane.longitude])
                .addTo(map)
                .bindPopup(`
                    <b>${plane.callsign || 'N/A'}</b><br>
                    ${plane.origin_country}<br>
                    Altitude: ${plane.baro_altitude || 'N/A'} m<br>
                    Velocity: ${plane.velocity || 'N/A'} m/s
                `);

            markers.push(marker);
        });

        console.log('Planes loaded:', planes.length);
    } catch(err) {
        console.error('Error loading planes:', err);
    }
}

// Initial load
loadPlanes();

// Refresh every 30 seconds
setInterval(loadPlanes, 30000);
</script>

</body>
</html>
