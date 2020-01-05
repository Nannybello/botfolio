<?php

namespace App\Commands;


use App\Controllers\BaseController;
use App\Database\Models\LineUser;
use LINE\LINEBot;
use LINE\LINEBot\Response;

abstract class BaseCommands
{
    protected $userId;
    protected $lineUserId;
    protected $controller;
    protected $bot;
    protected $event;
    protected $replyToken;
    protected $type;
    protected $text = null;

    public function __construct(LINEBot $bot, array $event, string $replyToken)
    {
        $this->bot = $bot;
        $this->event = $event;
        $this->replyToken = $replyToken;

        $this->type = $event['type'];
        if ($this->type == 'message') {
            $this->text = $event['message']['text'];
        }

        $this->lineUserId = @strval($event['source']['userId']);

        $this->controller = $this->controller();
    }

    private function fetchUser()
    {
        $user = LineUser::query()->where('userId', '=', $this->lineUserId)->first();
    }

    public function controller(): ?BaseController
    {
        return null;
    }

    public abstract function canHandle(): bool;

    public abstract function getResponse(): Response;


    public function matchHoldKeyWord(array $keywords): bool
    {
        return in_array($this->text, $keywords);
    }

    public function hasKeyWord(array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (stripos($this->text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
}