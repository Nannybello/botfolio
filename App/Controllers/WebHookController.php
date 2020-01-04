<?php

namespace App\Controllers;


use App\Commands\BaseCommands;
use http\Exception\RuntimeException;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebHookController
{
    public function index(LINEBot $bot, array $event): Response
    {
        $replyToken = $this->getReplyToken($event);
       foreach ($this->getCommands()as $CommandName){
           /**
            * @var BaseCommands $command
            */
           $command = new $CommandName($bot, $event ,$replyToken);
           if ($command->canHandle()){
               return $command->getResponse();
           }
       }
       Throw new \Exception("No Handler command!");
    }

    private function getReplyToken(array $event): ?string
    {
        return $event['replyToken'];
    }

    private function getCommands(): array{
        return include ROOT_PATH .'/App/Config/commands.php';
    }
}


