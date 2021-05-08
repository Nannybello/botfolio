<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalType;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\User;
use App\Utils\FormLoader;
use App\Views\View;

class UploadFile
{
    private $formLoader;

    public function __construct()
    {
        $this->formLoader = new FormLoader();
    }

    public function index()
    {
        $token = $_GET['token'];
        $user = User::fromToken($token);

        View::render('upload_file', [
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function uploadfile()
    {
        $token = $_GET['token'];
        $user = User::fromToken($token);
        print_r($user);
        print_r($_FILES);
    }   
    
    function getUserFileStoragePath(string $filename, string $userId): string
    {
        return ROOT_PATH . "/storage/user_files/$userId/$filename";
    }
}