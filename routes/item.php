<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ItemController.php';

$router = new Router();
$ctrl = new ItemController();

$router->get('/item', fn() => $ctrl->index());
$router->get('/item/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/item', fn() => $ctrl->store());
$router->put('/item/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/item/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
