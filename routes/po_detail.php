<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PoDetailController.php';

$router = new Router();
$ctrl = new PoDetailController();

$router->get('/po_detail', fn() => $ctrl->index());
$router->get('/po_detail/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/po_detail', fn() => $ctrl->store());
$router->put('/po_detail/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/po_detail/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
