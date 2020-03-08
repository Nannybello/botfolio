<?php

namespace App\Views;

class View
{
    /**
     * @var \GlobalView
     */
    public static $globalView;

    public static function compose(string $viewName, array $params = []): string
    {
        return self::$globalView->compose($viewName, $params);
    }

    public static function render(string $viewName, array $params = [])
    {
        echo self::compose($viewName, $params);
    }
}

View::$globalView = new \GlobalView();