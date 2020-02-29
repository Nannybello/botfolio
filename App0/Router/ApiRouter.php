<?php

namespace App\Router;

use App\Controllers\BaseController;
use App\Router\Handler\WebHookHandler;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ApiRouter
{
    public function route()
    {
        try {
            $paths = explode("/", $_SERVER['PATH_INFO']);
            if (array_pop($paths) != 'api') return false;

            $path = implode("\\", $paths);
            $class = "\\App\\Controllers{$path}Controller";

            /**
             * @var BaseController $controller
             */
            $controller = new $class(intval($_GET['id']));

            header('Content-Type: application/json');
            echo json_encode($controller->index());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}