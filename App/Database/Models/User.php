<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const PENDING_QUESTION_FIRST_NAME = 'FIRST_NAME';
    const PENDING_QUESTION_LAST_NAME = 'LAST_NAME';
    const PENDING_QUESTION_MAJOR = 'MAJOR';
    const PENDING_QUESTION_FACULTY = 'FACULTY';
    const PENDING_QUESTION_POSITION = 'POSITION';

    protected $table = 'line_user';

    public static function fromToken(string $token): ?User
    {
        return self::query()->where('token', '=', $token)->first();
    }

    public static function fromId($id): ?User
    {
        return self::query()->where('id', '=', $id)->first();
    }

    public function hasPendingQuestion(): bool
    {
        return $this->pending_question ? true : false;
    }

    public function getPendingQuestion(): string
    {
        return $this->pending_question;
    }

    public function setPendingQuestion(string $question): User
    {
        $this->pending_question = $question;
        return $this;
    }

    public function completeInfo(): User
    {
        $this->pending_question = '';
        $this->info_status = 1;
        return $this;
    }

    public function isCompleteInfo(): bool
    {
        return $this->info_status == 1;
    }

    public function isOfficer(): bool
    {
        return $this->employee_type == 'officer';
    }
}