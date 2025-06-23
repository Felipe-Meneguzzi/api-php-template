<?php
/*************************************************CORS***************************************************************/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

date_default_timezone_set('America/Sao_Paulo');

/********************************************************************************************************************/

require __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\AppRouter;
use App\Core\Http\HTTPRequest;
use Dotenv\Dotenv;

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$request = new HTTPRequest();

$appRouter = new AppRouter();

$app = new App($appRouter);

$app->HandleRequest($request);
