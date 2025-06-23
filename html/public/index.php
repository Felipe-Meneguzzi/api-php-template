<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Core\AppDIContainer;
use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;
use App\Router;
use Dotenv\Dotenv;

try {
    /*************************************************CORS***************************************************************/

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

    date_default_timezone_set('America/Sao_Paulo');

    /********************************************************************************************************************/
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    });

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $container = AppDIContainer::build();

    $request = new HTTPRequest();
    $router = new Router($request);

    $routeDefiner = require __DIR__ . '/../src/Api.php';
    $routeDefiner($router, $container);

    $router->dispatch();

} catch (AppException $appException) {
    $response = new DefaultResponse(
        statusCode: $appException->getCode(),
        message: $appException->getMessage()
    );

    $response->sendResponse();
} catch (\Throwable $unhandledException) {
    $response = new DefaultResponse(
        statusCode: 500,
        data: ['ERROR' => $unhandledException->getMessage()],
        message: 'Erro não tratado na API, contate o BackEnd'
    );

    $response->sendResponse();
}

