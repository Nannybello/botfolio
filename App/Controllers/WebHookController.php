<?php

namespace App\Controllers;


use App\Commands\BaseCommands;
use LINE\LINEBot;
use LINE\LINEBot\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WebHookController
{
    public function index(LINEBot $bot, array $event): Response
    {

        $logger = new Logger('channel-name');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/storage/app.log', Logger::DEBUG));

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


