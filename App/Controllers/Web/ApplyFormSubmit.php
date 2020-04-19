<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalType;
use App\Database\Models\Attachment;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\User;
use App\Utils\FormLoader;
use App\Views\View;

class ApplyFormSubmit
{
    private $formLoader;

    public function __construct()
    {
        $this->formLoader = new FormLoader();
    }

    public function index()
    {
        $rawData = $_POST;
        $user = User::fromToken($rawData['token']);
        $approvalTypeId = $rawData['approval_type_id'];
        $approverId = $rawData['approver_id'];

        //echo '<pre>';
        $data = [];
        foreach ($rawData as $field => $value) {
            if (is_string($field) && strpos($field, 'data_') == 0) {
                $f = str_replace('data_', '', $field);
                $data[$f] = $value;
            }
        }
        //print_r($data);

        $approvalType = ApprovalType::query()->findOrFail($approvalTypeId);
        $formType = FormType::of($approvalType)->first();
        $content = $this->formLoader->load($formType->name, $data);

        View::render('apply_form_submit', [
            'formContent' => $content,
            'data' => $data,
        ]);


        $approvalInstance = new ApprovalInstance();
        $approvalInstance->user_id = $user->id;
        $approvalInstance->approval_type_id = $approvalTypeId;
        $approvalInstance->data = json_encode($data);
        $approvalInstance->created_at = date('Y-m-d H:i:s');
        $approvalInstance->approver_id = $approverId;
        $approvalInstance->save();

        echo "id: " . $approvalInstance->id;

        $files = empty($rawData['attach_files']) ? [] : $rawData['attach_files'];
        foreach ($files as $fileId) {
            $attach = new Attachment();
            $attach->file_id = $fileId;
            $attach->approval_instance_id = $approvalInstance->id;
            $attach->save();
        }
    }
}