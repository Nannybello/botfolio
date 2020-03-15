<?php

namespace App\Controllers\Web;

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
        $user = User::fromToken($token);

        $content = $this->formLoader->load('A1');
        View::render('apply_form', [
            'user' => $user,
            'formContent' => $content,
        ]);
    }
}