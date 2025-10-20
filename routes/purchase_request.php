<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PurchaseRequestController.php';

$router = new Router();
$ctrl = new PurchaseRequestController();

$router->get('/purchase_request', fn() => $ctrl->index());
$router->get('/purchase_request/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/purchase_request', fn() => $ctrl->store());
$router->put('/purchase_request/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/purchase_request/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
