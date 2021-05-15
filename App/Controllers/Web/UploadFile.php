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

        $file = $_FILES['fileToUpload'];

        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_type = $file['type'];

        $errors = [];
//        $file_ext = strtolower(end(explode('.', $file['name'])));
//
//        $extensions = array("jpeg", "jpg", "png");
//
//        if (in_array($file_ext, $extensions) === false) {
//            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
//        }
//
//        if ($file_size > 2097152) {
//            $errors[] = 'File size must be excately 2 MB';
//        }

        if (!empty($errors)) {
            print_r($errors);
            return;
        }

        move_uploaded_file($file_tmp, $this->getUserFileStoragePath($file_name, $user->id));

        $s = explode('.', $file_name);
        $ext = end($s);
        $filename = date('Ymd-His') . '.' . $ext;


        $rec = new FileInfo();
        $rec->filename = $filename;
        $rec->filename_original = $file_name;
        $rec->filetype = $ext;
        $rec->user_id = $user->id;
        $rec->created_at = date('Y-m-d H:i:s');
        $rec->save();
        
        echo "Upload Success";
    }

    function getUserFileStoragePath(string $filename, string $userId): string
    {
        if (!file_exists(ROOT_PATH . "/storage/user_files/$userId")) {
            mkdir(ROOT_PATH . "/storage/user_files/$userId", 0777, true);
        }
        return ROOT_PATH . "/storage/user_files/$userId/$filename";
    }
}