<?php

// Inclui o Composer e o nosso bootstrap de telemetria
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/opentelemetry_bootstrap.php';

use App\Core\AppDIContainer;
use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;
use App\Router;
use Dotenv\Dotenv;
use OpenTelemetry\API\Globals;
use OpenTelemetry\SemConv\TraceAttributes;

try {
    /********************************************************************************************************************/
    /*************************************************CORS***************************************************************/

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    date_default_timezone_set('America/Sao_Paulo');

    /********************************************************************************************************************/
    /********************************************************************************************************************/


    /********************************************************************************************************************/
    /*************************************************TELEMETRIA*********************************************************/

    $response = new DefaultResponse(statusCode: 500, message: 'Internal Server Error');

    initialize_opentelemetry();

    $tracer = Globals::tracerProvider()->getTracer('io.opentelemetry.contrib.php', '1.0.0');

    $request = new HTTPRequest();

    $rootSpan = $tracer->spanBuilder(sprintf('%s %s', $request->method, $request->uri))
        ->setAttribute('http.request.method', $request->method)
        ->setAttribute('url.full', $request->uri)
        ->setAttribute('url.path', $request->uri)
        ->setAttribute('url.query', $request->uri)
        ->setAttribute('user_agent.original', $request->userAgent)
        ->setAttribute('client.address', $request->requestIP)
        ->startSpan();

    $scope = $rootSpan->activate();

    /********************************************************************************************************************/
    /********************************************************************************************************************/


    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    });

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $container = AppDIContainer::build();
    $router = new Router($request, $container);
    $routeDefiner = require __DIR__ . '/../src/Api.php';
    $routeDefiner($router, $container);

    $response = $router->dispatch();

    $rootSpan->setStatus('Ok', $response->body['message'] ?? '');

} catch (AppException $appException) {

    $response = new DefaultResponse(statusCode: $appException->getCode(), message: $appException->getMessage());
    $rootSpan?->setStatus('Ok', $appException->getCode() . '- ' . $appException->getMessage() ?? '');

} catch (UnexpectedValueException $jwtException) {

    $response = new DefaultResponse(statusCode: 401, message: 'JWTToken Authorization Failed: ' . $jwtException->getMessage());
    $rootSpan?->setStatus('Ok', '401- JWTToken Authorization Failed: ' . $jwtException->getMessage() ?? '');

} catch (\Throwable $unhandledException) {

    $response = new DefaultResponse(statusCode: 500, data: ['ERROR' => $unhandledException->getMessage()], message: 'Error not treated in APP, please contact support');
    $rootSpan?->recordException($unhandledException, [TraceAttributes::EXCEPTION_ESCAPED => true]);
    $rootSpan?->setStatus('Error', '500- ' . $unhandledException->getMessage() ?? 'Internal Server Error with no message');

} finally {

    $rootSpan?->setStatus($response->getStatusCode());
    $rootSpan?->setAttribute('http.status_code', $response->getStatusCode());

    $response->sendResponse();

    $scope?->detach();
    $rootSpan?->end();
}
