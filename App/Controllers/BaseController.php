<?php

namespace App\Controllers;

abstract class BaseController
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }


    public function index(): array
    {
    }
}