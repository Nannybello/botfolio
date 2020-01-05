<?php

namespace App\Controllers;

abstract class BaseController
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }


    public abstract function index(): array;
}