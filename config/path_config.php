<?php
define('BASE_PATH', __DIR__ . '/');

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$project_folder = explode('/', $script_name)[1];

define('BASE_URL', $protocol . "://" . $host . "/" . $project_folder . "/");
$asset_path = BASE_URL . 'asset/';
$view_path = BASE_URL . 'views/';
