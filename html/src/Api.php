<?php
declare(strict_types=1);

use App\Module\Login\Controller\UserLoginController;
use App\Router;
use DI\Container;

return function (Router $router, Container $container) {
    $router->group(['prefix' => '/login', 'middleware' => []], function ($router) use ($container) {

        $router->get('', $container->get(UserLoginController::class));

    });
};