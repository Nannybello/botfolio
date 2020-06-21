<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalInstanceResult;
use App\Database\Models\ApprovalType;
use App\Database\Models\Attachment;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\ScheduleMessage;
use App\Database\Models\User;
use App\Models\Messages\ConfirmDialogMessage;
use App\Models\Messages\TextMessage;
use App\Utils\FormLoader;
use App\Utils\Url;
use App\Views\View;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use function GuzzleHttp\Promise\queue;

class ApplyFormSubmit
{
    private $formLoader;

    /**
     * @var LINEBot $bot
     */
    private $bot;

    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
        $this->formLoader = new FormLoader();
    }

    public function index()
    {
        $rawData = $_POST;
        echo '<pre>';
        print_r($rawData);
        echo '</pre>';
        $user = User::fromToken($rawData['token']);
        $approvalTypeId = $rawData['approval_type_id'];
        $H1approverId = $rawData['H1_approver_id'] ?? null;
        $H2approverId = $rawData['H2_approver_id'] ?? null;
        $H3approverId = $rawData['H3_approver_id'] ?? null;
        $H4approverId = $rawData['H4_approver_id'] ?? null;
        $followUp = $rawData['follow_up'] ?? null;
        $parentId = $rawData['parent_id'] ?? null;

        //echo '<pre>';
        $data = [];
        foreach ($rawData as $field => $value) {
            if (is_string($field) && strpos($field, 'data_') == 0) {
                $f = str_replace('data_', '', $field);
                $data[$f] = $value;
            }
        }
        //print_r($data);

        $approvalType = ApprovalType::query()->findOrFail($approvalTypeId);
        $formType = FormType::of($approvalType)->first();
        $content = $this->formLoader->load($formType->name, $data);

        View::render('apply_form_submit', [
            'rawData' => $rawData,
            'formContent' => $content,
            'data' => $data,
        ]);


        $approvalInstance = new ApprovalInstance();
        $approvalInstance->user_id = $user->id;
        $approvalInstance->approval_type_id = $approvalTypeId;
        $approvalInstance->data = json_encode($data);
        $approvalInstance->follow_up = $followUp;
        $approvalInstance->created_at = date('Y-m-d H:i:s');
        $approvalInstance->H1_approver_id = $H1approverId;
        $approvalInstance->H2_approver_id = $H2approverId;
        $approvalInstance->H3_approver_id = $H3approverId;
        $approvalInstance->H4_approver_id = $H4approverId;
        if ($parentId) {
            $approvalInstance->parent_id = $parentId;
        }
        $approvalInstance->save();

        $approvalInstanceResult = new ApprovalInstanceResult();
        $approvalInstanceResult->approval_instance_id = $approvalInstance->id;
        $approvalInstanceResult->save();

        echo "id: " . $approvalInstance->id;

        $files = empty($rawData['attach_files']) ? [] : $rawData['attach_files'];
        foreach ($files as $fileId) {
            $attach = new Attachment();
            $attach->file_id = $fileId;
            $attach->approval_instance_id = $approvalInstance->id;
            $attach->save();
        }

        switch ($approvalTypeId) {
            case 1:
                $approvers = User::query()->where('id', '=', $H4approverId)->get();
                foreach ($approvers as $approver) {
                    $approverLineId = $approver->lineUserId;
                    $url = Url::viewform($approvalInstance->id);
                    $msg = new TextMessage("มีคนส่ง approve id ' . $approvalInstance->id . ' รอให้คุณ approve อยู่\n$url");
                    $this->bot->pushMessage($approverLineId, $msg->getMessageBuilder());

                    $url = Url::rejectform($approvalInstance->id, $user->token);
                    $confirmMsg = new ConfirmDialogMessage("Approve form {$approvalInstance->id} ?", [
                        "Approve" => "cmd:approve-form:{$approvalInstance->id}",
                        "Reject" => "cmd:reject-form:{$approvalInstance->id}\n{$url}",
                    ]);
                    $this->bot->pushMessage($approverLineId, $confirmMsg->getMessageBuilder());
                }
                break;
            case 50:
                $approver = User::query()->whereIn('id', [$H4approverId])->first();

                $approverLineId = $approver->lineUserId;
                $url50 = Url::viewform($approvalInstance->id);
                $url51 = Url::applyA51form($approver->token, $approvalInstance->id);
                $msg = new TextMessage(
                    "มีคนส่ง approve id " . $approvalInstance->id .
                    " รอให้คุณ approve อยู่\n" .
                    "ดูฟอร์มได้ที่ $url50\n" .
                    "แสดงความเห็นที่ $url51"
                );
                $this->bot->pushMessage($approverLineId, $msg->getMessageBuilder());
                break;
            case 51:
                $approver = User::query()->whereIn('id', [$H1approverId])->first();

                $approverLineId = $approver->lineUserId;
                $url50 = Url::viewform($parentId);
                $url51 = Url::viewform($approvalInstance->id);
                $url52 = Url::applyA51form($approver->token);
                $msg = new TextMessage(
                    "มีคนส่ง approve id " . $approvalInstance->id .
                    " รอให้คุณ approve อยู่\n" .
                    "ดูฟอร์มได้ที่ $url50\n" .
                    "ดูฟอร์มได้ที่ $url51\n" .
                    "แสดงความเห็นที่ $url52"
                );
                $this->bot->pushMessage($approverLineId, $msg->getMessageBuilder());

//                $url = Url::rejectform($approvalInstance->id, $user->token);
//                $confirmMsg = new ConfirmDialogMessage("Approve form {$approvalInstance->id} ?", [
//                    "Approve" => "cmd:approve-form:{$approvalInstance->id}",
//                    "Reject" => "cmd:reject-form:{$approvalInstance->id}\n{$url}",
//                ]);
//                $this->bot->pushMessage($approverLineId, $confirmMsg->getMessageBuilder());


                if ($followUp) {
                    $final = date("Y-m-d", strtotime("+$followUp month", time()));

                    $task = new ScheduleMessage();
                    $task->lineUserId = $user->lineUserId;
                    $task->message = "follow up ของคุณถึงกำหนดแล้ว ให้ส่งฟอร์มนี้ " . Url::applyA60form($user->token);
                    $task->send_at = $final;
                    $task->issue_at = date('Y-m-d H:i:s');
                    $task->save();
                }

                break;
        }
    }
}