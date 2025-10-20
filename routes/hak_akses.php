<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/HakAksesController.php';

$router = new Router();
$ctrl = new HakAksesController();

$router->get('/hak_akses', fn() => $ctrl->index());
$router->get('/hak_akses/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/hak_akses', fn() => $ctrl->store());
$router->put('/hak_akses/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/hak_akses/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
