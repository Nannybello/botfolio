<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'line_user';

    public static function fromToken(string $token): ?User
    {
        return self::query()->where('token', '=', $token)->first();
    }
}