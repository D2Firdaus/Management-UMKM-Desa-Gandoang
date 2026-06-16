<?php
$base_url = '/Management_UMKM_Desa_Gandoang';
$asset_path = $base_url . '/asset/';
$view_path = $base_url . '/views/';

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$project_folder = explode('/', $script_name)[1];

define('BASE_URL', $protocol . "://" . $host . "/" . $project_folder . "/");
$asset_path = BASE_URL . 'asset/';
$view_path = BASE_URL . 'views/';

// Path Untuk Controller
define('CONTROLLER_PATH', BASE_URL . 'controllers/');
$auth_controller_path = CONTROLLER_PATH . 'authControllers/';
$bantuan_controller_path = CONTROLLER_PATH . 'bantuanControllers/';
$product_controller_path = CONTROLLER_PATH . 'productControllers/';
$umkm_controller_path    = CONTROLLER_PATH . 'umkmControllers/';
$journey_controller_path = CONTROLLER_PATH . 'journeyControllers/';
