<?php

namespace App\Controllers\Web;

use App\Database\Models\ApprovalInstance;
use App\Database\Models\ApprovalInstanceResult;
use App\Database\Models\ApprovalType;
use App\Database\Models\Attachment;
use App\Database\Models\FileInfo;
use App\Database\Models\FormType;
use App\Database\Models\User;
use App\Models\Messages\ConfirmDialogMessage;
use App\Models\Messages\TextMessage;
use App\Utils\FormLoader;
use App\Utils\Url;
use App\Views\View;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use function GuzzleHttp\Promise\queue;

class RejectFormSubmit
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
        $approvalInstanceId = $rawData['approval_instance_id'];
        $token = $rawData['token'];
        $reason = $rawData['reason'];

        print_r($rawData);

        $user = User::fromToken($token);

        $result = ApprovalInstanceResult::query()->where('approval_instance_id', '=', $approvalInstanceId)->first();
        if ($user->user_type_id == 4) {
            $result->H4_approve = $user->id;
        } elseif ($user->user_type_id == 3) {
            $result->H3_approve = $user->id;
        } elseif ($user->user_type_id == 2) {
            $result->H2_approve = $user->id;
        } elseif ($user->user_type_id == 1) {
            $result->H1_approve = $user->id;
        }
        $result->reject_reason = $reason;

        print_r($user->toArray());
        print_r($result->toArray());

        $result->save();

        echo 'save reject reason';

        $instance = ApprovalInstance::query()->where('id', '=', $approvalInstanceId)->first();
        $userId = $instance->user_id;
        $target = User::query()->where('id', '=', $userId)->first();
        $msg = new TextMessage("apply form ของคุณไม่ผ่าน ด้วยสาเหตุ $reason");
        $this->bot->pushMessage($target->lineUserId, $msg->getMessageBuilder());
    }
}