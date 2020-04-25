<?php

namespace App\Models\Messages;

use App\Models\BotMessage;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class TextMessage extends BotMessage
{
    private $msg = "";

    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    function getMessageBuilder(): MessageBuilder
    {
        return new TextMessageBuilder($this->msg);
    }
}