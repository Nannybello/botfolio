<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include 'autoload.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow.log', Logger::DEBUG));
$logger->log("BEGIN", "-----------------------------------------------------------");
$logger->info("CONTENT", [
    'GET' => $_GET,
    'POST' => $_POST,
    'header' => getallheaders(),
    'body' => file_get_contents('php://input'),
]);