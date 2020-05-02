<?php

namespace App\Models\Messages;

use App\Models\BotMessage;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class ConfirmDialogMessage extends BotMessage
{
    private $msg = "";
    private $actions = [];

    public function __construct($msg, array $actions)
    {
        $this->msg = $msg;
        $this->actions = $actions;
    }

    function getMessageBuilder(): MessageBuilder
    {
        $actions = [];
        foreach ($this->actions as $button => $command) {
            $actions[] = new MessageTemplateActionBuilder(
                $button, $command
            );
        }
        return new TemplateMessageBuilder($this->msg, new ConfirmTemplateBuilder(
            $this->msg, $actions,
        ));
    }
}