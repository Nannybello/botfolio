<?php

namespace App\Router\Handler;


use App\Commands\BaseCommands;
use LINE\LINEBot;
use LINE\LINEBot\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebHookHandler
{
    public function index(LINEBot $bot, array $event): Response
    {
        error_log(json_encode($event));

        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/main-log.log', Logger::DEBUG));

        try {
            $replyToken = $this->getReplyToken($event);
            foreach ($this->getCommands() as $CommandName) {
                /**
                 * @var BaseCommands $command
                 */
                $command = new $CommandName($bot, $event, $replyToken);
                if ($command->canHandle()) {
                    return $command->getResponse();
                }
            }
            throw new \Exception("No Handler command!");
        } catch (\Exception $e) {
            $logger->alert($e->getMessage());
        }

        return null;
    }

    private function getReplyToken(array $event): ?string
    {
        return $event['replyToken'];
    }

    private function getCommands(): array
    {
        return include ROOT_PATH . '/App/Config/commands.php';
    }
}


