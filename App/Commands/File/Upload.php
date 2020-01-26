<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use App\Controllers\BaseController;
use App\Controllers\FileController;
use App\Controllers\HiController;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Upload extends BaseCommands
{

    private $controller;
    private $logger;

    /**
     * Upload constructor.
     * @param LINEBot $bot
     * @param array $event
     * @param string $replyToken
     */
    public function __construct(LINEBot $bot, array $event, string $replyToken)
    {
        parent::__construct($bot, $event, $replyToken);
        $this->controller = new FileController($this->userId);

        $this->logger = new Logger('channel-name');
        $this->logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/upload-file.log', Logger::DEBUG));


        $this->logger->debug(json_encode($event));
    }


    public function canHandle(): bool
    {
        return isset($this->event['message']['type']) && $this->event['message']['type'] == "file";
    }

    public function getResponse(): Response
    {
        $logger = $this->logger;
        try {
            $logger->debug(json_encode($this->event));
            $done = false;
            $filename = $this->event['message']['fileName'];
            $res = $this->bot->getMessageContent($this->event['message']['id']);
            if ($res->isSucceeded()) {
                $binaryData = $res->getRawBody();
                $done = $this->controller->saveFile($filename, $binaryData);
            }
            $msg = $done ? 'Saved' : 'cannot upload this file';
            $message = new TextMessageBuilder($msg);
            return $this->bot->replyMessage($this->replyToken, $message);
        } catch (Exception $e) {
            $logger->alert($e->getMessage());
        }
    }
}