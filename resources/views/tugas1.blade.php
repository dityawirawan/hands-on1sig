@extends('layout.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUGAS HANDS-ON 1</title>
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
            background: #f0f8ff; 
            color: #333;
        }
        h1 {
            text-align: center;
            padding: 20px;
            color: #2c3e50;
        }
        .map-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .map-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 48%;
            min-width: 400px;
        }
        .map-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            color: #34495e;
        }
        .map {
            height: 400px;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .map-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h1>Peta Interaktif dengan Laravel<br>Lokasi Universitas Udayana</h1>

    <div class="map-container">
        <!-- Peta menggunakan Google Maps -->
        <div class="map-box">
            <div class="map-title">Peta Google API</div>
            <div id="google-map" class="map"></div>
        </div>

        <!-- Peta menggunakan Leaflet -->
        <div class="map-box">
            <div class="map-title">Peta Leaflet</div>
            <div id="leaflet-map" class="map"></div>
        </div>
    </div>
    @endsection

    @section('script')
    <script>
        // Peta menggunakan Leaflet.js
        const leafletMap = L.map('leaflet-map').setView([-8.7984047, 115.1698715], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(leafletMap);

        // Marker pertama di Rektorat Universitas Udayana
        const markerBukit = L.marker([-8.7984047, 115.1698715]).addTo(leafletMap);
        markerBukit.bindPopup("<b>Kantor: Rektorat Universitas Udayana</b>").openPopup();

        // Zoom otomatis saat marker pertama di-klik
        markerBukit.on('click', function () {
            leafletMap.setView(markerBukit.getLatLng(), 15); 
        });

        // Marker tambahan di lokasi -8.6509, 115.2194
        const markerDenpasar = L.marker([-8.6728528,115.2185298]).addTo(leafletMap);
        markerDenpasar.bindPopup("<b>Universitas Udayana Kampus Sudirman</b>");

        // Zoom otomatis saat marker tambahan di-klik
        markerDenpasar.on('click', function () {
            leafletMap.setView(markerDenpasar.getLatLng(), 15); 
        });





        // Peta menggunakan Google Maps API
        function initGoogleMap() {
            const location = { lat: -8.7984047, lng: 115.1698715 };
            const googleMap = new google.maps.Map(document.getElementById('google-map'), {
                center: location,
                zoom: 10,
            });

            const googleMarker = new google.maps.Marker({
                position: location,
                map: googleMap,
                title: "Kantor: Rektorat Universitas Udayana",
            });

            const infoWindow = new google.maps.InfoWindow({
                content: "<b>Kantor: Rektorat Universitas Udayana</b>",
            });

            googleMarker.addListener('click', function () {
                infoWindow.open(googleMap, googleMarker);
                googleMap.setZoom(15); 
                googleMap.setCenter(googleMarker.getPosition());
            });
        }


        window.onload = initGoogleMap;
    </script>
</body>
</html>
@endsection