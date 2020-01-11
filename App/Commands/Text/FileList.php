<?php

namespace App\Commands\Text;

use App\Commands\BaseCommands;
use App\Controllers\BaseController;
use App\Controllers\FileController;
use App\Controllers\HiController;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class FileList extends BaseCommands
{

    public function controller(): ?BaseController
    {
        return new FileController($this->userId);
    }

    public function canHandle(): bool
    {
        return $this->matchHoldKeyWord(['file', 'files']);
    }

    public function getResponse(): Response
    {
        $files = $this->controller->index();
        if ($files) {
            $output = '';
            foreach ($files as $file) {
                $output .= "- " . $file->filename . "\n";
            }
        } else {
            $output = 'ยังไม่มีไฟล์';
        }

        $message = new TextMessageBuilder($output);
        return $this->bot->replyMessage($this->replyToken, $message);
    }
}