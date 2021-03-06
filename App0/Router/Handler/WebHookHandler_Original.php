<?php

namespace App\Router\Handler;


use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebHookHandler_Original
{
    public function index(LINEBot $bot, array $event): Response
    {
        $replyToken = $this->getReplyToken($event);
        $todo = $this->getTextMessage($event);

        if (isset($event['message']['type']) && $event['message']['type'] == "file") {
            $todo = "file";
        }

        switch ($todo) {
            case "hi":
                return $this->hi($bot, $event, $replyToken);
            case "info":
                return $this->info($bot, $event, $replyToken);
            case "website":
                return $this->website($bot, $event, $replyToken);
            case "confirm":
                return $this->confirmBox($bot, $event, $replyToken);
            case "img":
                return $this->img($bot, $event, $replyToken);
            case "file":
                return $this->file($bot, $event, $replyToken);
        }

        $textMessageBuilder = new TextMessageBuilder("ไม่พบคำสั่งนี้ กรุณาลองใหม่อีกครั้ง -- " . json_encode($event));
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

    private function file(LINEBot $bot, array $event, string $replyToken): Response
    {
        $res = $bot->getMessageContent($event['message']['id']);
        if ($res->isSucceeded()) {
            $binaryData = $res->getRawBody();
            file_put_contents(ROOT_PATH . "/storage/" . $event['message']['fileName'], $binaryData);
        }
        $textMessageBuilder = new TextMessageBuilder("Saved!");
        return $bot->replyMessage($replyToken, $textMessageBuilder);
    }

    private function confirmBox(LINEBot $bot, array $event, string $replyToken): Response
    {
        $message = new LINEBot\MessageBuilder\TemplateMessageBuilder("1 + 1 = ?",
            new LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder(
                "เลือกคำสั่ง",
                [
                    new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                        "welcome", "hi"
                    ),
                    new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                        "ข้อมูล", "info"
                    )
                ]
            ));
        return $bot->replyMessage($replyToken, $message);
    }

    private function img(LINEBot $bot, array $event, string $replyToken): Response
    {
        try {
            $textMessageBuilder = new LINEBot\MessageBuilder\TemplateMessageBuilder("img",
                new LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder(
                    [
                        new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                            "Title",
                            "Desc",
                            "https://botfolio.beautyandballoon.com/storage/20200111-164500.jpg",
                            [
                                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                                    "Download", "https://botfolio.beautyandballoon.com/storage/20200111-164500.jpg"
                                )
                            ]
                        ),
                        new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                            "Title",
                            "Desc",
                            "https://botfolio.beautyandballoon.com/storage/20200111-164500.jpg",
                            [
                                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                                    "Download", "https://botfolio.beautyandballoon.com/storage/20200111-164500.jpg"
                                )
                            ]
                        ),
                        new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                            "Title",
                            "Desc",
                            "",
                            [
                                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                                    "Download", "https://botfolio.beautyandballoon.com/storage/20200111-164500.jpg"
                                )
                            ]
                        )
                    ]
                )
            );
            return $bot->replyMessage($replyToken, $textMessageBuilder);
        } catch (Exception $e) {
            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/main-log.log', Logger::DEBUG));
            $logger->alert($e->getMessage());
        }
    }
}


