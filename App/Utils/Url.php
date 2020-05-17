<?php


namespace App\Utils;


class Url
{
    public static function applyform($token, $courseName)
    {
        return "https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=1&data-course-name=" . urlencode($courseName);
    }

    public static function applyA2A3form($token, $A)
    {
        switch ($A) {
            case 'A2':
            case '2':
                $id = 2;
                break;
            case 'A3':
            case '3':
                $id = 3;
                break;
            case 'A5':
            case '5':
                $id = 5;
                break;
            default:
                $id = null;
        }
        return "https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=$id";
    }

    public static function applyA5form($token)
    {
        return "https://botfolio.beautyandballoon.com/applyform?token=$token&approval_type_id=5";
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