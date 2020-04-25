<?php

namespace App\Models;

use App\Models\Intents\DefaultFallbackIntent;
use App\Models\Intents\TrainingRequestIntent;

abstract class BotIntent
{
    public $intentName;
    public $intentDisplayName;
    public $fulfillmentText;
    public $replyToken;
    public $lineUserId;

    /**
     * BotIntent constructor.
     * @param $intentName
     * @param $intentDisplayName
     * @param $fulfillmentText
     * @param $replyToken
     * @param $lineUserId
     */
    public function __construct($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId)
    {
        $this->intentName = $intentName;
        $this->intentDisplayName = $intentDisplayName;
        $this->fulfillmentText = $fulfillmentText;
        $this->replyToken = $replyToken;
        $this->lineUserId = $lineUserId;
    }

    public static function build($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId): ?BotIntent
    {
        switch ($intentDisplayName) {
            case 'Training Request Intent':
                return new TrainingRequestIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId);
        }
        return new DefaultFallbackIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId);
    }



}