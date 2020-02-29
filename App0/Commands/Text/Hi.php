<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use App\Controllers\BaseController;
use App\Controllers\HiController;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class Hi extends BaseCommands
{

    public function controller(): ?BaseController
    {
        return new HiController($this->userId);
    }

    public function canHandle(): bool
    {
        return $this->matchHoldKeyWord(['hi', 'Hi']);
    }

    public function getResponse(): Response
    {
        [$msg] = $this->controller->index();
        $message = new TextMessageBuilder($msg);
        return $this->bot->replyMessage($this->replyToken, $message);
    }
}