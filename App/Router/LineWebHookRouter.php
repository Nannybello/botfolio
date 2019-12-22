<?php

namespace App\Router;

use App\Controllers\WebHookController;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LineWebHookRouter
{
    public function route()
    {
        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/app.log', Logger::DEBUG));

        $content = file_get_contents('php://input');
        $logger->info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));


        $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
        $bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

        $events = json_decode($content, true);

        foreach ($events['events'] as $event) {
            $controller = new WebHookController();
            $response = $controller->index($bot, $event);
            if (!$response->isSucceeded()) {
                return $response->getHTTPStatus() . ' ' . $response->getRawBody();
            }
        }
        return 'ok';
    }
}