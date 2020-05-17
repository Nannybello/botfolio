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

    public static function item(int $id, string $title, string $desc, string $action): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'desc' => $desc,
            'action' => $action,
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
                        "Select to Done", $item['action']//"cmd:training-complete:" . $item['id']
                    )
                ]
            );
        }, $this->items);

        // limit by LINE
        $items = array_slice($items, 0, 10);

        return new TemplateMessageBuilder($this->msg,
            new CarouselTemplateBuilder(
                $items
//                [
//                    $items[0],
//                    new CarouselColumnTemplateBuilder(
//                        "Title",
//                        "Desc",
//                        $thumbnail,
//                        [
//                            new MessageTemplateActionBuilder(
//                                "Mark as Complete", "test123"
//                            )
//                        ]
//                    )
//                ]
            )
        );
    }
}