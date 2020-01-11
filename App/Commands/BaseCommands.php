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
        $this->fetchUser();
        $this->controller = $this->controller();
    }

    private function fetchUser()
    {
        //เช็คว่ามีอยู่ใน database มั้ย
        $user = LineUser::query()->where('lineUserId', '=', $this->lineUserId)->first();
        if ($user === null) {
            $user = new LineUser();
            $user->lineUserId = $this->lineUserId;
            $user->created_at = date('Y-m-d H:i:s');
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();
        } else {
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();
        }
        $this->userId = $user->id;
    }

    public function controller(): ?BaseController
    {
        return null;
    }

    public abstract function canHandle(): bool;

    public abstract function getResponse(): Response;


    public function matchHoldKeyWord(array $keywords, bool $ignoreCase = true): bool
    {
        $text = $ignoreCase ? strtolower($this->text) : $this->text;

        if ($ignoreCase) {
            $keywords = array_map(function ($k) {
                return strtolower($k);
            }, $keywords);
        }

        return in_array($text, $keywords);
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