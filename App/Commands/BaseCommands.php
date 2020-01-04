<?php

namespace App\Commands;


use LINE\LINEBot;
use LINE\LINEBot\Response;

abstract class BaseCommands
{
    protected $bot;
    protected $event;
    protected $replyToken;
    protected $type;
    protected $text = null;

    public function __construct(LINEBot $bot, array $event, string $replyToken)
    {
        $this->bot = $bot;
        $this->event = $event;
        $this->replyToken = replyToken;

        $this->type = $event['type'];
        if ($this->type == 'message') {
            $this->text = $event['message']['text'];
        }
    }

    public abstract function canHandle(): bool;

    public abstract function getResponse(): Response;
}