<?php

namespace App\Models\Messages;

use App\Models\BotMessage;
use LINE\LINEBot\MessageBuilder;

use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class CarouselMessage extends BotMessage
{
    private $msg;
    private $items = [];

    public static function item(int $id, string $title, string $desc): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'desc' => $desc,
        ];
    }

    public function __construct(string $msg, array $items)
    {
        $this->msg = $msg;
        $this->items = $items;
    }

    function getMessageBuilder(): MessageBuilder
    {
        $thumbnail = "https://botfolio.beautyandballoon.com/storage/file-iconpng.png";
        $items = array_map(function ($item) use ($thumbnail) {
            return new CarouselColumnTemplateBuilder(
                $item['title'],
                $item['desc'],
                $thumbnail,
                [
                    new MessageTemplateActionBuilder(
                        "Select to Done", "cmd:training-complete:" . $item['id']
                    )
                ]
            );
        }, $this->items);

        return new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("img",
            new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder(
                [
                    new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                        "Title",
                        "Desc",
                        $thumbnail,
                        [
                            new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                                "Download", "test123"
                            )
                        ]
                    )
                ]
            )
        );

        return new TemplateMessageBuilder($this->msg,
            new CarouselTemplateBuilder(
                $items,
            )
        );
    }
}