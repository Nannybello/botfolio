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
    public $messageText;
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
    public function __construct($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters)
    {
        $this->intentName = $intentName;
        $this->intentDisplayName = $intentDisplayName;
        $this->fulfillmentText = $fulfillmentText;
        $this->replyToken = $replyToken;
        $this->lineUserId = $lineUserId;
        $this->messageText = $messageText;
        $this->parameters = $parameters;
    }

    public static function build($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters = []): ?BotIntent
    {
        if ($intentDisplayName == '2.0 Training Request Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/2ca195eb-cf63-45f2-b453-edc00ca07bc3') {
            return new TrainingRequestIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);
        }

        if ($intentDisplayName == '3.0 Approve Form Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/792137b1-34ed-4dd5-8693-fd8cb6da77be') {
            return new ApproveFormIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);
        }

        if ($intentDisplayName == '4.0 Request From Type Intent' || $intentName == 'projects/botfolio-jnxcqb/agent/intents/b1933b65-537e-4611-b8d4-7704bb94b3fb') {
            return new RequestFromTypeIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);
        }

        if ($intentDisplayName == '5.0 Training Complete Intent' || $intentName == ' projects/botfolio-jnxcqb/agent/intents/81732c6d-d481-44c6-b1f1-86ca8c40ba46') {
            return new TrainingCompleteIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);
        }

        return new DefaultFallbackIntent($intentName, $intentDisplayName, $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);
    }


}

/*
 * {
    "responseId": "d4f086cc-fda6-47ba-bf92-75775795e878-b4a98e7e",
    "queryResult": {
        "queryText": "\u0e44\u0e1e\u0e49\u0e33\u0e30\u0e48\u0e31\u0e1e",
        "action": "input.unknown",
        "parameters": {},
        "allRequiredParamsPresent": true,
        "fulfillmentText": "\u0e09\u0e31\u0e19\u0e44\u0e21\u0e48\u0e40\u0e02\u0e49\u0e32\u0e43\u0e08\u0e04\u0e48\u0e30",
        "fulfillmentMessages": [
            {
                "text": {
                    "text": [
                        "\u0e09\u0e31\u0e19\u0e44\u0e21\u0e48\u0e40\u0e02\u0e49\u0e32\u0e43\u0e08\u0e04\u0e48\u0e30"
                    ]
                }
            }
        ],
        "outputContexts": [
            {
                "name": "projects\/botfolio-jnxcqb\/agent\/sessions\/4a589fbc-7972-363c-b4f9-d95b1c4f3c79\/contexts\/__system_counters__",
                "lifespanCount": 1,
                "parameters": {
                    "no-input": 0,
                    "no-match": 1
                }
            }
        ],
        "intent": {
            "name": "projects\/botfolio-jnxcqb\/agent\/intents\/14da6584-49b5-4d82-8d27-5ae0bba90ff2",
            "displayName": "Default Fallback Intent",
            "isFallback": true
        },
        "intentDetectionConfidence": 1,
        "languageCode": "th"
    },
    "originalDetectIntentRequest": {
        "source": "line",
        "payload": {
            "data": {
                "timestamp": "1591515751117",
                "type": "message",
                "replyToken": "546fb81065814f7bb184aab54e859174",
                "source": {
                    "type": "user",
                    "userId": "U26e4e6446cc1063b57b722805a357518"
                },
                "message": {
                    "id": "12101998829085",
                    "type": "text",
                    "text": "\u0e44\u0e1e\u0e49\u0e33\u0e30\u0e48\u0e31\u0e1e"
                }
            }
        }
    },
    "session": "projects\/botfolio-jnxcqb\/agent\/sessions\/4a589fbc-7972-363c-b4f9-d95b1c4f3c79"
}
 */