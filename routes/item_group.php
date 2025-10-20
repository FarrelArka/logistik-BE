<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ItemGroupController.php';

$router = new Router();
$ctrl = new ItemGroupController();

$router->get('/item_group', fn() => $ctrl->index());
$router->get('/item_group/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/item_group', fn() => $ctrl->store());
$router->put('/item_group/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/item_group/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
