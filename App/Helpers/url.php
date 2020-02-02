<?php

namespace App\Helpers;

function userFileUrl(string $filename, string $userId): string
{
    return "https://botfolio.beautyandballoon.com/storage/user_files/$userId/$filename";
}

function userThumbnailUrl(string $filename, string $userId): string
{
    return "https://botfolio.beautyandballoon.com/storage/file-iconpng.png";
}

function getUserFileStoragePath(string $filename, string $userId): string
{
    return ROOT_PATH . "/storage/user_files/$userId/$filename";
}

function makeLink(string $url)
{
    return "link: $url";
}