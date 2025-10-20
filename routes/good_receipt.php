<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/GoodReceiptController.php';

$router = new Router();
$ctrl = new GoodReceiptController();

$router->get('/good_receipt', fn() => $ctrl->index());
$router->get('/good_receipt/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/good_receipt', fn() => $ctrl->store());
$router->put('/good_receipt/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/good_receipt/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
