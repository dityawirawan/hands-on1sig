<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 2</title>
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .btn-delete {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-delete:hover {
            background-color: darkred;
        }

        .btn-view {
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-view:hover {
            background-color: darkgreen;
        }

        .wrapper {
            display: flex;
            flex-direction: row-reverse;
            margin: 20px;
        }

        .form-container {
            flex: 1;
            margin-left: 20px;
            max-width: 300px;
        }

        .card {
            margin-bottom: 5px;
            width: 100%;
            /* Atur lebar card sesuai kebutuhan */
        }

        #leaflet-map {
            flex: 2;
            height: 715px;
            margin: 0;
        }

        .table-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            align-items: center;
            gap: 20px;
            /* Jarak antar tabel */
        }

        .table-wrapper {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Form Tambah Marker dan Tambah Polygon -->
        <div class="form-container">
            <!-- Form Tambah Marker -->
            <!-- Form Tambah Marker -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Tambahkan Marker</b></h3>
                </div>
                <form id="markerForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="markerName">Nama Lokasi</label>
                            <input type="text" id="markerName" name="markerName" class="form-control"
                                placeholder="Nama Lokasi" required>
                        </div>
                        <div class="form-group">
                            <label for="markerLat">Latitude</label>
                            <input type="text" id="markerLat" name="markerLat" class="form-control"
                                placeholder="Latitude" required>
                        </div>
                        <div class="form-group">
                            <label for="markerLng">Longitude</label>
                            <input type="text" id="markerLng" name="markerLng" class="form-control"
                                placeholder="Longitude" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Tambah Marker</button>
                    </div>
                </form>
            </div>
            <!-- Form Tambah Poligon -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Tambahkan Poligon</b></h3>
                </div>
                <form id="polygonForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="polygonCoords">Koordinat Poligon (JSON)</label>
                            <textarea id="polygonCoords" name="polygonCoords" class="form-control" rows="4"
                                placeholder="Masukkan koordinat poligon dalam format JSON" required></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Tambah Poligon</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Peta Leaflet -->
        <div id="leaflet-map"></div>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
        <!-- Tabel Data Marker -->
        <div class="card card-primary table-wrapper">
            <div class="card-header">
                <h3 class="card-title"><b>Data Marker</b></h3>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="markerTableBody">
                        <!-- Data Marker diload di sini -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Data Poligon -->
        <div class="card card-primary table-wrapper">
            <div class="card-header">
                <h3 class="card-title"><b>Data Poligon</b></h3>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Koordinat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="polygonTableBody">
                        <!-- Data Poligon diload di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // Buat Leaflet.maps
        const leafletMap = L.map('leaflet-map').setView([-8.6509, 115.2194], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);
        loadMarkers();
        loadPolygons();
        const markerTableBody = document.getElementById('markerTableBody');
        const polygonTableBody = document.getElementById('polygonTableBody');

        // Tambahkan event listener untuk marker
        document.getElementById("markerForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const name = document.getElementById("markerName").value;
            const lat = parseFloat(document.getElementById("markerLat").value);
            const lng = parseFloat(document.getElementById("markerLng").value);
            fetch("{{ url('api/markers') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        name,
                        latitude: lat,
                        longitude: lng
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    alert("Marker ditambahkan!");
                    loadMarkers();
                    document.getElementById("markerForm").reset();
                });
        });
        // Tambahkan event listener untuk poligon
        document.getElementById("polygonForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const coords = JSON.parse(document.getElementById("polygonCoords").value);
            fetch("{{ url('api/polygons') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        coordinates: coords
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    alert("Polygon ditambahkan!");
                    loadPolygons();
                    document.getElementById("polygonForm").reset();
                });
        });

        // Fungsi untuk menghapus semua marker dari peta
        function clearMarkers() {
            leafletMap.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    leafletMap.removeLayer(layer);
                }
            });
        }

        // Fungsi untuk menghapus semua poligon dari peta
        function clearPolygons() {
            leafletMap.eachLayer(function(layer) {
                if (layer instanceof L.Polygon) {
                    leafletMap.removeLayer(layer);
                }
            });
        }

        // Fungsi untuk memuat data marker
        function loadMarkers() {
            clearMarkers();
            fetch("{{ url('api/markers') }}")
                .then(response => response.json())
                .then(data => {
                    markerTableBody.innerHTML = '';
                    data.forEach(marker => {
                        markerTableBody.innerHTML += `
                        <tr>
                            <td>${marker.name}</td>
                            <td>${marker.latitude}</td>
                            <td>${marker.longitude}</td>
                            <td>
                                <button class="btn-delete" onclick="deleteMarker(${marker.id})">Delete</button>
                                <button class="btn-view" onclick="viewMarker(${marker.id})">View Map</button>
                            </td>
                        </tr>`;
                        const markerView = L.marker([marker.latitude, marker.longitude]).addTo(leafletMap);
                    });
                });
        }

        // Fungsi untuk memuat data polygon
        function loadPolygons() {
            clearPolygons();
            fetch("{{ url('api/polygons') }}")
                .then(response => response.json())
                .then(data => {
                    polygonTableBody.innerHTML = '';
                    data.forEach(polygon => {
                        polygonTableBody.innerHTML += `
                        <tr>
                            <td>${polygon.coordinates}</td>
                            <td>
                                <button class="btn-delete" onclick="deletePolygon(${polygon.id})">Delete</button>
                                <button class="btn-view" onclick="viewPolygon(${polygon.id})">View Map</button>
                            </td>
                        </tr>`;
                        let coordinates;
                        try {
                            coordinates = JSON.parse(polygon.coordinates);
                        } catch (error) {
                            console.error(Error parsing coordinates for polygon ID ${polygon.id}:, error);
                            return;
                        }
                        const polygonLayer = L.polygon(coordinates.map(coord => [coord.lat, coord.lng]), {
                            color: 'red',
                            weight: 3,
                            fillOpacity: 0.5
                        }).addTo(leafletMap);
                    });
                });
        }

        // Fungsi untuk menghapus marker
        function deleteMarker(id) {
            fetch({{ url('api/markers') }}/${id}, {
                method: 'DELETE',
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                alert('Marker berhasil dihapus!');
                loadMarkers();
            });
        }

        // Fungsi untuk menghapus polygon
        function deletePolygon(id) {
            fetch({{ url('api/polygons') }}/${id}, {
                method: 'DELETE',
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                alert('Polygon berhasil dihapus!');
                loadPolygons();
            });
        }

        // Fungsi untuk melihat lokasi marker di peta
        function viewMarker(id) {
            fetch({{ url('api/markers') }}/${id})
                .then(response => response.json())
                .then(marker => {
                    leafletMap.setView([marker.latitude, marker.longitude], 15);
                    const markerView = L.marker([marker.latitude, marker.longitude]).addTo(leafletMap);
                    markerView.bindPopup(<b>${marker.name}</b>).openPopup();
                });
        }

        // Fungsi untuk melihat lokasi polygon di peta
        function viewPolygon(id) {
            fetch({{ url('api/polygons') }}/${id})
                .then(response => response.json())
                .then(polygon => {
                    // Parse JSON koordinat poligon dari database
                    const coordinates = JSON.parse(polygon.coordinates);
                    // Set tampilan peta berdasarkan titik pertama poligon
                    if (coordinates.length > 0) {
                        leafletMap.setView([coordinates[0].lat, coordinates[0].lng], 15);
                    }
                    polygonLayer.bindPopup(<b>Polygon ID: ${polygon.id}</b>);
                })
        }
    </script>

</body>

</html>