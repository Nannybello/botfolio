<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalType;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\User;
use App\Utils\FormLoader;
use App\Views\View;

class ApplyForm
{
    private $formLoader;

    public function __construct()
    {
        $this->formLoader = new FormLoader();
    }

    public function index()
    {
        $token = $_GET['token'];
        $approvalTypeId = $_GET['approval_type_id'];

        $approvalType = ApprovalType::query()->findOrFail($approvalTypeId);
        $formType = FormType::of($approvalType)->first();
        $user = User::fromToken($token);
        $files = FileInfo::of($user);

        $usersH4 = User::query()->where('user_type_id', '=', 4)->get();

        $content = $this->formLoader->load($formType->name);
        View::render('apply_form', [
            'user' => $user,
            'formContent' => $content,
            'files' => $files,
            'token' => $token,
            'approval_type_id' => $approvalTypeId,
            'form_type' => $formType,
            'h4_list' => $usersH4,
        ]);
    }
}