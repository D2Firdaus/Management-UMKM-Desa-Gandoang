<?php
session_start();
require_once __DIR__ . '/../../config/path_config.php';
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Data Dummy Produk (Base 8 items)
$baseDummyProducts = [
    ['id' => 1, 'nama' => 'Lele Fillet Goreng Tepung Ibu Markonah', 'kategori' => 'Makanan & Minuman', 'harga' => 10000, 'gambar' => BASE_URL . 'asset/images/lele_fillet.png', 'rekomendasi' => true],
    ['id' => 2, 'nama' => 'Bakso Ikan Lele Gurih Pak Udin', 'kategori' => 'Makanan & Minuman', 'harga' => 10000, 'gambar' => BASE_URL . 'asset/images/bakso_lele.png', 'rekomendasi' => true],
    ['id' => 3, 'nama' => 'Lele Segar Pak Mamat Asli Cileungsi', 'kategori' => 'Olahan Peternakan', 'harga' => 10000, 'gambar' => BASE_URL . 'asset/images/lele_segar.png', 'rekomendasi' => true],
    ['id' => 4, 'nama' => 'Pasang Internet Murah sekitar Cileungsi', 'kategori' => 'Jasa', 'harga' => 10000, 'gambar' => BASE_URL . 'asset/images/pasang_wifi.png', 'rekomendasi' => true],
    ['id' => 5, 'nama' => 'Baju Batik Tulis Khas Gandoang', 'kategori' => 'Fashion', 'harga' => 75000, 'gambar' => BASE_URL . 'asset/images/baju_batik.png', 'rekomendasi' => true],
    ['id' => 6, 'nama' => 'Kerajinan Anyaman Bambu Cantik', 'kategori' => 'Kerajinan/Souvenir', 'harga' => 45000, 'gambar' => BASE_URL . 'asset/images/anyaman.png', 'rekomendasi' => true],
    ['id' => 7, 'nama' => 'Kopi Robusta Seduh Asli Gandoang', 'kategori' => 'Makanan & Minuman', 'harga' => 15000, 'gambar' => BASE_URL . 'asset/images/kopi_robusta.png', 'rekomendasi' => true],
    ['id' => 8, 'nama' => 'Pakan Lele Protein Tinggi', 'kategori' => 'Olahan Peternakan', 'harga' => 8000, 'gambar' => BASE_URL . 'asset/images/pakan_lele.png', 'rekomendasi' => true]
];

$dummyProducts = array_slice($baseDummyProducts, 0, 5);

$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'Semua kategori';
$filteredProducts = [];
foreach ($dummyProducts as $prod) {
    if ($activeCategory === 'Semua kategori' || $prod['kategori'] === $activeCategory) {
        $filteredProducts[] = $prod;
    }
}

$per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$total_items = count($filteredProducts);
$total_pages = ceil($total_items / $per_page);

if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

$offset = ($current_page - 1) * $per_page;
$pagedProducts = array_slice($filteredProducts, $offset, $per_page);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - UMKM Gandoang</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>asset/css/landing_page.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>asset/css/katalog_produk.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body style="background-color: #fdfdfd;">
    <?php include __DIR__ . '/../../views/layouts/navbar.php'; ?>

    <div class="katalog-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>"><i class="bi bi-house-door"></i> Beranda</a>
            <i class="bi bi-chevron-right separator"></i>
            <span class="current">Lihat Product</span>
        </div>

        <!-- Section Rekomendasi -->
        <div class="katalog-section mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="katalog-title mb-0"><i class="bi bi-star-fill text-warning"></i> Barang yang Direkomendasikan</h2>
                <div class="product-nav m-0">
                    <button class="nav-btn" onclick="scrollProductCarousel(-1)">&#10094;</button>
                    <button class="nav-btn" onclick="scrollProductCarousel(1)">&#10095;</button>
                </div>
            </div>

            <div class="product-carousel recommended-carousel">
                <?php
                // Display all 8 products as recommended for the carousel
                foreach ($dummyProducts as $prod):
                ?>
                    <div class="product-card" style="flex: 0 0 280px; min-width: 280px;">
                        <div class="product-img-wrapper" style="aspect-ratio: 4/3;">
                            <img src="<?= $prod['gambar'] ?>" alt="<?= htmlspecialchars($prod['nama']) ?>" onerror="this.onerror=null; this.src='<?= BASE_URL ?>asset/images/logo.png';">
                        </div>
                        <div class="katalog-info">
                            <h3 class="katalog-name"><?= htmlspecialchars($prod['nama']) ?></h3>
                            <p class="katalog-price">Rp <?= number_format($prod['harga'], 0, ',', '.') ?></p>
                            <a href="<?= BASE_URL ?>view/product/katalog-produk/<?= $prod['id'] ?>" class="btn-detail">Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section Filter & Semua Produk -->
        <div class="katalog-section mt-5 mb-5">
            <div class="katalog-filters" id="category-filters">
                <?php
                $categories = ['Semua kategori', 'Makanan & Minuman', 'Olahan Peternakan', 'Fashion', 'Kerajinan/Souvenir'];
                foreach ($categories as $cat):
                    $activeClass = ($activeCategory === $cat) ? 'active' : '';
                    $url = "?category=" . urlencode($cat);
                ?>
                    <a href="<?= $url ?>" class="filter-btn <?= $activeClass ?>" style="text-decoration:none; display:inline-block;"><?= htmlspecialchars($cat) ?></a>
                <?php endforeach; ?>
            </div>

            <div class="katalog-grid" id="all-products-grid">
                <?php if (empty($pagedProducts)): ?>
                    <p class="text-muted w-100 text-center py-5">Tidak ada produk di kategori ini.</p>
                <?php else: ?>
                    <?php foreach ($pagedProducts as $prod): ?>
                        <div class="katalog-card product-item" data-category="<?= htmlspecialchars($prod['kategori']) ?>">
                            <div class="katalog-img-wrapper">
                                <img src="<?= $prod['gambar'] ?>" alt="<?= htmlspecialchars($prod['nama']) ?>" onerror="this.onerror=null; this.src='<?= BASE_URL ?>asset/images/logo.png';">
                            </div>
                            <div class="katalog-info">
                                <h3 class="katalog-name"><?= htmlspecialchars($prod['nama']) ?></h3>
                                <p class="katalog-price">Rp <?= number_format($prod['harga'], 0, ',', '.') ?></p>
                                <a href="<?= BASE_URL ?>view/product/katalog-produk/<?= $prod['id'] ?>" class="btn-detail">
                                    Detail
                                    <i class="bi bi-arrow-right-short" style="font-size:1.2rem; transition: transform 0.3s;"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Dynamic Pagination -->
            <ul class="katalog-pagination mt-4">
                <!-- Previous Button -->
                <li class="page-item prev-next <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a href="<?= ($current_page <= 1) ? '#' : '?category=' . urlencode($activeCategory) . '&page=' . ($current_page - 1) ?>" class="page-link">Previous</a>
                </li>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                        <a href="?category=<?= urlencode($activeCategory) ?>&page=<?= $i ?>" class="page-link"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item prev-next <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a href="<?= ($current_page >= $total_pages) ? '#' : '?category=' . urlencode($activeCategory) . '&page=' . ($current_page + 1) ?>" class="page-link">Next</a>
                </li>
            </ul>
        </div>
    </div>

    <?php include __DIR__ . '/../../views/layouts/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force navbar to be solid on catalogue page
            const nav = document.querySelector('nav');
            if (nav) {
                nav.classList.add('scrolled-nav');
                nav.style.background = 'linear-gradient(to right, #0b1615, #162e2b)';
                const style = document.createElement('style');
                style.innerHTML = 'nav::before { opacity: 1 !important; }';
                document.head.appendChild(style);
            }

            const filterBtns = document.querySelectorAll('.filter-btn');
            const productItems = document.querySelectorAll('.product-item');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active class
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filterValue = this.getAttribute('data-filter');

                    productItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        if (filterValue === 'Semua kategori' || filterValue === itemCategory) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        let isCarouselScrolling = false;

        function scrollProductCarousel(direction) {
            if (isCarouselScrolling) return;

            const carousel = document.querySelector('.recommended-carousel');
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