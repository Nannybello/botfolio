<?php


namespace App\Commands;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;

class NotFoundCommand extends DefaultCommand
{
    public function getResponse(): Response
    {
        // TODO: Implement getResponse() method.
        $message = new TextMessageBuilder("Command Not Found");
        $this->bot->replyMessage($this->replyToken, $message);
        return parent::getResponse(); // TODO: Change the autogenerated stub
    }
}