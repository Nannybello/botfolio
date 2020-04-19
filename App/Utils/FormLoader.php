<?php

namespace App\Utils;

class FormLoader
{
    public function load(string $formName, array $data = [], string $ext = '.html'): string
    {
        $content = file_get_contents(BASE_PATH . '/forms/' . $formName . '.php');
        foreach ($data as $field => $value) {
            if (!is_string($value)) continue;
            $field = "{{{$field}}}";
            $value = "<span>$value</span>";
            $content = str_replace($field, $value, $content);
        }
        return $content;
    }
}