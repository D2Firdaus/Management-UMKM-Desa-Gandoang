<?php
require_once __DIR__ . '/../../models/ProductModel.php';

class ProductController
{
    private $productModel;

    public function __construct($conn)
    {
        $this->productModel = new ProductModel($conn);
    }

    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $per_page = isset($_GET['show']) ? (int)$_GET['show'] : 3;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if ($per_page <= 0) $per_page = 3;
        if ($current_page <= 0) $current_page = 1;

        $offset = ($current_page - 1) * $per_page;

        $total_items = $this->productModel->countAllProducts($search);
        $total_pages = ceil($total_items / $per_page);

        // Pastikan current page tidak melebihi total pages jika data ada
        if ($current_page > $total_pages && $total_pages > 0) {
            $current_page = $total_pages;
            $offset = ($current_page - 1) * $per_page;
        }

        $products = $this->productModel->getAllProducts($search, $per_page, $offset);

        return [
            'products'     => $products,
            'search'       => $search,
            'per_page'     => $per_page,
            'current_page' => $current_page,
            'total_pages'  => $total_pages
        ];
    }
}
