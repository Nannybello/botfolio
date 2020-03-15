<?php

namespace App\Models;

abstract class BotInput
{
    abstract function getRawInputString(): string;
}