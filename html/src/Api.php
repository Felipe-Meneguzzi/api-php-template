<?php
declare(strict_types=1);

use App\Middleware\LogMiddleware;
use App\Module\Login\Controller\UserLoginController;
use App\Router;
use App\Module\FeaturesTest\Controller\FeatureTestController;
use App\Module\FeaturesTest\Middleware\FeatureTestMiddleware;

return function (Router $router) {
    if ($_ENV['ENVIRONMENT'] === 'dev') {
        $router->get(uri: '/test/restful/{parametro_1}', handler: [FeatureTestController::class, 'Run'], middlewares: [FeatureTestMiddleware::class, LogMiddleware::class], controllerParams: ['string', 1, false]);
    }

    $router->group(['prefix' => '/login', 'middleware' => [LogMiddleware::class]], function ($router) {

        $router->post(uri: '', handler: [UserLoginController::class, 'Run']);

    });
};