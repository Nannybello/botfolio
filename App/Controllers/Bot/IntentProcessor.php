<?php


namespace App\Controllers\Bot;


use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalInstanceResult;
use App\Database\Models\User;
use App\Models\BotAction;
use App\Models\BotIntent;
use App\Models\BotMessage;
use App\Models\Intents\ApproveFormIntent;
use App\Models\Intents\TrainingRequestIntent;
use App\Models\Messages\ConfirmDialogMessage;
use App\Models\Messages\TextMessage;
use App\Utils\Url;
use LINE\LINEBot;

class IntentProcessor
{
    function getUser(string $lineUserId): ?User
    {
        return User::query()->where('lineUserId', '=', $lineUserId)->first();
    }

    public function process(LINEBot $bot, BotIntent $intent)
    {

        $defaultText = "\n---\n" .
            "fulfillment: {$intent->fulfillmentText}\n" .
            "params: " . json_encode($intent->parameters) . "\n" .
            "intent: {$intent->intentName} ({$intent->intentDisplayName})\n" .
            "lineUserId: {$intent->lineUserId}";

        $replyToken = $intent->replyToken;

        if ($intent instanceof TrainingRequestIntent) {
            $user = $this->getUser($intent->lineUserId);
            $token = $user ? $user->token : '';
            $url = Url::applyform($token, $intent->parameters['data-course-name']);
            //"https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=1&data-course-name=" . urlencode($intent->parameters['data-course-name']);

            $reply = new TextMessage($url . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        } //
        elseif ($intent instanceof ApproveFormIntent) {
            $user = $this->getUser($intent->lineUserId);
            $id = $intent->parameters['approval-instance-id'];

            $instance = ApprovalInstance::query()->where('id', '=', $id)->first();
            $result = ApprovalInstanceResult::query()->where('approval_instance_id', '=', $id)->first();
            $target = User::fromId($instance->user_id);

            if (!is_null($result->H1_approve)) {

            } elseif (!is_null($result->H2_approve)) {

            } elseif (!is_null($result->H3_approve)) {

            } elseif (!is_null($result->H4_approve)) {
                $H3 = User::fromId($instance->H3_approver_id);
                $approverLineId = $H3->lineUser_id;
            }

            $reply = new TextMessage("อนุมัติแล้ว");
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());


            $url = Url::viewform($id);
            $msg = new TextMessage("มีคนส่ง approve id ' . $id . ' รอให้คุณ approve อยู่\n$url");
            $bot->pushMessage($approverLineId, $msg->getMessageBuilder());

            $url = Url::rejectform($id, $H3->token);
            $confirmMsg = new ConfirmDialogMessage("Approve form {$id} ?", [
                "Approve" => "cmd:approve-form:{$id}",
                "Reject" => "cmd:reject-form:{$id}\n{$url}",
            ]);
            $bot->pushMessage($approverLineId, $confirmMsg->getMessageBuilder());
        }


        $bot->replyMessage($replyToken, (new TextMessage($defaultText))->getMessageBuilder());
    }
}