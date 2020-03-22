<?php

namespace App\Models\Input;

use App\Models\BotInput;

class BotInputCommand extends BotInput
{
    const REQUEST_FORM = 'request-form';

    private $str;
    private $type;

    public function __construct(string $str, string $type)
    {
        $this->str = $str;
        $this->type = $type;
    }

    function getRawInputString(): string
    {
        return $this->str;
    }

    function is(string $type): bool
    {
        return $this->type == $type;
    }
}
