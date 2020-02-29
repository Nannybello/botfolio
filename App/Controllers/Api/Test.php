<?php

namespace App\Controllers\Api;

use App\Database\Models\FileInfo;

class Test
{
    public function index(){
        echo 'test = ok!';
        $files = FileInfo::all()->toArray();
        echo json_encode($files);
    }
}