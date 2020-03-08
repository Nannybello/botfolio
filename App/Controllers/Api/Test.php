<?php

namespace App\Controllers\Api;

use App\Database\Models\FileInfo;
use App\Database\Models\User;

class Test
{
    public function index(){
        echo 'test = ok!';
        $x = User::fromToken('aaa');
        echo json_encode($x);
    }
}