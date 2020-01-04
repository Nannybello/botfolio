<?php


namespace App\Commands;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class DefaultCommand extends BaseCommands
{

    public function canHandle(): bool
    {
        return true;
    }

    public function getResponse(): Response
    {
        $message = new TextMessageBuilder("welcome to BotFolio");
        return $this->bot->replyMessage($this->replyToken, $message);
    }
}