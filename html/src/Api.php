<?php
declare(strict_types=1);

use App\Middleware\LogMiddleware;
use App\Module\Login\Controller\UserLoginController;
use App\Router;

return function (Router $router) {
    $router->group(['prefix' => '/login', 'middleware' => [LogMiddleware::class]], function ($router) {

        $router->get(uri:'', handler:[UserLoginController::class, 'Run']);

    });
};