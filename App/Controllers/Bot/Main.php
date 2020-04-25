<?php

namespace App\Controllers\Bot;

use App\Database\Models\User;
use App\Models\BotInput;
use App\Models\BotMessage;
use App\Models\Input\BotInputCommand;
use App\Models\Output\BotOutputString;

class Main
{
    public function index(BotInput $input, User $user): BotMessage
    {
        if (!$user->isCompleteInfo() && !$user->hasPendingQuestion()) {
            $user
                ->setPendingQuestion(User::PENDING_QUESTION_FIRST_NAME)
                ->save();
            return new BotOutputString('enter your first name ?');
        }

        if ($user->hasPendingQuestion()) {
            $question = $user->getPendingQuestion();
            $answer = $input->getRawInputString();
            switch ($question) {
                case User::PENDING_QUESTION_FIRST_NAME:
                    $user->info_first_name = $answer;
                    $user
                        ->setPendingQuestion(User::PENDING_QUESTION_LAST_NAME)
                        ->save();
                    return new BotOutputString('enter your last name ?');
                case User::PENDING_QUESTION_LAST_NAME:
                    $user->info_last_name = $answer;
                    $user
                        ->setPendingQuestion(User::PENDING_QUESTION_MAJOR)
                        ->save();
                    return new BotOutputString('enter your major ?');
                case User::PENDING_QUESTION_MAJOR:
                    $user->info_major = $answer;
                    $user
                        ->setPendingQuestion(User::PENDING_QUESTION_FACULTY)
                        ->save();
                    return new BotOutputString('enter your faculty ?');
                case User::PENDING_QUESTION_FACULTY:
                    $user->info_faculty = $answer;
                    $user
                        ->setPendingQuestion(User::PENDING_QUESTION_POSITION)
                        ->save();
                    return new BotOutputString('enter your position ?');
                case User::PENDING_QUESTION_POSITION:
                    $user->info_position = $answer;
                    $user
                        ->completeInfo()
                        ->save();
            }
        }

        if ($input instanceof BotInputCommand) {
            return $this->runCommand($input, $user);
        }

        return new BotOutputString('hi ?');
    }

    function runCommand(BotInputCommand $input, User $user): BotMessage
    {
        if($input->is(BotInputCommand::REQUEST_FORM)){

        }
        return new BotOutputString('command not found !!');
    }
}