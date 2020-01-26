<?php


namespace App\Commands;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use function App\Helpers\makeLink;

class NoActionCommand extends BaseCommands
{

    private function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function canHandle(): bool
    {
        return $this->startsWith($this->text, makeLink(''));
    }

    public function getResponse(): Response
    {
        return null;
    }
}