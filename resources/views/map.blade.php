<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasar Peta Interaktif</title>
    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwlgebS3bplkEr9NEFBhut66Xo-m4muW4"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            padding: 20px;
        }
        #leaflet-map, #google-map {
            height: 400px;
            margin: 20px auto;
            max-width: 90%;
        }
    </style>
</head>
<body>
    <h1>Peta Interaktif dengan Laravel</h1>

    <!-- Peta menggunakan Leaflet -->
    <div id="leaflet-map"></div>

    <!-- Peta menggunakan Google Maps -->
    <div id="google-map"></div>

    <script>
        // Peta menggunakan Leaflet.js
        const leafletMap = L.map('leaflet-map').setView([-8.6509, 115.2194], 10);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CartoDB</a> contributors'
        }).addTo(leafletMap);

        const locations = [
            { coords: [-8.6728528, 115.2185298], popup: "<b>Universitas Udayana</b><br>Denpasar, Bali" },
            { coords: [-8.6699675, 115.2344887], popup: "<b>Lapangan Renon</b><br>Denpasar, Bali" },
            { coords: [-8.7172564,115.1687018], popup: "<b>Pantai Kuta</b><br>Kuta, Bali" },
            { coords: [-8.669209, 115.2134385], popup: "<b>Level 21 Mall</b><br>Denpasar, Bali" }
        ];

        locations.forEach(location => {
            const marker = L.marker(location.coords).addTo(leafletMap);
            marker.bindPopup(location.popup);

            // Zoom otomatis saat marker di-klik pada Leaflet.js
            marker.on('click', function () {
                leafletMap.setView(marker.getLatLng(), 17); // Zoom ke level 17
            });
        });

        // Peta menggunakan Google Maps API
        const googleMap = new google.maps.Map(document.getElementById('google-map'), {
            center: { lat: -8.6728528, lng: 115.2185298 },
            zoom: 10,
        });

        const googleMarkers = [
            { coords: { lat: -8.6728528, lng: 115.2185298 }, title: "Universitas Udayana" },
            { coords: { lat: -8.6699675, lng: 115.2344887 }, title: "Lapangan Renon" },
            { coords: { lat: -8.7172564, lng: 115.1687018 }, title: "Pantai Kuta" },
            { coords: { lat: -8.669209, lng: 115.2134385 }, title: "Level 21 Mall Denpasar" }
        ];

        googleMarkers.forEach(location => {
            const marker = new google.maps.Marker({
                position: location.coords,
                map: googleMap,
                title: location.title,
            });

            // Zoom otomatis saat marker di-klik pada Google Maps API
            marker.addListener('click', function () {
                googleMap.setZoom(17); // Zoom ke level 17
                googleMap.setCenter(marker.getPosition());
            });
        });
    </script>
</body>
</html>
