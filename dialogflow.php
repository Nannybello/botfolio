<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include 'autoload.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/main-log.log', Logger::DEBUG));
$logger->info("BEGIN", "-----------------------------------------------------------");
$logger->info("GET", json_encode($_GET));
$logger->info("POST", json_encode($_POST));