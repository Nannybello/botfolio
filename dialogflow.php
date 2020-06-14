<?php

use App\Controllers\Bot\IntentProcessor;
use App\Models\BotIntent;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Controllers\Bot\Main;
use App\Database\Models\User;
use App\Models\Input\BotInputString;
use App\Models\Input\BotInputCommand;

include 'autoload.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow-raw-input.log', Logger::DEBUG));
$logger->info("BEGIN", ["-----------------------------------------------------------"]);
$logger->info("CONTENT", [
    'GET' => $_GET,
    'POST' => $_POST,
    'header' => json_encode(getallheaders(), JSON_PRETTY_PRINT),
    'body' => json_encode(json_decode(file_get_contents('php://input')), JSON_PRETTY_PRINT),
]);

define('BASE_URL', '/botfolio');
define('BASE_PATH', __DIR__);

$dialogFlowPayload = json_decode(file_get_contents('php://input'), true);
$fulfillmentText = $dialogFlowPayload['queryResult']['fulfillmentText'];
$intent = $dialogFlowPayload['queryResult']['intent'];
$parameters = $dialogFlowPayload['queryResult']['parameters'];

$linePayload = $dialogFlowPayload['originalDetectIntentRequest']['payload'];
$replyToken = $linePayload['data']['replyToken'];
$lineUserId = $linePayload['data']['source']['userId'];
$messageText = $linePayload['data']['message']['text'];

$processor = new IntentProcessor();
$intent = BotIntent::build($intent['name'], $intent['displayName'], $fulfillmentText, $replyToken, $lineUserId, $messageText, $parameters);


include BASE_PATH . '/App/Config/line_bot.php';
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

$processor->process($bot, $intent);