<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/GrDetailController.php';

$router = new Router();
$ctrl = new GrDetailController();

$router->get('/gr_detail', fn() => $ctrl->index());
$router->get('/gr_detail/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/gr_detail', fn() => $ctrl->store());
$router->put('/gr_detail/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/gr_detail/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
