<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ModulController.php';

$router = new Router();
$ctrl = new ModulController();

$router->get('/modul', fn() => $ctrl->index());
$router->get('/modul/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/modul', fn() => $ctrl->store());
$router->put('/modul/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/modul/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
