<?php


namespace App\Controllers\Bot;


use App\Database\Models\User;
use App\Models\BotAction;
use App\Models\BotIntent;
use App\Models\BotMessage;
use App\Models\Intents\TrainingRequestIntent;
use App\Models\Messages\TextMessage;
use LINE\LINEBot;

class IntentProcessor
{
    function getUser(string $lineUserId): ?User
    {
        return User::query()->where('lineUserId', '=', $lineUserId)->first();
    }

    public function process(LINEBot $bot, BotIntent $intent)
    {

        $defaultText = "\n---\n" .
            "fulfillment: {$intent->fulfillmentText}\n" .
            "params: " . json_encode($intent->parameters) . "\n" .
            "intent: {$intent->intentName} ({$intent->intentDisplayName})\n" .
            "lineUserId: {$intent->lineUserId}";

        $replyToken = $intent->replyToken;

        if ($intent instanceof TrainingRequestIntent) {
            $user = $this->getUser($intent->lineUserId);
            $token = $user ? $user->token : '';
            $url = "https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=1&data-course-name=" . urlencode($intent->parameters['data-course-name']);

            $reply = new TextMessage($url . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());

            $bot->pushMessage("U8d979fc1b1a2b976ec6fc65c54e288f1", new TextMessageBuilder('มีคนส่งอะไรซักอย่างมาให้แอพพรุ๊ปนะ'));
        }


        $bot->replyMessage($replyToken, (new TextMessage($defaultText))->getMessageBuilder());
    }
}