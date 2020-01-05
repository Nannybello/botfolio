<?php

include 'autoload.php';

use App\Database\Models\LineUser;
use App\Router\ApiRouter;

$user = LineUser::query()->where('userId', '=', "U8d979fc1b1a2b976ec6fc65c54e288f1")->first();
var_dump($user);

$router = new ApiRouter();
$router->route();