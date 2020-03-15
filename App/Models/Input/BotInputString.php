<?php

namespace App\Models\Input;

use App\Models\BotInput;

class BotInputString extends BotInput
{
    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    function getRawInputString(): string
    {
        return $this->str;
    }
}