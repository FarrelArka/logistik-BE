<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PrDetailController.php';

$router = new Router();
$ctrl = new PrDetailController();

$router->get('/pr_detail', fn() => $ctrl->index());
$router->get('/pr_detail/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/pr_detail', fn() => $ctrl->store());
$router->put('/pr_detail/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/pr_detail/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
