<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("ROOT_PATH", __DIR__);

require_once(ROOT_PATH . '/vendor/autoload.php');
require_once(ROOT_PATH . '/line_bot.php');

spl_autoload_register(function ($path) {
    $paths = explode("\\", $path);
    $path = implode(DIRECTORY_SEPARATOR, $paths);
    include_once ROOT_PATH . "/$path.php";
});

use App\Router\LineWebHookRouter;

$router = new LineWebHookRouter();
$router->route();