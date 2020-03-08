<?php

namespace App\Utils;

class FormLoader
{
    public function load(string $formName, string $ext = '.html'): string
    {
        return file_get_contents(BASE_PATH . '/forms/' . $formName . '.php');
    }
}