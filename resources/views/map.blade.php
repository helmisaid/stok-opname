<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info Gempa Terkini dengan TomTom Map</title>

    <!-- TomTom Map SDK -->
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.16.0/maps/maps-web.min.js"></script>
    <link rel="stylesheet" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.16.0/maps/maps.css">

    <!-- jQuery untuk memanggil API BMKG -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS untuk styling tambahan -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #content-wrapper {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 600px;
        }

        #map {
            height: 400px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
            font-weight: 600;
        }

        .btn-refresh {
            display: block;
            margin: 0 auto;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-refresh:hover {
            background-color: #0056b3;
            color: white;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="content-wrapper">

        <h3>Info Gempa Terkini</h3>

        <!-- Peta TomTom -->
        <div id="map"></div>

        <!-- Tombol untuk refresh data -->
        <a href="#" class="btn-refresh" onclick="initMap(); return false;">Refresh Data Gempa</a>

        <!-- Footer -->
        <footer>Data diambil dari <a href="https://bmkg.go.id" target="_blank">BMKG</a></footer>
    </div>

    <script>
        var map;
        var apiKey = 'tvebyV5Lx8knBwt5JGqCdsIufq8sV7aw';  // Ganti dengan API Key dari TomTom

        function initMap() {
            // Inisialisasi peta, mulai dengan pusat yang standar
            map = tt.map({
                key: apiKey,
                container: 'map',
                center: [120.382615, -1.831239],  // Lokasi pusat awal (Indonesia)
                zoom: 1
            });

            // Ambil data gempa dari API BMKG
            $.getJSON("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json", function(data) {
                let gempa = data.Infogempa.gempa;
                let latitude = parseFloat(gempa.Lintang.replace(' LS', '').replace(' LU', ''));
                let longitude = parseFloat(gempa.Bujur.replace(' BT', '').replace(' BB', ''));
                let lokasi = gempa.Wilayah;

                // Tambahkan marker ke peta dan pindahkan pusat peta ke marker tersebut
                addMarker(latitude, longitude, lokasi);
            });
        }

        // Fungsi untuk menambahkan marker dan memusatkan peta pada marker
        function addMarker(lat, lng, lokasi) {
            var marker = new tt.Marker().setLngLat([lng, lat]).addTo(map);

            // Tambahkan popup info pada marker
            var popup = new tt.Popup().setHTML(`<h5>Gempa di ${lokasi}</h5>`);
            marker.setPopup(popup).togglePopup();

            // Pusatkan peta pada marker
            map.setCenter([lng, lat]);
            map.setZoom(1); // Perbesar zoom ke lokasi gempa
        }

        // Inisialisasi peta saat halaman dimuat
        window.onload = initMap;
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
