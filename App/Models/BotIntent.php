<?php

namespace App\Models;

use App\Models\Intents\ApproveFormIntent;
use App\Models\Intents\DefaultFallbackIntent;
use App\Models\Intents\RequestFromTypeIntent;
use App\Models\Intents\TrainingCompleteIntent;
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

        if ($intentDisplayName == 'Approve Form Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/792137b1-34ed-4dd5-8693-fd8cb6da77be') {
            return new ApproveFormIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
        }

        if ($intentDisplayName == 'Request From Type Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/b1933b65-537e-4611-b8d4-7704bb94b3fb') {
            return new RequestFromTypeIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
        }

        if ($intentDisplayName == 'Training Complete Intent' || $intentName == ' projects/botfolio-jnxcqb/agent/intents/81732c6d-d481-44c6-b1f1-86ca8c40ba46') {
            return new TrainingCompleteIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
        }

        return new DefaultFallbackIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $parameters);
    }


}