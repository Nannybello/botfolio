<?php

namespace App\Controllers\Web;

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
        $content = $this->formLoader->load('A1');
        View::render('test1', [
            'x' => 123,
            'formContent' => $content,
        ]);
    }
}