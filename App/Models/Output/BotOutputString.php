<?php

namespace App\Models\Output;

use App\Models\BotOutput;

class BotOutputString extends BotOutput
{

    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    function getRawOutputString(): string
    {
        return $this->str;
    }
}