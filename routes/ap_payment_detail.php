<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ApPaymentDetailController.php';

$router = new Router();
$ctrl = new ApPaymentDetailController();

$router->get('/ap_payment_detail', fn() => $ctrl->index());
$router->get('/ap_payment_detail/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/ap_payment_detail', fn() => $ctrl->store());
$router->put('/ap_payment_detail/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/ap_payment_detail/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
