<?php

namespace App\Models\Output;

use App\Models\BotOutput;

class BotOutputString extends BotOutput
{

    private $strLines = [];

    public function __construct($str)
    {
        $this->strLines[] = $str;
    }

    function getRawOutputString(): string
    {
        return join("\n", $this->strLines);
    }
}