<?php
session_start();
require_once __DIR__ . '/../config/path_config.php';
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Gandoang</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>asset/css/landing_page.css?v=<?= time() ?>">
</head>

<body>
    <?php include __DIR__ . '/layouts/navbar.php'; ?>

    <!-- Hero Banner Section -->
    <div class="hero-banner">
        <img src="<?= BASE_URL ?>asset/images/banner.png" alt="Hero Background" class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-text">
                <h1>Kembangkan UMKM<br>Anda Bersama Desa<br>Gandoang !</h1>
                <p>Mari Temukan berbagai produk unggulan desa, informasi pelatihan, dan panduan<br>usaha dalam satu platform.</p>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="about-section">
            <div class="about-image">
                <img src="<?= BASE_URL ?>asset/images/about_gandoang.png" alt="Tentang UMKM Desa Gandoang">
            </div>
            <div class="about-text">
                <h2>Mendukung Pertumbuhan UMKM Desa</h2>
                <p>Platform ini membantu pelaku UMKM desa dalam mempromosikan produk, mengelola informasi usaha, serta memberikan akses panduan dan edukasi untuk meningkatkan kualitas bisnis lokal.</p>
            </div>
        </div>
        <div class="video-section">
            <h2 class="section-title">Video Panduan</h2>
            <div class="video-grid">
                <!-- Card 1 -->
                <div class="video-card">
                    <div class="custom-thumb thumb-orange">
                        <div class="thumb-content">
                            <span class="thumb-badge">UMKM<br>Gandoang</span>
                            <h4 class="thumb-title">Cara Mendaftarkan<br>Produk UMKM ke<br>Sistem</h4>
                        </div>
                        <img src="<?= BASE_URL ?>asset/images/woman_megaphone.png" alt="Person" class="thumb-person">
                    </div>
                    <h3 class="video-title">Cara Mendaftarkan Produk UMKM ke Sistem</h3>
                    <p class="video-date">30 Desember 2006</p>
                </div>

                <!-- Card 2 -->
                <div class="video-card">
                    <div class="custom-thumb thumb-green">
                        <div class="thumb-content">
                            <span class="thumb-badge">UMKM<br>Gandoang</span>
                            <h4 class="thumb-title">Panduan Mengelola<br>Data Produk dan<br>Stok</h4>
                        </div>
                        <!-- Ganti src dengan gambar pria memegang kardus -->
                        <img src="<?= BASE_URL ?>asset/images/man_box.png" alt="Person" class="thumb-person">
                    </div>
                    <h3 class="video-title">Panduan Mengelola Data Produk dan Stok</h3>
                    <p class="video-date">30 Desember 2006</p>
                </div>

                <!-- Card 3 -->
                <div class="video-card">
                    <div class="custom-thumb thumb-blue">
                        <div class="thumb-content">
                            <span class="thumb-badge">UMKM<br>Gandoang</span>
                            <h4 class="thumb-title">Cara Mengajukan<br>Bantuan Keperluan<br>Yang Dibutuhkan...</h4>
                        </div>
                        <!-- Ganti src dengan gambar dua orang -->
                        <img src="<?= BASE_URL ?>asset/images/people_help.png" alt="Person" class="thumb-person" style="right: 0;">
                    </div>
                    <h3 class="video-title">Cara Mengajukan Bantuan Keperluan Yang Dibutuhkan UMKM</h3>
                    <p class="video-date">30 Desember 2006</p>
                </div>
            </div>
        </div>
        <div class="product-section">
            <div class="product-header">
                <span class="product-subtitle">Pusat Informasi UMKM</span>
                <h2 class="product-title">Produk Unggulan Usaha Kecil<br>Desa Gandoang</h2>
                <div class="product-nav">
                    <button class="nav-btn" onclick="scrollProductCarousel(-1)">&#10094;</button>
                    <button class="nav-btn" onclick="scrollProductCarousel(1)">&#10095;</button>
                </div>
                <a href="#" class="btn-outline">PRODUK LAINNYA</a>
            </div>
            <div class="product-carousel">
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/lele_segar.png" alt="Lele Segar">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Budidaya</span>
                        <h3 class="product-name">Lele segar hasil budidaya warga desa kualitas terbaik</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/wifi_desa.png" alt="Layanan WiFi">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Layanan</span>
                        <h3 class="product-name">Layanan WiFi Desa untuk Masyarakat</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/pakan_lele.png" alt="Pakan Lele">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Produk Tani</span>
                        <h3 class="product-name">Pakan Lele Berkualitas untuk Hasil Panen Maksimal</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/lele_segar.png" alt="Lele Segar">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Budidaya</span>
                        <h3 class="product-name">Lele segar hasil budidaya warga desa kualitas terbaik</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/wifi_desa.png" alt="Layanan WiFi">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Layanan</span>
                        <h3 class="product-name">Layanan WiFi Desa untuk Masyarakat</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="<?= BASE_URL ?>asset/images/pakan_lele.png" alt="Pakan Lele">
                    </div>
                    <div class="product-info">
                        <span class="product-tag">Produk Tani</span>
                        <h3 class="product-name">Pakan Lele Berkualitas untuk Hasil Panen Maksimal</h3>
                        <p class="product-desc">30 Desember 2006</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map-section">
        <div class="map-header">
            <span class="product-subtitle">Jelajahi Lokasi</span>
            <h2 class="section-title">Peta Persebaran UMKM Desa Gandoang</h2>
            <p class="map-desc">Temukan berbagai produk dan layanan unggulan langsung di lokasi pembuatannya.</p>
        </div>
        <div class="map-container-inner">
            <div id="map-manual-picker"></div>
        </div>
    </div>
    </div>
    <?php include __DIR__ . '/layouts/footer.php'; ?>
    <script>
        const defaultLat = -6.4024312;
        const defaultLng = 107.0321451;

        const map = L.map('map-manual-picker').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // --- Data dummy UMKM terdaftar ---
        const dummyUMKM = [{
                nama: "Keripik Singkong Ibu Kokom",
                deskripsi: "Produksi keripik singkong aneka rasa.",
                lat: -6.398500,
                lng: 107.031000
            },
            {
                nama: "Kerajinan Bambu Pak Jajang",
                deskripsi: "Berbagai anyaman bambu dan perabotan unik.",
                lat: -6.405500,
                lng: 107.033500
            },
            {
                nama: "Kopi Seduh Gandoang",
                deskripsi: "Kopi robusta asli panenan lokal.",
                lat: -6.401000,
                lng: 107.036000
            },
            {
                nama: "Budidaya Lele Jaya",
                deskripsi: "Penyedia lele konsumsi dan bibit lele.",
                lat: -6.404500,
                lng: 107.028500
            },
            {
                nama: "Pusat Oleh-oleh Gandoang",
                deskripsi: "Toko pusat jajan dan oleh-oleh khas desa.",
                lat: -6.400500,
                lng: 107.033000
            }
        ];

        // Tampilkan marker dummy di peta menggunakan icon default
        dummyUMKM.forEach(umkm => {
            const marker = L.marker([umkm.lat, umkm.lng]).addTo(map);
            marker.bindPopup(`
                <div class="custom-popup">
                    <div style="margin:0 0 5px 0; color:#0b1615; font-weight:700; font-size:1.1rem;">${umkm.nama}</div>
                    <div style="font-size:0.95rem; color:#444; line-height:1.4;">${umkm.deskripsi}</div>
                </div>
            `);
        });

        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 400) {
                nav.classList.add('scrolled-nav');
            } else {
                nav.classList.remove('scrolled-nav');
            }
        });
    </script>
    <script>
        let isCarouselScrolling = false;

        function scrollProductCarousel(direction) {
            if (isCarouselScrolling) return;

            const carousel = document.querySelector('.product-carousel');
            if (!carousel) return;

            isCarouselScrolling = true;

            const item = carousel.firstElementChild;
            const style = window.getComputedStyle(carousel);
            const gap = parseFloat(style.gap) || 0;
            const scrollAmount = item.offsetWidth + gap;

            const items = Array.from(carousel.children);

            const originalOverflow = carousel.style.overflowX;
            carousel.style.overflowX = 'hidden';
            carousel.scrollLeft = 0;

            if (direction === 1) {
                const animations = items.map(el =>
                    el.animate([{
                            transform: 'translateX(0px)'
                        },
                        {
                            transform: `translateX(-${scrollAmount}px)`
                        }
                    ], {
                        duration: 400,
                        easing: 'ease-in-out'
                    })
                );

                Promise.all(animations.map(a => a.finished)).then(() => {
                    carousel.appendChild(carousel.firstElementChild);
                    carousel.style.overflowX = originalOverflow;
                    isCarouselScrolling = false;
                });
            } else {
                carousel.prepend(carousel.lastElementChild);

                const newItems = Array.from(carousel.children);

                const animations = newItems.map(el =>
                    el.animate([{
                            transform: `translateX(-${scrollAmount}px)`
                        },
                        {
                            transform: 'translateX(0px)'
                        }
                    ], {
                        duration: 400,
                        easing: 'ease-in-out'
                    })
                );

                Promise.all(animations.map(a => a.finished)).then(() => {
                    carousel.style.overflowX = originalOverflow;
                    isCarouselScrolling = false;
                });
            }
        }
    </script>
</body>

</html>