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
    public $parameters = [];

    /**
     * BotIntent constructor.
     * @param $intentName
     * @param $intentDisplayName
     * @param $fulfillmentText
     * @param $replyToken
     * @param $lineUserId
     * @param $parameters
     */
    public function __construct($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters)
    {
        $this->intentName = $intentName;
        $this->intentDisplayName = $intentDisplayName;
        $this->fulfillmentText = $fulfillmentText;
        $this->replyToken = $replyToken;
        $this->lineUserId = $lineUserId;
        $this->parameters = $parameters;
    }

    public static function build($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters = []): ?BotIntent
    {
        if ($intentDisplayName == 'Training Request Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/2ca195eb-cf63-45f2-b453-edc00ca07bc3') {
            return new TrainingRequestIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
        }

        return new DefaultFallbackIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
    }


}