<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/VendorController.php';

$router = new Router();
$ctrl = new VendorController();

$router->get('/vendor', fn() => $ctrl->index());
$router->get('/vendor/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/vendor', fn() => $ctrl->store());
$router->put('/vendor/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/vendor/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
