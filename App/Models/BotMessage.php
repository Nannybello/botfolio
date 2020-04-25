<?php

namespace App\Models;

use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

abstract class BotMessage
{
    abstract function getMessageBuilder(): MessageBuilder;
}