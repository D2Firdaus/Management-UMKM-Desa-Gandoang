<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/HomeModel.php';
require_once __DIR__ . '/../config/path_config.php';

class HomeController
{
    private HomeModel $model;

    public function __construct(PDO $db)
    {
        $this->model = new HomeModel($db);
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ambil lokasi UMKM aktif dari DB untuk peta
        $umkmList = $this->model->getUmkmLokasi();

        // Ambil 4 produk acak
        $productsToShow = $this->model->getRandomProducts(4);

        // Render View
        require_once __DIR__ . '/../views/home.php';
    }
}
