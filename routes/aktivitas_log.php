<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/AktivitasLogController.php';

$router = new Router();
$ctrl = new AktivitasLogController();

$router->get('/aktivitas_log', fn() => $ctrl->index());
$router->get('/aktivitas_log/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/aktivitas_log', fn() => $ctrl->store());
$router->put('/aktivitas_log/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/aktivitas_log/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
