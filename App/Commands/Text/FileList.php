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
use function App\Helpers\makeLink;
use function App\Helpers\userFileUrl;

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


        if (!$files) {
            $output = 'ยังไม่มีไฟล์';
            $message = new TextMessageBuilder($output);
            return $this->bot->replyMessage($this->replyToken, $message);
        }


        try {

            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
            $logger->alert(json_encode([
                $files[0]['filename_original'],
                $files[0]['filetype'] . ", upload at " . $files[0]['created_at'],
                userFileUrl($files[0]['filename'], $this->userId),
                makeLink(userFileUrl($files[0]['filename'], $this->userId)),
            ]));

            $carouseColumns = array_map(function ($file) {
                $url = userFileUrl($file['filename'], $this->userId);
                return new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                    $file['filename_original'],
                    $file['filetype'] . ", upload at " . $file['created_at'],
                    $url,
                    [
                        new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                            "Download", makeLink($url)
                        )
                    ]
                );
            }, $files);

            $textMessageBuilder = new LINEBot\MessageBuilder\TemplateMessageBuilder("img",
                new LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($carouseColumns)
            );
            return $this->bot->replyMessage($this->replyToken, $textMessageBuilder);
        } catch (Exception $e) {
            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
            $logger->alert($e->getMessage());
        }
    }
}