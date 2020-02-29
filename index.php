<?php

use App\Controllers\Api\Test;

require 'autoload.php';

define('BASE_URL', '/botfolio');

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', BASE_URL . '/test', function () {
        $controller = new Test();
        $controller->index();
    });

    $r->addRoute('GET', BASE_URL . '/users', function () {
        echo 'users';
    });

    $r->addRoute('GET', BASE_URL . '/user/{id:\d+}', function ($id) {
        echo 'user id ' . $id;
    });

    $r->addRoute('GET', BASE_URL . '/articles/{id:\d+}[/{title}]', function ($id, $title = 'no') {
        echo "id=$id, title=$title";
    });
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        //$handler(...$vars);
        call_user_func_array($handler, $vars);
        break;
}
