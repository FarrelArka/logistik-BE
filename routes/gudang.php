<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/GudangController.php';

$router = new Router();
$ctrl = new GudangController();

$router->get('/gudang', fn() => $ctrl->index());
$router->get('/gudang/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/gudang', fn() => $ctrl->store());
$router->put('/gudang/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/gudang/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
