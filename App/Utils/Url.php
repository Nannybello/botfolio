<?php


namespace App\Utils;


class Url
{
    public static function applyform($token, $courseName)
    {
        return "https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=1&data-course-name=" . urlencode($courseName);
    }

    public static function viewform($id)
    {
        return "https://botfolio.beautyandballoon.com/viewform/$id";
    }

    public static function rejectform($id, $token)
    {
        return "https://botfolio.beautyandballoon.com/rejectform/$id?token=$token";
    }
}