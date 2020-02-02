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

    public function saveUserFile(string $ori_filename, $binaryData, int $userId)
    {
        $s = explode('.', $ori_filename);
        $ext = end($s);
        $filename = date('Ymd-His') . '.' . $ext;

        try {
            if (!$this->saveFile($filename, $binaryData)) {
                $this->logger->alert('CANNOT SAVE FILE');
                return false;
            }

            $rec = new FilesInfo();
            $rec->filename = $filename;
            $rec->filename_original = $ori_filename;
            $rec->filetype = $ext;
            $rec->user_id = $userId;
            $rec->created_at = date('Y-m-d H:i:s');
            $rec->save();
            $this->logger->debug(json_encode($rec->toArray()));
        } catch (Exeption $e) {
            $this->logger->alert($e->getMessage());
        }

        return true;
    }

    public function saveFile(string $filename, $binaryData)
    {
        try {
            file_put_contents(getUserFileStoragePath($filename, $this->userId), $binaryData);
            return true;
        } catch (Exception $e) {
            $this->logger->alert($e->getMessage());
            return false;
        }
    }
}