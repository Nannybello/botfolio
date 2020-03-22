<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;

class FileInfo extends Model
{
    protected $table = 'files_info';

    /**
     * @param User $user
     * @return FileInfo[]
     */
    public static function of(User $user)
    {
        return self::query()->where('user_id', '=', $user->id)->get();
    }
}