<?php


namespace App\Controllers\Bot;


use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalInstanceResult;
use App\Database\Models\User;
use App\Models\BotAction;
use App\Models\BotIntent;
use App\Models\BotMessage;
use App\Models\Intents\ApproveFormIntent;
use App\Models\Intents\RequestFromTypeIntent;
use App\Models\Intents\TrainingCompleteIntent;
use App\Models\Intents\TrainingRequestIntent;
use App\Models\Messages\CarouselMessage;
use App\Models\Messages\ConfirmDialogMessage;
use App\Models\Messages\TextMessage;
use App\Utils\Url;
use LINE\LINEBot;
use mysql_xdevapi\Exception;

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
            "lineUserId: {$intent->lineUserId}\n" .
            "IntentClass: " . get_class($intent);

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

            $nextApprover = $approverLineId = null;

            //Done!
            if (!is_null($result->H1_approve)) {
                $nextFormType = $target->isOfficer() ? 'A3' : 'A2';
                $confirmMsg = new ConfirmDialogMessage("H1 approve your form. Next, you have to submit form {$nextFormType}?", [
                    "Request Form" => "cmd:request-form-type:{$nextFormType}",
                    "Cancel" => "cancel",
                ]);
                $bot->pushMessage("U8d979fc1b1a2b976ec6fc65c54e288f1", $confirmMsg->getMessageBuilder());
                $bot->pushMessage($target->lineUserId, $confirmMsg->getMessageBuilder());
                return;
            }

            //in approving step
            if (!is_null($result->H2_approve)) {
                $H1 = User::fromId($instance->H1_approver_id);
                $approverLineId = $H1->lineUserId;
                $nextApprover = $H1;

                $result->H1_approve = $user->id;
                $result->save();

            } elseif (!is_null($result->H3_approve)) {
                $H2 = User::fromId($instance->H2_approver_id);
                $approverLineId = $H2->lineUserId;
                $nextApprover = $H2;

                $result->H2_approve = $user->id;
                $result->save();

            } elseif (!is_null($result->H4_approve)) {
                $H3 = User::fromId($instance->H3_approver_id);
                $approverLineId = $H3->lineUserId;
                $nextApprover = $H3;

                $result->H3_approve = $user->id;
                $result->save();
            } else {
                $result->H4_approve = $user->id;
                $result->save();
            }

            $reply = new TextMessage("อนุมัติแล้ว" . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());

            if ($nextApprover && $approverLineId) {
                $url = Url::viewform($id);
                $msg = new TextMessage("มีคนส่ง approve id ' . $id . ' รอให้คุณ approve อยู่\n$url" . "\n" . $defaultText);
                $bot->pushMessage($approverLineId, $msg->getMessageBuilder());

                $url = Url::rejectform($id, $H3->token);
                $confirmMsg = new ConfirmDialogMessage("Approve form {$id} ?", [
                    "Approve" => "cmd:approve-form:{$id}",
                    "Reject" => "cmd:reject-form:{$id}\n{$url}",
                ]);
                $bot->pushMessage($approverLineId, $confirmMsg->getMessageBuilder());
            }
        } elseif ($intent instanceof RequestFromTypeIntent) {
            $user = $this->getUser($intent->lineUserId);
            $token = $user ? $user->token : '';

            $type = $intent->parameters['request-form-type'];
            $url = Url::applyA2A3form($token, $type);

            $reply = new TextMessage($url . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        } elseif ($intent instanceof TrainingCompleteIntent) {
            $user = $this->getUser($intent->lineUserId);

//            $reply = new TextMessage("กรุณาเลือกการอบรมที่ต้องการแจ้งว่าสำเร็จแล้ว" . "\n" . $defaultText);
//            $bot->replyMessage($replyToken, $reply->getMessageBuilder());

            $instances = ApprovalInstance::query()
                ->where('user_id', '=', $user->id)
                ->where('status', '=', 0)
                ->get();

            $items = array_map(function ($instance) {
                return CarouselMessage::item($instance['id'], "id: {$instance['id']}", "เลือกเพื่อแจ้งว่าสำเร็จแล้ว");
            }, $instances->toArray());

            //กรุณาเลือกการอบรมที่ต้องการแจ้งว่าสำเร็จแล้ว
            $reply = new CarouselMessage("img", $items);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        }


        $bot->replyMessage($replyToken, (new TextMessage($defaultText))->getMessageBuilder());
    }
}