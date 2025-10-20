<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/UserAccountController.php';

$router = new Router();
$ctrl = new UserAccountController();

$router->get('/user_account', fn() => $ctrl->index());
$router->get('/user_account/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/user_account', fn() => $ctrl->store());
$router->put('/user_account/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/user_account/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
