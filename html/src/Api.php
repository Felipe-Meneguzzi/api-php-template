<?php
declare(strict_types=1);

use App\Middleware\RequestLogMiddleware;
use App\Module\Login\Controller\UserLoginController;
use App\Module\User\Controller\GetUserByIdController;
use App\Router;
use App\Module\FeaturesTest\Controller\FeatureTestController;
use App\Module\FeaturesTest\Middleware\FeatureTestMiddleware;
use App\Module\User\Controller\GetAllUsersController;

return function (Router $router) {
    if ($_ENV['ENVIRONMENT'] === 'dev') {
        $router->get(uri: '/test/restful/{parametro_1}', handler: [FeatureTestController::class, 'Run'], middlewares: [FeatureTestMiddleware::class, RequestLogMiddleware::class], controllerParams: ['string', 1, false]);
    }
    $router->group(['prefix' => '', 'middleware' => [RequestLogMiddleware::class]], function ($router) {

        $router->group(['prefix' => '/login'], function ($router) {

            $router->post(uri: '', handler: [UserLoginController::class, 'Run']);

        });

        $router->group(['prefix' => '/user'], function ($router) {
            $router->get(uri: '/{id}', handler: [GetUserByIdController::class, 'Run']);
            $router->get(uri: '', handler: [GetAllUsersController::class, 'Run']);
        });

    });
};