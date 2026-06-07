<?php

declare(strict_types=1);

require_once __DIR__ . '/../../models/KatalogProdukModel.php';
require_once __DIR__ . '/../../config/path_config.php';

class KatalogProdukController
{
    private KatalogProdukModel $model;

    public function __construct(PDO $db)
    {
        $this->model = new KatalogProdukModel($db);
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $activeCategory = isset($_GET['category']) ? trim($_GET['category']) : 'Semua kategori';
        $current_page   = max(1, (int) ($_GET['page'] ?? 1));
        $per_page       = 6;

        $kategoriList   = array_merge(['Semua kategori'], $this->model->getKategori());
        $rekomendasi    = ($current_page === 1) ? $this->model->getRekomendasi(12) : [];

        $offset         = ($current_page - 1) * $per_page;
        $result         = $this->model->getPaginated($activeCategory, $per_page, $offset);
        $pagedProducts  = $result['rows'];
        $total_items    = $result['total_count'];
        $total_pages    = max(1, (int) ceil($total_items / $per_page));

        if ($current_page > $total_pages) {
            $current_page = $total_pages;
            $offset       = ($current_page - 1) * $per_page;
            $result       = $this->model->getPaginated($activeCategory, $per_page, $offset);
            $pagedProducts = $result['rows'];
        }

        $normalise = function (array $products): array {
            return array_map(function ($prod) {
                $fotoList       = array_filter(array_map('trim', explode(',', $prod['foto'] ?? '')));
                $gambar         = !empty($fotoList) && reset($fotoList) !== 'default.jpg'
                    ? BASE_URL . 'storage/images/products/' . reset($fotoList)
                    : BASE_URL . 'asset/images/default.jpg';
                $prod['gambar'] = $gambar;
                return $prod;
            }, $products);
        };

        $rekomendasi   = !empty($rekomendasi) ? $normalise($rekomendasi) : [];
        $pagedProducts = $normalise($pagedProducts);

        require_once __DIR__ . '/../../views/products/katalog-produk.php';
    }
}
