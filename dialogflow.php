<?php

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Controllers\Bot\Main;
use App\Database\Models\User;
use App\Models\Input\BotInputString;
use App\Models\Input\BotInputCommand;
use App\Models\Output\BotOutputString;

define('BASE_URL', '/botfolio');
define('BASE_PATH', __DIR__);

include 'autoload.php';
include BASE_PATH . '/App/Config/line_bot.php';

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(ROOT_PATH . '/storage/dialogflow.log', Logger::DEBUG));
$logger->info("BEGIN", ["-----------------------------------------------------------"]);
$logger->info("CONTENT", [
    'GET' => $_GET,
    'POST' => $_POST,
    'header' => json_encode(getallheaders(), JSON_PRETTY_PRINT),
    'body' => json_encode(json_decode(file_get_contents('php://input')), JSON_PRETTY_PRINT),
]);

$dialogFlowPayload = json_decode(file_get_contents('php://input'));
$fulfillmentText = $dialogFlowPayload['queryResult']['fulfillmentText'];
$intent = $dialogFlowPayload['queryResult']['intent'];

$linePayload = $dialogFlowPayload['originalDetectIntentRequest']['payload'];
$replyToken = $linePayload['data']['replyToken'];
$lineUserId = $linePayload['source']['userId'];

//
//$main = new Main();
//
////$input = new BotInputString('hi');
//$input = new BotInputCommand('ขออนุมัติเข้าร่วมอบรม', BotInputCommand::REQUEST_FORM);
//
//$user = User::query()->find(1);
//$output = $main->index($input, $user);
//if ($output instanceof BotOutputString) {
//    print_r($output->getRawOutputString());
//}

$textMsg = $intent['name'] . " / " . $intent['displayName'] . " say: " . $fulfillmentText;

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);
$message = new TextMessageBuilder('');
$bot->replyMessage($this->replyToken, $message);