<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PeranController.php';

$router = new Router();
$ctrl = new PeranController();

$router->get('/peran', fn() => $ctrl->index());
$router->get('/peran/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/peran', fn() => $ctrl->store());
$router->put('/peran/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/peran/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
