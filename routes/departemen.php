<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/DepartemenController.php';

$router = new Router();
$ctrl = new DepartemenController();

$router->get('/departemen', fn() => $ctrl->index());
$router->get('/departemen/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/departemen', fn() => $ctrl->store());
$router->put('/departemen/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/departemen/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
