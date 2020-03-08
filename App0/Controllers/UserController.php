<?php


namespace App\Controllers;


use App\Database\Models\User;

class UserController extends BaseController
{
    public function index(): array
    {
        $user = User::all()->toArray();
        return $user;
    }
}