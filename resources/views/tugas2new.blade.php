<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 2</title>

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .main-container {
            display: flex;
            flex-direction: row;
            height: 100vh;
            overflow: hidden;
        }

        .form-container {
            width: 25%;
            padding: 20px;
            background-color: #f9f9f9;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }

        .map-container {
            width: 75%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        #leaflet-map {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .table-container {
            padding: 15px;
            overflow-y: auto;
            max-height: 50%; /* Adjust to limit table height */
            background-color: white;
            z-index: 2;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Form Section -->
    <div class="form-container">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Tambah Marker</h3>
            </div>
            <form id="markerForm" method="POST" action="{{ url('api/markers') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Lokasi</label>
                        <input type="text" class="form-control" id="markerName" placeholder="Nama Lokasi" required>
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="markerLat" placeholder="Latitude" required>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="markerLng" placeholder="Longitude" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Tambah Marker</button>
                    <button type="reset" class="btn btn-default">Cancel</button>
                </div>
            </form>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Tambah Poligon</h3>
            </div>
            <form id="polygonForm">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Koordinat Poligon (JSON)</label>
                        <textarea id="polygonCoords" class="form-control" placeholder="Koordinat Poligon (JSON)" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Tambah Poligon</button>
                    <button type="reset" class="btn btn-default">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-container">
        <div id="leaflet-map"></div>

        <!-- Table Section -->
        <div class="table-container">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Data Marker</h3>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                        <tr>
                            <th>Nama Lokasi</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="markerTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Data Poligon</h3>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                        <tr>
                            <th>Koordinat</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="polygonTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>






    <script type="text/javascript">
    // Buat Leaflet.maps
    const leafletMap = L.map('leaflet-map').setView([-8.6509, 115.2194], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(leafletMap);

    let markersLayer = L.layerGroup().addTo(leafletMap); // Layer untuk menyimpan semua marker
    let polygonsLayer = L.layerGroup().addTo(leafletMap); // Layer untuk menyimpan semua poligon

    // Fungsi untuk memuat data marker dari server
    function loadMarkers() {
        fetch("/api/markers")
            .then((response) => response.json())
            .then((markers) => {
                const markerTableBody = document.getElementById("markerTableBody");
                markerTableBody.innerHTML = ""; // Kosongkan tabel
                markersLayer.clearLayers(); // Hapus semua marker dari peta

                markers.forEach((marker) => {
                    // Tambahkan marker ke peta
                    const leafletMarker = L.marker([marker.latitude, marker.longitude])
                        .addTo(markersLayer)
                        .bindPopup(`<b>${marker.name}</b><br>Lat: ${marker.latitude}<br>Lng: ${marker.longitude}`);

                    // Tambahkan data ke tabel
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${marker.name}</td>
                        <td>${marker.latitude}</td>
                        <td>${marker.longitude}</td>
                        <td>
                            <button class="btn btn-sm btn-primary view-marker" data-id="${marker.id}" data-lat="${marker.latitude}" data-lng="${marker.longitude}">View</button>
                            <button class="btn btn-sm btn-warning edit-marker" data-id="${marker.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-marker" data-id="${marker.id}">Delete</button>
                        </td>
                    `;
                    markerTableBody.appendChild(row);
                });
            });
    }

    // Fungsi untuk memuat data poligon dari server
    function loadPolygons() {
        fetch("/api/polygons")
            .then((response) => response.json())
            .then((polygons) => {
                const polygonTableBody = document.getElementById("polygonTableBody");
                polygonTableBody.innerHTML = ""; // Kosongkan tabel
                polygonsLayer.clearLayers(); // Hapus semua poligon dari peta

                polygons.forEach((polygon) => {
                    const coords = JSON.parse(polygon.coordinates);

                    // Tambahkan poligon ke peta
                    const leafletPolygon = L.polygon(coords, { color: 'blue' })
                        .addTo(polygonsLayer)
                        .bindPopup(`<b>Polygon ID: ${polygon.id}</b>`);

                    // Tambahkan data ke tabel
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${polygon.id}</td>
                        <td>${polygon.coordinates}</td>
                        <td>
                            <button class="btn btn-sm btn-primary view-polygon" data-id="${polygon.id}" data-coords='${polygon.coordinates}'>View</button>
                            <button class="btn btn-sm btn-warning edit-polygon" data-id="${polygon.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-polygon" data-id="${polygon.id}">Delete</button>
                        </td>
                    `;
                    polygonTableBody.appendChild(row);
                });
            });
    }

    // Fungsi untuk menambahkan marker
    document.getElementById("markerForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const name = document.getElementById("markerName").value;
        const lat = parseFloat(document.getElementById("markerLat").value);
        const lng = parseFloat(document.getElementById("markerLng").value);

        fetch("{{url('api/markers')}}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ name, latitude: lat, longitude: lng }),
        })
            .then((res) => res.json())
            .then((data) => {
                alert("Marker ditambahkan!");
                loadMarkers(); // Refresh data marker
                document.getElementById("markerForm").reset(); // Reset form
            });
    });

    // Fungsi untuk menambahkan poligon
    document.getElementById("polygonForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const coords = JSON.parse(document.getElementById("polygonCoords").value);

        fetch("{{url('api/polygons')}}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ coordinates: coords }),
        })
            .then((res) => res.json())
            .then((data) => {
                alert("Polygon ditambahkan!");
                loadPolygons(); // Refresh data poligon
                document.getElementById("polygonForm").reset(); // Reset form
            });
    });

    // Fungsi untuk zoom ke marker atau poligon
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("view-marker")) {
            const lat = parseFloat(e.target.getAttribute("data-lat"));
            const lng = parseFloat(e.target.getAttribute("data-lng"));

            leafletMap.setView([lat, lng], 15); // Zoom ke marker
        }

        if (e.target.classList.contains("view-polygon")) {
            const coords = JSON.parse(e.target.getAttribute("data-coords"));
            const bounds = L.polygon(coords).getBounds(); // Dapatkan bounds dari poligon

            leafletMap.fitBounds(bounds); // Zoom ke poligon
        }
    });

    // Fungsi untuk menghapus marker atau poligon
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-marker")) {
            const id = e.target.getAttribute("data-id");
            if (confirm("Yakin ingin menghapus marker ini?")) {
                fetch(`/api/markers/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                    .then(() => {
                        alert("Marker berhasil dihapus!");
                        loadMarkers();
                    });
            }
        }

        if (e.target.classList.contains("delete-polygon")) {
            const id = e.target.getAttribute("data-id");
            if (confirm("Yakin ingin menghapus polygon ini?")) {
                fetch(`/api/polygons/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                    .then(() => {
                        alert("Polygon berhasil dihapus!");
                        loadPolygons();
                    });
            }
        }
    });

        // Fungsi untuk mengedit Marker atau Polygon
        document.addEventListener("click", function (e) {
            // Fungsi Edit Marker
            if (e.target.classList.contains("edit-marker")) {
                const id = e.target.getAttribute("data-id");
                fetch(`/api/markers/${id}`)
                    .then((response) => response.json())
                    .then((marker) => {
                        // Isi form dengan data marker
                        document.getElementById("markerName").value = marker.name;
                        document.getElementById("markerLat").value = marker.latitude;
                        document.getElementById("markerLng").value = marker.longitude;

                        // Tambahkan atribut data-id pada tombol submit
                        const markerForm = document.getElementById("markerForm");
                        markerForm.setAttribute("data-edit-id", id);
                    });
            }

            // Fungsi Edit Polygon
            if (e.target.classList.contains("edit-polygon")) {
                const id = e.target.getAttribute("data-id");
                fetch(`/api/polygons/${id}`)
                    .then((response) => response.json())
                    .then((polygon) => {
                        // Isi form dengan data polygon
                        document.getElementById("polygonCoords").value = JSON.stringify(polygon.coordinates);

                        // Tambahkan atribut data-id pada tombol submit
                        const polygonForm = document.getElementById("polygonForm");
                        polygonForm.setAttribute("data-edit-id", id);
                    });
            }
        });

        // Simpan perubahan Marker
        document.getElementById("markerForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const id = e.target.getAttribute("data-edit-id");
            const name = document.getElementById("markerName").value;
            const lat = parseFloat(document.getElementById("markerLat").value);
            const lng = parseFloat(document.getElementById("markerLng").value);

            const updatedMarker = { name, latitude: lat, longitude: lng };

            if (id) {
                // Update marker jika ID ada
                fetch(`/api/markers/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(updatedMarker),
                })
                    .then((response) => response.json())
                    .then(() => {
                        alert("Marker berhasil diperbarui!");
                        e.target.removeAttribute("data-edit-id"); // Hapus atribut data-edit-id
                        loadMarkers(); // Muat ulang marker di tabel
                    });
            }
        });

        // Simpan perubahan Polygon
        document.getElementById("polygonForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const id = e.target.getAttribute("data-edit-id");
            const coords = JSON.parse(document.getElementById("polygonCoords").value);

            const updatedPolygon = { coordinates: coords };

            if (id) {
                // Update polygon jika ID ada
                fetch(`/api/polygons/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(updatedPolygon),
                })
                    .then((response) => response.json())
                    .then(() => {
                        alert("Polygon berhasil diperbarui!");
                        e.target.removeAttribute("data-edit-id"); // Hapus atribut data-edit-id
                        loadPolygons(); // Muat ulang polygon di tabel
                    });
            }
        });




    // Muat data marker dan poligon saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function () {
        loadMarkers();
        loadPolygons();
    });
</script>



</body>
</html>
