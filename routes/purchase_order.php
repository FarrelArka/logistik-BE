<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PurchaseOrderController.php';

$router = new Router();
$ctrl = new PurchaseOrderController();

$router->get('/purchase_order', fn() => $ctrl->index());
$router->get('/purchase_order/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/purchase_order', fn() => $ctrl->store());
$router->put('/purchase_order/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/purchase_order/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
