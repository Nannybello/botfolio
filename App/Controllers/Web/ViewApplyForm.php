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

class ViewApplyForm
{
    private $formLoader;

    /**
     * @var LINEBot $bot
     */
    private $bot;

    public function __construct()
    {
        $this->formLoader = new FormLoader();
    }

    public function index(int $id)
    {
        $instance = ApprovalInstance::query()->where('id', '=', $id)->first();

        $approvalType = ApprovalType::query()->findOrFail($instance->approval_type_id);
        $formType = FormType::of($approvalType)->first();
        $data = json_decode($instance->data, true);
        $content = $this->formLoader->load($formType->name, $data);

        View::render('view_apply_form', [
            'formContent' => $content,
            'data' => $data,
        ]);


    }
}