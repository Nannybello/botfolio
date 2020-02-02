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

class DeleteFile extends BaseCommands
{

    public function controller(): ?BaseController
    {
        return new FileController($this->userId);
    }

    public static function getCommand($fileId = ''): string
    {
        return "delete-file=$fileId";
    }

    public function canHandle(): bool
    {
        return strpos($this->text, self::getCommand()) == 0;
    }

    public function getResponse(): Response
    {
        try {
            $fileController = new FileController();
            $fileId = intval(str_replace(self::getCommand(), '', $this->text));
            $fileController->delete($fileId);
            $message = new TextMessageBuilder('File Deleted!');
            return $this->bot->replyMessage($this->replyToken, $message);
        } catch (Exception $e) {
            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/command.log', Logger::DEBUG));
            $logger->alert($e->getMessage());
        }
    }
}