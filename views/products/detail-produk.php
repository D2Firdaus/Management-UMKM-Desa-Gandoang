<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($product['nama']) ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>asset/css/landing_page.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #fcfdfd;
        }

        .detail-container {
            padding: 8rem 5% 4rem;
            max-width: 1300px;
            margin: 0 auto;
            flex: 1;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #0b1615;
        }

        .breadcrumb .current {
            color: #2b7a4b;
            font-weight: 600;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1.1fr 1.3fr 1.1fr;
            gap: 3rem;
            align-items: start;
        }

        /* Gallery Section */
        .gallery-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .main-img-box {
            width: 100%;
            aspect-ratio: 1 / 1;
            background-color: #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .main-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-grid {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }

        .thumb-box {
            width: 70px;
            height: 70px;
            background-color: #f0f0f0;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .thumb-box:hover,
        .thumb-box.active {
            border-color: #2b7a4b;
        }

        .thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Info Section */
        .info-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            position: relative;
        }

        .info-title {
            font-size: 2rem;
            font-weight: 500;
            color: #222;
            margin: 0;
            line-height: 1.2;
        }

        .info-category {
            font-size: 1.1rem;
            color: #555;
            margin: 0;
        }

        .info-desc {
            font-size: 1.05rem;
            color: #444;
            line-height: 1.6;
            margin: 0;
        }

        .stats-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1rem;
            color: #333;
            font-weight: 500;
        }

        .stats-row i {
            color: #ffc107;
        }

        .price-wrapper {
            margin-top: 0.5rem;
        }

        .price-main {
            font-size: 2.2rem;
            font-weight: 800;
            color: #000;
            margin: 0;
        }

        .price-discount {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-top: 0.4rem;
        }

        .badge-discount {
            background-color: #e0e0e0;
            color: #333;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .price-coret {
            text-decoration: line-through;
            color: #888;
            font-size: 1rem;
            font-weight: 500;
        }

        .divider {
            height: 1px;
            background-color: #ddd;
            margin: 1.5rem 0;
        }

        /* CTA Section (Far right column) */
        .cta-wrapper {
            background-color: transparent;
            border: none;
            padding: 2rem 0;
            text-align: center;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cta-text {
            font-size: 1.25rem;
            color: #0b1615;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            text-align: center;
        }

        .btn-wa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            background-color: #2b7a4b;
            color: #fff;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            border: none;
            width: 100%;
            max-width: 100%;
        }

        .btn-wa:hover {
            background-color: #1e5936;
            color: #fff;
            box-shadow: 0 4px 12px rgba(43, 122, 75, 0.25);
        }

        .btn-wa i {
            font-size: 1.3rem;
        }

        @media (max-width: 1100px) {
            .detail-grid {
                grid-template-columns: 1fr 1.2fr;
                gap: 2rem;
            }

            .cta-wrapper {
                grid-column: span 2;
            }
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .cta-wrapper {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="detail-container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>">Beranda</a>
            <span class="separator">›</span>
            <a href="<?= BASE_URL ?>katalog-produk">Lihat Product</a>
            <span class="separator">›</span>
            <span class="current">Detail Product</span>
        </div>

        <div class="detail-grid">
            <!-- Left: Gallery -->
            <div class="gallery-wrapper">
                <div class="main-img-box">
                    <img id="main-product-img" src="<?= $product['gambar_utama'] ?>" alt="Produk" onerror="this.onerror=null; this.src='<?= BASE_URL ?>asset/images/default.jpg';">
                </div>
                <?php if (!empty($product['gambar_lain'])): ?>
                    <div class="thumb-grid">
                        <div class="thumb-box active" onclick="switchMainImage('<?= $product['gambar_utama'] ?>', this)">
                            <img src="<?= $product['gambar_utama'] ?>" alt="Main Thumb" onerror="this.style.display='none'">
                        </div>
                        <?php foreach ($product['gambar_lain'] as $idx => $glain): ?>
                            <div class="thumb-box" onclick="switchMainImage('<?= $glain ?>', this)">
                                <img src="<?= $glain ?>" alt="Thumb <?= $idx + 1 ?>" onerror="this.style.display='none'">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Info -->
            <div class="info-wrapper">
                <div>
                    <h1 class="info-title"><?= htmlspecialchars($product['nama']) ?></h1>
                    <p class="info-category">jenis UMKM <?= htmlspecialchars($product['kategori']) ?></p>
                </div>

                <p class="info-desc"><?= htmlspecialchars($product['deskripsi']) ?></p>

                <div class="price-wrapper">
                    <h2 class="price-main">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h2>
                </div>

            </div>

            <!-- Right: CTA Card -->
            <div class="cta-wrapper">
                <p class="cta-text">"Hubungi langsung penjual <br><?= htmlspecialchars($product['nama_umkm']) ?>"</p>
                <a href="https://wa.me/<?= $product['no_hp'] ?>?text=Halo%20saya%20tertarik%20dengan%20produk%20<?= urlencode($product['nama']) ?>%20dari%20<?= urlencode($product['nama_umkm']) ?>" target="_blank" class="btn-wa">
                    <i class="bi bi-whatsapp"></i>
                    Kirim Pesan Whatsapp
                </a>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section" style="margin-top: 5rem; padding: 0;">
            <div class="map-header" style="width: 100%; text-align: left; margin-bottom: 1.5rem;">
                <span class="product-subtitle" style="font-size: 0.75rem; font-weight: 700; color: #0b1615; letter-spacing: 1px; text-transform: uppercase;">Lokasi Toko</span>
                <h2 class="section-title" style="font-size: 1.8rem; font-weight: 800; color: #0b1615; margin-top: 0.5rem; margin-bottom: 0.5rem;">Peta Alamat <?= htmlspecialchars($product['nama_umkm']) ?></h2>
                <p class="map-desc" style="font-size: 1rem; color: #666;"><?= htmlspecialchars($umkmInfo['alamat'] ?? 'Desa Gandoang, Kec. Cileungsi, Bogor') ?></p>
            </div>
            <div class="map-container-inner" style="width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div id="map-manual-picker" style="height: 400px; width: 100%; z-index: 1;"></div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <script>
        function switchMainImage(src, element) {
            const mainImg = document.getElementById('main-product-img');
            if (mainImg) {
                mainImg.src = src;
            }

            // Manage active classes
            const thumbs = document.querySelectorAll('.thumb-box');
            thumbs.forEach(t => t.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Force navbar solid background for detail page
            const nav = document.querySelector('nav');
            if (nav) {
                nav.classList.add('scrolled-nav');
                nav.style.background = 'linear-gradient(to right, #0b1615, #162e2b)';
                const style = document.createElement('style');
                style.innerHTML = 'nav::before { opacity: 1 !important; }';
                document.head.appendChild(style);
            }

            // Initialize Map — koordinat dari database UMKM
            const defaultLat = <?= !empty($product['latitude'])  ? (float)$product['latitude']  : -6.4024312 ?>;
            const defaultLng = <?= !empty($product['longitude']) ? (float)$product['longitude'] : 107.0321451 ?>;

            const map = L.map('map-manual-picker').setView([defaultLat, defaultLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng]).addTo(map);
            marker.bindPopup(`
                <div class="custom-popup">
                    <div style="margin:0 0 5px 0; color:#0b1615; font-weight:700; font-size:1.1rem;"><?= htmlspecialchars($product['nama_umkm']) ?></div>
                    <div style="font-size:0.95rem; color:#444; line-height:1.4;"><?= htmlspecialchars($umkmInfo['alamat'] ?? 'Desa Gandoang, Kec. Cileungsi, Bogor') ?></div>
                </div>
            `).openPopup();
        });
    </script>
</body>

</html>