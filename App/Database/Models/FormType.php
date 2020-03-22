<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;
use App\Database\Models\ApprovalType;

class FormType extends Model
{
    protected $table = 'form_type';

    /**
     * @param ApprovalType $approvalType
     * @return FormType[]
     */
    public static function of(ApprovalType $approvalType)
    {
        return self::query()->where('approval_type_id', '=', $approvalType->id)->get();
    }
}