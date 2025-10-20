<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/DivisiController.php';

$router = new Router();
$ctrl = new DivisiController();

$router->get('/divisi', fn() => $ctrl->index());
$router->get('/divisi/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/divisi', fn() => $ctrl->store());
$router->put('/divisi/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/divisi/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
