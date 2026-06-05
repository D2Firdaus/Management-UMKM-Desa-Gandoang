<?php
session_start();
require_once __DIR__ . '/config/path_config.php';
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<?php include 'views/layouts/navbar.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Gandoang</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }
        .main { flex: 1; padding: 2rem 3rem; }
        .welcome { background: #2d5a3f; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome h1 { margin: 0 0 0.5rem; font-size: 1.5rem; }
        .welcome p { margin: 0; opacity: 0.9; }
        .btn-logout { display: inline-block; margin-top: 1rem; padding: 0.5rem 1.5rem; background: white; color: #2d5a3f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; }
        #map-manual-picker {
            height: 450px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .info-koordinat {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #e9ecef;

        }
    </style>
</head>
`   
<body>
    <div class="main">
        <div class="welcome">
            <?php if ($is_logged_in): ?>
                <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</h1>
                <p>Anda berhasil masuk ke Sistem Manajemen UMKM Desa Gandoang.</p>
                <a href="<?= CONTROLLER_PATH ?>logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <h1>Selamat Datang di UMKM Desa Gandoang!</h1>
                <p>Silakan <a href="<?= BASE_URL ?>views/auth/login.php" style="color:white;font-weight:700;">masuk</a> atau <a href="<?= BASE_URL ?>views/auth/register.php" style="color:white;font-weight:700;">daftar</a> untuk mengakses fitur lengkap.</p>
            <?php endif; ?>
        </div>
        <div class="container mt-3">
            <label class="form-label fw-bold">Pilih Titik Lokasi UMKM (Klik pada peta atau geser pin)</label>
            
            <div id="map-manual-picker"></div>

            <div class="info-koordinat">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small text-muted">Latitude (Garis Lintang)</label>
                        <input type="text" name="latitude" id="lat-input" class="form-control" readonly required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small text-muted">Longitude (Garis Bujur)</label>
                        <input type="text" name="longitude" id="lng-input" class="form-control" readonly required>
                    </div>
                </div>
                <small class="text-secondary">*Koordinat di atas akan otomatis terupdate saat Anda mengubah posisi pin di peta.</small>
            </div>
        </div>
    </div>
    <?php include 'views/layouts/footer.php'; ?>
    <script>

        const defaultLat = -6.4024312;
        const defaultLng = 107.0321451;

        const map = L.map('map-manual-picker').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let pinLokasi = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateFormValues(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('lng-input').value = lng.toFixed(8);
        }

        updateFormValues(defaultLat, defaultLng);

        pinLokasi.on('dragend', function (e) {
            const posisi = pinLokasi.getLatLng();
            updateFormValues(posisi.lat, posisi.lng);
        });

        map.on('click', function (e) {
            const klat = e.latlng.lat;
            const klng = e.latlng.lng;
            
            pinLokasi.setLatLng([klat, klng]);
            updateFormValues(klat, klng);
        });
</script>
</body>

</html>