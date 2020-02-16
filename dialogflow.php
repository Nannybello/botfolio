<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include 'autoload.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow.log', Logger::DEBUG));
$logger->info("BEGIN", ["-----------------------------------------------------------"]);
$logger->info("CONTENT", [$_GET, $_POST]);