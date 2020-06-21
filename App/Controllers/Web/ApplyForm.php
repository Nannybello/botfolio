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
        $parentId = $_GET['parent_id'] ?? null;

        $approvalType = ApprovalType::query()->findOrFail($approvalTypeId);
        $formType = FormType::of($approvalType)->first();
        $user = User::fromToken($token);
        $files = FileInfo::of($user);

        $followUpRange = [];

        if ($formType->name == 'A1') {
            $usersH1 = User::query()->where('user_type_id', '=', 1)->get();
            $usersH2 = User::query()->where('user_type_id', '=', 2)->get();
            $usersH3 = User::query()->where('user_type_id', '=', 3)->get();
            $usersH4 = User::query()->where('user_type_id', '=', 4)->get();
        } elseif ($formType->name == 'A50') {
            $usersH1 = User::query()->where('user_type_id', '=', 1)->get();
            $usersH4 = User::query()->where('user_type_id', '=', 4)->get();
            $followUpRange = [6, 9];
        } elseif ($formType->name == 'A51') {
            $usersH1 = User::query()->where('user_type_id', '=', 1)->get();
            $followUpRange = [6, 9];
        }

        $content = $this->formLoader->load($formType->name);

        $prefields = [
            'courseName' => $_GET['data-course-name'] ?? '',
        ];

        View::render('apply_form', [
            'user' => $user,
            'formContent' => $content,
            'files' => $files,
            'token' => $token,
            'approval_type_id' => $approvalTypeId,
            'form_type' => $formType,
            'h1_list' => $usersH1 ?? null,
            'h2_list' => $usersH2 ?? null,
            'h3_list' => $usersH3 ?? null,
            'h4_list' => $usersH4 ?? null,
            'prefields' => $prefields,
            'followUpRange' => $followUpRange,
            'parent_id' => $parentId,
        ]);
    }
}