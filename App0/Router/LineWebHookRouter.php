<?php

namespace App\Router;

use App\Router\Handler\WebHookHandler;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LineWebHookRouter
{
    public function route()
    {
        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/main-log.log', Logger::DEBUG));

        $content = file_get_contents('php://input');
        $logger->info(json_encode(["GET" => $_GET, "POST" => $_POST, "CONTENT" => $content, "HEADERS" => getallheaders()]));


        $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
        $bot = new LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

        $events = json_decode($content, true);

        try {

            foreach ($events['events'] as $event) {
                $controller = new WebHookHandler();
                $response = $controller->index($bot, $event);
                if (!$response->isSucceeded()) {
                    return $response->getHTTPStatus() . ' ' . $response->getRawBody();
                }
            }
            return true;
        } catch (\Exception $e) {
            $logger->alert($e->getMessage());
            return false;
        }
    }
}