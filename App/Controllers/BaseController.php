<?php

namespace App\Controllers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class BaseController
{
    protected $userId;
    protected $logger;

    public function __construct($userId = null)
    {
        $this->userId = $userId;


        $this->logger = new Logger('channel-name');
        $this->logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/upload-file.log', Logger::DEBUG));

    }


    public function index(): array
    {
    }
}