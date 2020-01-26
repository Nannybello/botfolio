<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use App\Controllers\BaseController;
use App\Controllers\FileController;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use LINE\LINEBot;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileList extends BaseCommands
{

    public function controller(): ?BaseController
    {
        return new FileController($this->userId);
    }

    public function canHandle(): bool
    {
        return $this->matchHoldKeyWord(['file', 'files', 'my file', 'ไฟล์', 'เรียกไฟล์', 'ไฟล์ทั้งหมด']);
    }

    public function getResponse(): Response
    {
        $files = $this->controller->index();

        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
        $logger->info(json_encode($files));


        try {
            $textMessageBuilder = new LINEBot\MessageBuilder\TemplateMessageBuilder("img",
                new LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder(
                    [
                        new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                            "Title",
                            "Desc",
                            "https://botfolio.beautyandballoon.com/storage/user_files/1/20200111-164500.jpg",
                            [
                                new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                                    "Download", "https://botfolio.beautyandballoon.com/storage/user_files/1/20200111-164500.jpg"
                                )
                            ]
                        ),
                    ]
                )
            );
            return $this->bot->replyMessage($this->replyToken, $textMessageBuilder);
        } catch (Exception $e) {
            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
            $logger->alert($e->getMessage());
        }

        if (!$files) {
            $output = 'ยังไม่มีไฟล์';
            $message = new TextMessageBuilder($output);
            return $this->bot->replyMessage($this->replyToken, $message);
        }


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
            return $this->bot->replyMessage($this->replyToken, $textMessageBuilder);
        } catch (Exception $e) {
            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
            $logger->alert($e->getMessage());
        }
    }
}