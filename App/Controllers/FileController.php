<?php


namespace App\Controllers;

use App\Database\Models\FilesInfo;
use function App\Helpers\makeLink;
use function App\Helpers\userFileUrl;

class FileController extends BaseController
{
    public function index(): array
    {
        $files = FilesInfo::query()->where('user_id', '=', $this->userId)->get()->toArray();
        echo json_encode([
            $files[0]['filename_original'],
            $files[0]['filetype'] . ", upload at " . $files[0]['created_at'],
            userFileUrl($files[0]['filename'], $this->userId),
            makeLink(userFileUrl($files[0]['filename'], $this->userId)),
        ]);
        return $files;
    }
}