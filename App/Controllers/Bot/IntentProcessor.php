<?php


namespace App\Controllers\Bot;


use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalInstanceResult;
use App\Database\Models\User;
use App\Models\BotAction;
use App\Models\BotIntent;
use App\Models\BotMessage;
use App\Models\Intents\ApproveFormIntent;
use App\Models\Intents\DefaultFallbackIntent;
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

        // $defaultText = "\n---\n" .
        //     "message-text: {$intent->messageText}\n" .
        //     "fulfillment: {$intent->fulfillmentText}\n" .
        //     "params: " . json_encode($intent->parameters) . "\n" .
        //     "intent: {$intent->intentName} ({$intent->intentDisplayName})\n" .
        //     "lineUserId: {$intent->lineUserId}\n" .
        //     "IntentClass: " . get_class($intent);
        $defultText = "";

        $replyToken = $intent->replyToken;
        $user = $this->getUser($intent->lineUserId);

        if (!$this->alreadyAskForWelcomeQuestion($bot, $intent)) {
            return;
        }

        if (strpos($intent->messageText, "https://") != false ) {

        } elseif ($intent instanceof DefaultFallbackIntent) {

            $txt = $intent->messageText;
            $reply = new TextMessage("original text: $txt\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());

        } elseif ($intent instanceof TrainingRequestIntent) {
            $token = $user ? $user->token : '';
            $url = Url::applyform($token, $intent->parameters['data-course-name']);
            //"https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=1&data-course-name=" . urlencode($intent->parameters['data-course-name']);

            $reply = new TextMessage($url . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        } //
        elseif ($intent instanceof ApproveFormIntent) {
            $id = $intent->parameters['approval-instance-id'];

            $instance = ApprovalInstance::query()->where('id', '=', $id)->first();
            $result = ApprovalInstanceResult::query()->where('approval_instance_id', '=', $id)->first();
            $target = User::fromId($instance->user_id);

            $nextApprover = null;

            switch ($instance->approval_type_id) {
                case 1:

                    //A1: Done! -> A2 A3
                    // เหลือ H1 เป็นตนสุดท้าย ถ้าให้ผ่านก็ส่งกลับหา user เลย
                    if (!is_null($result->H2_approve)) {
                        $result->H1_approve = $user->id;
                        $result->save();

                        $nextFormType = $target->isOfficer() ? 'A3' : 'A2';
                        $confirmMsg = new ConfirmDialogMessage("H1 approve your form. Next, you have to submit form {$nextFormType}?", [
                            "Request Form" => "cmd:request-form-type:{$nextFormType}",
                            "Cancel" => "cancel",
                        ]);
                        $bot->pushMessage($target->lineUserId, $confirmMsg->getMessageBuilder());
                        return;

                    }
                    // H3 กรอกแล้ว รอ H2 อยู่ -- ถ้า H2 กรอกแล้ว คนต่อไปจะเป็น H1
                    elseif (!is_null($result->H3_approve)) {
                        $H1 = User::fromId($instance->H1_approver_id);
                        $nextApprover = $H1;

                        $result->H2_approve = $user->id;
                        $result->save();

                    }
                    // H4 กรอกแล้ว รอ H3 อยู่ -- ถ้า H3 กรอกแล้ว คนต่อไปจะเป็น H2
                    elseif (!is_null($result->H4_approve)) {
                        $H2 = User::fromId($instance->H2_approver_id);
                        $nextApprover = $H2;

                        $result->H3_approve = $user->id;
                        $result->save();
                    }
                    // H4 ว่างอยู่
                    else {
                        $H3 = User::fromId($instance->H3_approver_id);
                        $nextApprover = $H3;

                        $result->H4_approve = $user->id;
                        $result->save();
                    }

                    $reply = new TextMessage("อนุมัติแล้ว" . "\n" . $defaultText);
                    $bot->replyMessage($replyToken, $reply->getMessageBuilder());

                    if ($nextApprover) {
                        $url = Url::viewform($id);
                        $msg = new TextMessage("มีคนส่ง approve id ' . $id . ' รอให้คุณ approve อยู่\n$url" . "\n" . $defaultText);
                        $bot->pushMessage($nextApprover->lineUserId, $msg->getMessageBuilder());

                        $url = Url::rejectform($id, $target->token);
                        $confirmMsg = new ConfirmDialogMessage("Approve form {$id} ?", [
                            "Approve" => "cmd:approve-form:{$id}",
                            "Reject" => "cmd:reject-form:{$id}\n{$url}",
                        ]);
                        $bot->pushMessage($nextApprover->lineUserId, $confirmMsg->getMessageBuilder());
                    }
                    return;


                case 5:
                    //A5
                    if (is_null($result->H4_approve)) {
                        $H4 = User::fromId($instance->H1_approver_id);
                        $approverLineId = $H4->lineUserId;

                        $result->H4_approve = $user->id;
                        $result->save();
                    } elseif (is_null($result->H1_approve)) {
                        $H1 = User::fromId($instance->H1_approver_id);
                        $approverLineId = $H1->lineUserId;

                        $result->H1_approve = $user->id;
                        $result->save();
                    } else {
                        $approverLineId = null;
                    }

                    if ($approverLineId) {
                        $viewUrl = Url::viewform($id);

                        $msg = new TextMessage("มีคนส่ง approve id ' . $id . ' รอให้คุณ approve อยู่\n$viewUrl" . "\n" . $defaultText);
                        $bot->pushMessage($approverLineId, $msg->getMessageBuilder());

                        $rejectUrl = Url::rejectform($id, $target->token);
                        $confirmMsg = new ConfirmDialogMessage("Approve form {$id} ?", [
                            "Approve" => "cmd:approve-form:{$id}",
                            "Reject" => "cmd:reject-form:{$id}\n{$rejectUrl}",
                        ]);
                        $bot->pushMessage($approverLineId, $confirmMsg->getMessageBuilder());
                    }
                    return;
            }

        } elseif ($intent instanceof RequestFromTypeIntent) {
            $token = $user ? $user->token : '';

            $type = $intent->parameters['request-form-type'];
            $url = Url::applyA2A3form($token, $type);

            $urlA4 = Url::applyA4form($token);

            $reply = new TextMessage($url . "\n" .
                "\n" .
                "ถ้าไปอบรมเกิน 1 เดือน ให้กรอกใบข้อล่างด้วย\n" .
                $urlA4 . "\n" .
                $defaultText
            );
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        } elseif ($intent instanceof TrainingCompleteIntent) {
            $token = $user ? $user->token : '';

            $approvalInstanceId = $intent->parameters['approval-instance-id'];
            $action = $intent->parameters['training-complete-action'];

            if ($action == 'done' || empty($approvalInstanceId)) {
                $instances = ApprovalInstance::query()
                    ->where('user_id', '=', $user->id)
                    ->where('status', '=', 0)
                    ->where('approval_type_id', '=', 1)
                    ->get();

                $items = array_map(function ($instance) use ($token) {
                    return CarouselMessage::item($instance['id'], "id: {$instance['id']}", "เลือกเพื่อแจ้งว่าสำเร็จแล้ว", "กรุณาข้อมูลในฟอร์มนี้ " . Url::applyA50form($token));
                }, $instances->toArray());

                if (empty($items)) {
                    $reply = new TextMessage("ไม่มีการอบรบที่ยังค้างอยู่ของคุณเลย\n" . $defaultText);
                    $bot->replyMessage($replyToken, $reply->getMessageBuilder());
                } else {
                    //กรุณาเลือกการอบรมที่ต้องการแจ้งว่าสำเร็จแล้ว
                    $reply = new CarouselMessage("Select Form", $items);
                    $bot->replyMessage($replyToken, $reply->getMessageBuilder());
                }
            } elseif ($approvalInstanceId) {
                $reply = new TextMessage("แจ้งการอบรบสำเร็จแล้ว");
                $bot->replyMessage($replyToken, $reply->getMessageBuilder());
            }
        }

        //Test default case
        $bot->replyMessage($replyToken, (new TextMessage($defaultText))->getMessageBuilder());
    }

    private function alreadyAskForWelcomeQuestion(LINEBot $bot, BotIntent $intent)
    {
        $defaultText = "\n---\n" .
            "message-text: {$intent->messageText}\n" .
            "fulfillment: {$intent->fulfillmentText}\n" .
            "params: " . json_encode($intent->parameters) . "\n" .
            "intent: {$intent->intentName} ({$intent->intentDisplayName})\n" .
            "lineUserId: {$intent->lineUserId}\n" .
            "IntentClass: " . get_class($intent);

        $replyToken = $intent->replyToken;
        $user = $this->getUser($intent->lineUserId);

        if (is_null($user)) {
            $user = new User();
            $user->lineUserId = $intent->lineUserId;
            $user->created_at = $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            $user->token = "u{$user->id}";
            $user->save();
        }

        if ($user->isCompleteInfo()) {
            return true;
        }

        if (!$user->isCompleteInfo() && !$user->hasPendingQuestion()) {
            $user->setPendingQuestion(User::PENDING_QUESTION_FIRST_NAME)->save();
            $reply = new TextMessage("คุณชื่ออะไร?" . "\n" . $defaultText);
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
            return false;
        }


        if ($user->hasPendingQuestion()) {
            $question = $user->getPendingQuestion();
            $answer = $intent->messageText;
            switch ($question) {
                case User::PENDING_QUESTION_FIRST_NAME:
                    $user->info_first_name = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_LAST_NAME)->save();
                    $replyMessage = 'นามสกุลอะไร?';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
                    break;
                case User::PENDING_QUESTION_LAST_NAME:
                    $user->info_last_name = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_MAJOR)->save();
                    $replyMessage = 'สาขาวิชาอะไร?';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
                    break;
                case User::PENDING_QUESTION_MAJOR:
                    $user->info_major = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_FACULTY)->save();
                    $replyMessage = 'คณะอะไร?';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
                    break;
                case User::PENDING_QUESTION_FACULTY:
                    $user->info_faculty = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_POSITION)->save();
                    $replyMessage = 'ตำแหน่งอะไร?';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
                    break;
                case User::PENDING_QUESTION_POSITION:
                    $user->info_position = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_USER_TYPE)->save();

                    $replyMessage = 'คุณเป็นผู้ใช้ระดับอะไร?';

                    $items = [
                        CarouselMessage::item(0, 'ผู้ใช้', 'ผู้ใช้แบบธรรมดา', '0'),
                        CarouselMessage::item(1, 'H1', 'ผู้ใช้แบบ1', '1'),
                        CarouselMessage::item(2, 'H2', 'ผู้ใช้แบบ2', '2'),
                        CarouselMessage::item(3, 'H3', 'ผู้ใช้แบบ3', '3'),
                        CarouselMessage::item(4, 'H4', 'ผู้ใช้แบบ4', '4'),
                    ];
                    $reply = new CarouselMessage($replyMessage, $items);

                    break;
                case User::PENDING_QUESTION_USER_TYPE:
                    $user->user_type_id = $answer;
                    $user->setPendingQuestion(User::PENDING_QUESTION_EMPLOYEE_TYPE)->save();
                    $replyMessage = 'เป็นพนักงานประเภทไหน?';
                    $reply = new ConfirmDialogMessage($replyMessage, [
                        'ข้าราชการ' => 'officer',
                        'พนักงาน' => 'employee',
                    ]);
                    break;
                case User::PENDING_QUESTION_EMPLOYEE_TYPE:
                    $user->employee_type = $answer;
                    $user->completeInfo()->save();
                    $replyMessage = 'ใส่ข้อมูลพื้นฐานครบแล้ว';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
                    break;
                default:
                    $replyMessage = 'XXX';
                    $reply = new TextMessage($replyMessage . "\n" . $defaultText);
            }
            $bot->replyMessage($replyToken, $reply->getMessageBuilder());
            return false;
        }


        $reply = new TextMessage("ERROR!\n" . $defaultText);
        $bot->replyMessage($replyToken, $reply->getMessageBuilder());
        return false;

    }
}