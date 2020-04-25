<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("ROOT_PATH", __DIR__);

ini_set("log_errors", 1);
ini_set("error_log", ROOT_PATH . "/storage/php-error.log");
error_log(date('Y-m-d H:i:s') . "------------------------------------------------------------------");

spl_autoload_register(function ($path) {
    if (strpos($path, "App") === 0) {
        $paths = explode("\\", $path);
        $path = implode(DIRECTORY_SEPARATOR, $paths);
        include_once ROOT_PATH . "/$path.php";
    }
});

require_once(ROOT_PATH . '/vendor/autoload.php');

require_once(ROOT_PATH . '/App/Database/init.php');
