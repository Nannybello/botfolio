<?php


namespace App\Controllers;


class HiController extends BaseController
{
    public function index(): array
    {
        return [
            'Hello World!, user id is ' . $this->userId
        ];
    }
}