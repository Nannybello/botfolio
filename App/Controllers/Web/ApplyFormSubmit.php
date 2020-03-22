<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalInstance;
use App\Database\Models\Attachment;
use App\Database\Models\FileInfo;
use App\Database\Models\User;
use App\Utils\FormLoader;
use App\Views\View;

class ApplyFormSubmit
{

    public function __construct()
    {
    }

    public function index()
    {
        $rawData = $_POST;
        $user = User::fromToken($rawData['token']);
        $approvalTypeId = $rawData['approval_type_id'];

//        echo '<pre>';
//        $data = [];
//        foreach ($rawData as $field => $value) {
//            echo "$field - $value";
//            if (is_string($field) && strpos($field, 'data_') == 0) {
//                $f = str_replace($field, 'data_', '');
//                $data[$f] = $value;
//            }
//        }
//        print_r($data);

        $approvalInstance = new ApprovalInstance();
        $approvalInstance->user_id = $user->id;
        $approvalInstance->approval_type_id = $approvalTypeId;
        $approvalInstance->save();

        echo "id: " . $approvalInstance->id;

        foreach ($rawData['attach_files'] as $fileId) {
            $attach = new Attachment();
            $attach->file_id = $fileId;
            $attach->approval_instance_id = $approvalInstance->id;
            $attach->save();
        }
    }
}