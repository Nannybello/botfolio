<?php

namespace App\Router;

use App\Controllers\BaseController;
use App\Router\Handler\WebHookHandler;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebRouter
{
    public function route()
    {
        try {
            $paths = explode("/", $_SERVER['PATH_INFO']);
            $path = implode("\\", $paths);
            $class = "\\App\\Controllers\\Web\\{$path}Controller";

            /**
             * @var BaseController $controller
             */
            $controller = new $class();
            $controller->index();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}