<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ApVoucherController.php';

$router = new Router();
$ctrl = new ApVoucherController();

$router->get('/ap_voucher', fn() => $ctrl->index());
$router->get('/ap_voucher/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/ap_voucher', fn() => $ctrl->store());
$router->put('/ap_voucher/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/ap_voucher/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
