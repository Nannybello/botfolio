<?php

include 'autoload.php';

use App\Router\LineWebHookRouter;

$router = new LineWebHookRouter();
$router->route();