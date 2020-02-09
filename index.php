<?php

include 'autoload.php';

use App\Router\ApiRouter;
use App\Router\WebRouter;

$api = new ApiRouter();
$web = new WebRouter();

$res = $api->route();

if (!$res) {
    $res = $web->route();
}

if (!$res) {
    echo '404';
}

