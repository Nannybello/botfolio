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
use function App\Helpers\userThumbnailUrl;

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

            $carouseColumns = array_map(function ($file) use($logger) {
                $url = userFileUrl($file['filename'], $this->userId);
                $thumb_url = userThumbnailUrl($file['filename'], $this->userId);
                $logger->debug(json_encode([
                    '$file' => $file,
                    '$url' => $url,
                    '$thumb_url' => $thumb_url,
                ]));
//                $thumb_url = "https://botfolio.beautyandballoon.com/storage/user_files/1/20200111-164500.jpg";
//                $url = "https://botfolio.beautyandballoon.com/storage/user_files/1/20200111-164500.jpg";

                if(in_array($file['filetype'], ['jpg', 'png'])){
                    $thumb_url = $url;
                }

                return new LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
                    $file['filename_original'],
                    $file['filetype'] . ", upload at " . $file['created_at'],
                    $thumb_url,
                    [
                        new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                            "Download", makeLink($url)
                        ),
                        new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                            "Delete", DeleteFile::getCommand($file['id'])
                        ),
                        new LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
                            "Update", makeLink($url)
                        ),
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