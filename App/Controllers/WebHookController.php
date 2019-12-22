<?php

namespace App\Controllers;


use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebHookController
{
    public function index(): string
    {

        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/app.log', Logger::DEBUG));

        $content = file_get_contents('php://input');
        $logger->info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));


//


        $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
        $bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

        $events = json_decode($content, true);

        if (!is_null($events)) {
            // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
            $replyToken = $events['events'][0]['replyToken'];
        }

        $text = $events['events'][0]['message']['text'];

        $textMessageBuilder = new TextMessageBuilder(json_encode($events) . "\nคุณถามเข้ามาว่า $text");

        $response = $bot->replyMessage($replyToken ?? "", $textMessageBuilder);
        if ($response->isSucceeded()) {
            return 'ok';
        } else {
            return $response->getHTTPStatus() . ' ' . $response->getRawBody();
        }
    }
}


