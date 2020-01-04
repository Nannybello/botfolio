<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class Hi extends BaseCommands
{

    public function canHandle(): bool
    {
        return in_array($this->text, ['hi', 'Hi']);
    }

    public function getResponse(): Response
    {
        $message = new TextMessageBuilder("Hello World!");
        return $this->bot->replyMessage($this->replyToken, $message);
    }
}