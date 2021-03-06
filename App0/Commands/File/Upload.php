<?php

namespace App\Commands\File;

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
        $this->logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/main-log.log', Logger::DEBUG));


        $this->logger->debug(json_encode($event));
    }


    public function canHandle(): bool
    {
        return isset($this->event['message']['type']) && in_array($this->event['message']['type'], ["file", "image"]);
    }

    public function getResponse(): Response
    {
        $logger = $this->logger;
        try {
            $logger->debug(json_encode($this->event));
            $done = false;
            $res = $this->bot->getMessageContent($this->event['message']['id']);
            if ($res->isSucceeded()) {
                $binaryData = $res->getRawBody();
                $headers = $res->getHeaders();
                $t = date('Ymd-His');
                switch ($headers['Content-Type']) {
                    case 'image/jpeg':
                        $filename = "$t.jpg";
                        break;
                    case 'image/png':
                        $filename = "$t.png";
                        break;
                    default:
                        $filename = $this->event['message']['fileName'] ?? $t;
                }
                $done = $this->controller->saveUserFile($filename, $binaryData, $this->userId);
            }
            $msg = $done ? 'Saved' : 'cannot upload this file';
            $message = new TextMessageBuilder($msg);
            return $this->bot->replyMessage($this->replyToken, $message);
        } catch (Exception $e) {
            $logger->alert($e->getMessage());
        }
    }
}