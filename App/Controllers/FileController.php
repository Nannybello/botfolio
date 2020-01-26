<?php


namespace App\Controllers;

use App\Database\Models\FilesInfo;
use function App\Helpers\getUserFileStoragePath;
use function App\Helpers\makeLink;
use function App\Helpers\userFileUrl;

class FileController extends BaseController
{
    public function index(): array
    {
        $files = FilesInfo::query()->where('user_id', '=', $this->userId)->orderBy('created_at', 'desc')->get()->toArray();
        return $files;
    }

    public function saveFile(string $filename, $binaryData)
    {
        try {
            file_put_contents(getUserFileStoragePath($filename, $this->userId), $binaryData);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}