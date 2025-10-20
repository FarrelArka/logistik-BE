<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ApPaymentHeaderController.php';

$router = new Router();
$ctrl = new ApPaymentHeaderController();

$router->get('/ap_payment_header', fn() => $ctrl->index());
$router->get('/ap_payment_header/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/ap_payment_header', fn() => $ctrl->store());
$router->put('/ap_payment_header/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/ap_payment_header/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
