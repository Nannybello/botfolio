<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalType;
use App\Database\Models\Attachment;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\User;
use App\Models\Messages\TextMessage;
use App\Utils\FormLoader;
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
        $user = User::fromToken($rawData['token']);
        $approvalTypeId = $rawData['approval_type_id'];
        $approverId = $rawData['approver_id'];

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
        $approvers = User::query()->where('id', '=', $approverId)->get();
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
        $approvalInstance->created_at = date('Y-m-d H:i:s');
        $approvalInstance->approver_id = $approverId;
        $approvalInstance->save();

        echo "id: " . $approvalInstance->id;

        $files = empty($rawData['attach_files']) ? [] : $rawData['attach_files'];
        foreach ($files as $fileId) {
            $attach = new Attachment();
            $attach->file_id = $fileId;
            $attach->approval_instance_id = $approvalInstance->id;
            $attach->save();
        }

        foreach ($approvers as $approver) {
            $approverLineId = $approver->lineUserId;
            $msg = new TextMessage('มีคนส่ง approve id ' . $approvalInstance->id . ' รอให้คุณ approve อยู่');
            $this->bot->pushMessage($approverLineId, $msg->getMessageBuilder());
        }
    }
}