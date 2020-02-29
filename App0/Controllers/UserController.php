<?php


namespace App\Controllers;


use App\Database\Models\LineUser;

class UserController extends BaseController
{
    public function index(): array
    {
        $user = LineUser::all()->toArray();
        return $user;
    }
}