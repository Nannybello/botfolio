<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("ROOT_PATH", __DIR__);

require_once(ROOT_PATH . '/vendor/autoload.php');
require_once(ROOT_PATH . '/line_bot.php');

use App\Controllers\WebHookController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//spl_autoload_register(function($path){
//    $paths = explode("\\", $path);
//    $path = implode(DIRECTORY_SEPARATOR, $paths);
//    include_once ROOT_PATH . "/$path.php";
//});

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/webhook', function (Request $request, Response $response, $args) {
    $controller = new WebHookController();
    $response->getBody()->write($controller);
    return $response;
});

$app->run();