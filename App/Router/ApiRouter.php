<?php

namespace App\Router;

use App\Router\Handler\WebHookHandler;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ApiRouter
{
    public function route()
    {
        echo "<pre>";
        print_r($_SERVER);
    }
}