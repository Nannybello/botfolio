<?php

//echo "test botfolio";
//include 'public/index.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/vendor/autoload.php');

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(__DIR__.'/storage/app.log', Logger::DEBUG));
//$logger->info('This is a log! ^_^ ');
//$logger->warning('This is a log warning! ^_^ ');
//$logger->error('This is a log error! ^_^ ');

$content = file_get_contents('php://input');

$logger->info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));