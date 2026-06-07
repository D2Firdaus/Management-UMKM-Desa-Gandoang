<?php
// Pastikan halaman ini hanya diakses melalui controller
if (!isset($pagedProducts, $rekomendasi, $kategoriList, $activeCategory, $current_page, $total_pages)) {
    die('Akses langsung tidak diizinkan.');
}
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - UMKM Gandoang</title>
    <meta name="description" content="Temukan berbagai produk unggulan UMKM Desa Gandoang — makanan, kerajinan, fashion, dan masih banyak lagi.">
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

        <!-- ── Section Rekomendasi (hanya di halaman pertama) ────────────── -->
        <?php if ($current_page === 1 && !empty($rekomendasi)): ?>
        <div class="katalog-section mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="katalog-title mb-0"><i class="bi bi-star-fill text-warning"></i> Barang yang Direkomendasikan</h2>
                <div class="product-nav m-0">
                    <button class="nav-btn" id="btn-prev-carousel" onclick="scrollProductCarousel(-1)">&#10094;</button>
                    <button class="nav-btn" id="btn-next-carousel" onclick="scrollProductCarousel(1)">&#10095;</button>
                </div>
            </div>

            <div class="product-carousel recommended-carousel" id="recommended-carousel">
                <?php foreach ($rekomendasi as $prod): ?>
                    <div class="product-card" style="flex: 0 0 280px; min-width: 280px;">
                        <div class="product-img-wrapper" style="aspect-ratio: 4/3;">
                            <img
                                src="<?= htmlspecialchars($prod['gambar']) ?>"
                                alt="<?= htmlspecialchars($prod['nama_produk']) ?>"
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>asset/images/logo.png';">
                        </div>
                        <div class="katalog-info">
                            <h3 class="katalog-name"><?= htmlspecialchars($prod['nama_produk']) ?></h3>
                            <p class="katalog-price">Rp <?= number_format((float)$prod['harga'], 0, ',', '.') ?></p>
                            <a href="<?= BASE_URL ?>view/product/katalog-produk/<?= htmlspecialchars($prod['id_produk']) ?>" class="btn-detail">Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($rekomendasi)): ?>
                    <p class="text-muted py-3">Belum ada produk tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Section Filter & Semua Produk ─────────────────────────────── -->
        <div class="katalog-section mt-5 mb-5">

            <!-- Filter tombol kategori (diambil dari DB) -->
            <div class="katalog-filters" id="category-filters">
                <?php foreach ($kategoriList as $cat):
                    $activeClass = ($activeCategory === $cat) ? 'active' : '';
                    $url = '?category=' . urlencode($cat);
                ?>
                    <a href="<?= $url ?>" id="filter-<?= htmlspecialchars(preg_replace('/[^a-zA-Z0-9]/', '-', $cat)) ?>"
                       class="filter-btn <?= $activeClass ?>"
                       style="text-decoration:none; display:inline-block;">
                        <?= htmlspecialchars($cat) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Grid Produk -->
            <div class="katalog-grid" id="all-products-grid">
                <?php if (empty($pagedProducts)): ?>
                    <p class="text-muted w-100 text-center py-5">Tidak ada produk di kategori ini.</p>
                <?php else: ?>
                    <?php foreach ($pagedProducts as $prod): ?>
                        <div class="katalog-card product-item" data-category="<?= htmlspecialchars($prod['kategori']) ?>">
                            <div class="katalog-img-wrapper">
                                <img
                                    src="<?= htmlspecialchars($prod['gambar']) ?>"
                                    alt="<?= htmlspecialchars($prod['nama_produk']) ?>"
                                    onerror="this.onerror=null; this.src='<?= BASE_URL ?>asset/images/logo.png';">
                            </div>
                            <div class="katalog-info">
                                <h3 class="katalog-name"><?= htmlspecialchars($prod['nama_produk']) ?></h3>
                                <p class="katalog-price">Rp <?= number_format((float)$prod['harga'], 0, ',', '.') ?></p>
                                <a href="<?= BASE_URL ?>view/product/katalog-produk/<?= htmlspecialchars($prod['id_produk']) ?>" class="btn-detail">
                                    Detail
                                    <i class="bi bi-arrow-right-short" style="font-size:1.2rem; transition: transform 0.3s;"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Paginasi -->
            <ul class="katalog-pagination mt-4">
                <li class="page-item prev-next <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a id="pagination-prev"
                       href="<?= ($current_page <= 1) ? '#' : '?category=' . urlencode($activeCategory) . '&page=' . ($current_page - 1) ?>"
                       class="page-link">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                        <a id="pagination-page-<?= $i ?>"
                           href="?category=<?= urlencode($activeCategory) ?>&page=<?= $i ?>"
                           class="page-link"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item prev-next <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a id="pagination-next"
                       href="<?= ($current_page >= $total_pages) ? '#' : '?category=' . urlencode($activeCategory) . '&page=' . ($current_page + 1) ?>"
                       class="page-link">Next</a>
                </li>
            </ul>
        </div>
    </div>

    <?php include __DIR__ . '/../../views/layouts/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buat navbar solid di halaman katalog
            const nav = document.querySelector('nav');
            if (nav) {
                nav.classList.add('scrolled-nav');
                nav.style.background = 'linear-gradient(to right, #0b1615, #162e2b)';
                const style = document.createElement('style');
                style.innerHTML = 'nav::before { opacity: 1 !important; }';
                document.head.appendChild(style);
            }
        });

        // ── Carousel Rekomendasi ──────────────────────────────────────────
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
                    el.animate([
                        { transform: 'translateX(0px)' },
                        { transform: `translateX(-${scrollAmount}px)` }
                    ], { duration: 400, easing: 'ease-in-out' })
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
                    el.animate([
                        { transform: `translateX(-${scrollAmount}px)` },
                        { transform: 'translateX(0px)' }
                    ], { duration: 400, easing: 'ease-in-out' })
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