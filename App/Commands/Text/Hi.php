<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use App\Controllers\HiController;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class Hi extends BaseCommands
{

    private $controller;

    public function __construct()
    {
        $this->controller = new HiController();
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