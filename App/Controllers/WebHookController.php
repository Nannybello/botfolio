<?php

namespace App\Controllers;


use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class WebHookController
{
    public function index(LINEBot $bot, array $event): Response
    {
        $replyToken = $this->getReplyToken($event);
        $todo = $this->getTextMessage($event);

        switch ($todo) {
            case "hi":
                return $this->hi($bot, $event, $replyToken);
            case "info":
                return $this->info($bot, $event, $replyToken);
            case "website":
                return $this->website($bot, $event, $replyToken);
            case "confirm":
                return $this->confirmBox($bot, $event, $replyToken);
        }

        $textMessageBuilder = new TextMessageBuilder("ไม่พบคำสั่งนี้ กรุณาลองใหม่อีกครั้ง");
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }

    private function getReplyToken(array $event): ?string
    {
        return $event['replyToken'];
    }

    private function getTextMessage(array $event): ?string
    {
        $type = $event['type'];
        if ($type != "message") return null;
        return $event['message']['text'];
    }

    private function hi(LINEBot $bot, array $event, string $replyToken): Response
    {
        $textMessageBuilder = new TextMessageBuilder("Hello, I'm Botfolio");
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }

    private function info(LINEBot $bot, array $event, string $replyToken): Response
    {
        $textMessageBuilder = new TextMessageBuilder(json_encode($event));
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }

    private function website(LINEBot $bot, array $event, string $replyToken): Response
    {
        $textMessageBuilder = new TextMessageBuilder("https://www.beautyandballoon.com");
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }

    private function confirmBox(LINEBot $bot, array $event, string $replyToken): Response
    {
        $textMessageBuilder = new TextMessageBuilder("1 + 1 = ?",
        new LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder(
            "1 + 1 = ?",
            [
                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                    "1", "Wrong"
                ),
                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                    "2", "Correct"
                )
            ]
        ));
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }
}


