<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/ApFaktorPengurangController.php';

$router = new Router();
$ctrl = new ApFaktorPengurangController();

$router->get('/ap_faktor_pengurang', fn() => $ctrl->index());
$router->get('/ap_faktor_pengurang/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/ap_faktor_pengurang', fn() => $ctrl->store());
$router->put('/ap_faktor_pengurang/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/ap_faktor_pengurang/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
