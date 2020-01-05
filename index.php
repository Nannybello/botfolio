<?php

include 'autoload.php';

use App\Database\Models\LineUser;
use App\Router\ApiRouter;

$user = LineUser::query()->where('userId', '=', "a")->first();
var_dump($user);

$router = new ApiRouter();
$router->route();