<?php

//echo "test botfolio";
//include 'public/index.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/line_bot.php');

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(__DIR__ . '/storage/app.log', Logger::DEBUG));

$content = file_get_contents('php://input');
$logger->info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));


//


$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);

if (!is_null($events)) {
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
}

$text = $events['events'][0]['message']['text'];

// ส่วนของคำสั่งจัดเตียมรูปแบบข้อความสำหรับส่ง
$textMessageBuilder = new TextMessageBuilder(json_encode($events) . "\nคุณถามเข้ามาว่า $text");

//l ส่วนของคำสั่งตอบกลับข้อความ
$response = $bot->replyMessage($replyToken ?? "", $textMessageBuilder);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
} else {
    echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
}