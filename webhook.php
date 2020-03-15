<?php

use App\Controllers\Bot\Main;
use App\Database\Models\User;
use App\Models\Input\BotInputString;
use App\Models\Output\BotOutputString;

require 'autoload.php';

define('BASE_URL', '/botfolio');
define('BASE_PATH', __DIR__);

$main = new Main();

$input = new BotInputString('hi');
$user = User::query()->find(2);

$output = $main->index($input, $user);
if ($output instanceof BotOutputString) {
    print_r($output->getRawOutputString());
}
