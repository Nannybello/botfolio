<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include 'autoload.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow.log', Logger::DEBUG));
$logger->info("BEGIN", ["-----------------------------------------------------------"]);
$logger->info("CONTENT", [
    'GET' => $_GET,
    'POST' => $_POST,
    'header' => json_encode(getallheaders(), JSON_PRETTY_PRINT),
    'body' => json_encode(json_decode(file_get_contents('php://input')), JSON_PRETTY_PRINT),
]);