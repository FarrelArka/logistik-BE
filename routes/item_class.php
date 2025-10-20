<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ItemClassController.php';

$router = new Router();
$ctrl = new ItemClassController();

$router->get('/item_class', fn() => $ctrl->index());
$router->get('/item_class/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/item_class', fn() => $ctrl->store());
$router->put('/item_class/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/item_class/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
