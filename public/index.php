<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Bramus\Router\Router;

// load semua route
require_once __DIR__ . '/../routes/all_routes.php';

// Jalankan router
$router->run();
