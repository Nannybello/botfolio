<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleMessage extends Model
{
    protected $table = 'schedule_message';

    /**
     * @param ApprovalType $approvalType
     * @return FormType[]
     */
    public static function getScheduleMessages()
    {
        return self::query()->whereRaw('now() >= send_at')->get();
    }
}