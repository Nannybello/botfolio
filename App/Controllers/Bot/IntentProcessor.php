<?php


namespace App\Controllers\Bot;


use App\Models\BotIntent;
use App\Models\BotMessage;
use App\Models\Intents\TrainingRequestIntent;
use App\Models\Messages\TextMessage;

class IntentProcessor
{
    public function process(BotIntent $intent): BotMessage
    {

        if($intent instanceof TrainingRequestIntent){

        }
        $defaultText = "{$intent->fulfillmentText}\nintent: {$intent->intentName}({$intent->intentDisplayName})";
        return new TextMessage($defaultText);
    }
}