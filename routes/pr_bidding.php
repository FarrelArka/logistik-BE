<?php
use Bramus\Router\Router;
require_once __DIR__ . '/../controllers/PrBiddingController.php';

$router = new Router();
$ctrl = new PrBiddingController();

$router->get('/pr_bidding', fn() => $ctrl->index());
$router->get('/pr_bidding/(\d+)', fn($id) => $ctrl->show($id));
$router->post('/pr_bidding', fn() => $ctrl->store());
$router->put('/pr_bidding/(\d+)', fn($id) => $ctrl->update($id));
$router->delete('/pr_bidding/(\d+)', fn($id) => $ctrl->delete($id));

return $router;
