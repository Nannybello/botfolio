<?php

use App\Controllers\Api\Test;
use App\Views\View;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

require 'autoload.php';
require 'lib/BladeOne/BladeOne.php';

//define('BASE_URL', '/botfolio');
define('BASE_URL', '');
define('BASE_PATH', __DIR__);

include BASE_PATH . '/App/Config/line_bot.php';
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

class GlobalView
{
    public $blade;

    public function __construct()
    {
        $viewPath = BASE_PATH . '/views';
        $compliedPath = BASE_PATH . '/views/compiled';
        $this->blade = new \eftec\bladeone\BladeOne($viewPath, $compliedPath);
    }

    public function compose(string $viewName, array $params = []): string
    {
        return $this->blade->run($viewName, $params);
    }
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($bot) {
    $r->addRoute('GET', BASE_URL . '/test', function () {
        $controller = new Test();
        $controller->index();
    });

    $r->addRoute('GET', BASE_URL . '/applyform', function () {
        $controller = new \App\Controllers\Web\ApplyForm();
        $controller->index();
    });
    $r->addRoute('POST', BASE_URL . '/applyform-submit', function () use ($bot) {
        $controller = new \App\Controllers\Web\ApplyFormSubmit($bot);
        $controller->index();
    });



    $r->addRoute('GET', BASE_URL . '/viewform/{id:\d+}', function ($id) {
        $controller = new \App\Controllers\Web\ViewApplyForm();
        $controller->index($id);
    });



    $r->addRoute('GET', BASE_URL . '/rejectform/{id:\d+}', function ($id) {
        View::render('reject_form', [
            'approval_instance_id' => $id,
            'token' => $_GET['token'],
        ]);
    });
    $r->addRoute('POST', BASE_URL . '/rejectform', function () use($bot) {
        $controller = new \App\Controllers\Web\RejectFormSubmit($bot);
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
