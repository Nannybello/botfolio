<?php


use App\Database\Models\ScheduleMessage;
use App\Models\Messages\TextMessage;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

require 'autoload.php';

define('BASE_URL', '');
define('BASE_PATH', __DIR__);


include BASE_PATH . '/App/Config/line_bot.php';
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

$tasks = ScheduleMessage::getScheduleMessages();
foreach ($tasks as $task) {
    $msg = new TextMessage($task->message);
    $bot->pushMessage($task->lineUserId, $msg->getMessageBuilder());
    try {
        $task->delete();
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}